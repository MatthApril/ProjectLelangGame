<?php

namespace App\Http\Controllers;

use App\Events\SupportMessageSent;
use App\Models\Category;
use App\Models\Message;
use App\Models\SupportTickets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    const ADMIN_ID = 1;

    /**
     * Display a listing of the user's support tickets.
     */
    public function index()
    {
        $tickets = SupportTickets::where('user_id', Auth::id())
            ->with(['messages' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->orderBy('updated_at', 'desc')
            ->get();

        $categories = Category::orderBy('category_name')->get();
        $subjectCategories = $this->getSubjectCategories();

        return view('pages.user.support.index', compact('tickets', 'categories', 'subjectCategories'));
    }

    /**
     * Store a newly created support ticket in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'sub_subject' => 'required|string',
            'message' => 'required|string|min:10|max:2000',
        ], [
            'category.required' => 'Silakan pilih kategori masalah.',
            'sub_subject.required' => 'Silakan pilih jenis masalah.',
            'message.required' => 'Pesan tidak boleh kosong.',
            'message.min' => 'Pesan minimal 10 karakter.',
            'message.max' => 'Pesan maksimal 2000 karakter.',
        ]);

        // Combine category and sub_subject for the subject field
        $subject = $validated['category'] . ' - ' . $validated['sub_subject'];

        // Create the support ticket
        $ticket = SupportTickets::create([
            'user_id' => Auth::id(),
            'subject' => $subject,
            'status' => 'open',
        ]);

        // Create the initial message
        $message = Message::create([
            'ticket_id' => $ticket->ticket_id,
            'sender_id' => Auth::id(),
            'receiver_id' => self::ADMIN_ID,
            'content' => $validated['message'],
            'is_read' => false,
        ]);

        broadcast(new SupportMessageSent($message))->toOthers();

        return redirect()->route('support.show', $ticket->ticket_id)
            ->with('success', 'Tiket bantuan berhasil dibuat. Tim kami akan segera merespons.');
    }

    /**
     * Display the specified support ticket.
     */
    public function show($ticketId)
    {
        $ticket = SupportTickets::where('ticket_id', $ticketId)
            ->where('user_id', Auth::id())
            ->with(['messages.sender', 'messages.receiver'])
            ->firstOrFail();

        // Mark messages from admin as read
        Message::where('ticket_id', $ticketId)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $ticket->messages()->orderBy('created_at', 'asc')->get();

        $categories = Category::orderBy('category_name')->get();

        return view('pages.user.support.show', compact('ticket', 'messages', 'categories'));
    }

    /**
     * Reply to a support ticket.
     */
    public function reply(Request $request, $ticketId)
    {
        $ticket = SupportTickets::where('ticket_id', $ticketId)
            ->where('user_id', Auth::id())
            ->where('status', '!=', 'closed')
            ->firstOrFail();

        $validated = $request->validate([
            'message' => 'required|string|min:1|max:2000',
        ], [
            'message.required' => 'Pesan tidak boleh kosong.',
            'message.max' => 'Pesan maksimal 2000 karakter.',
        ]);

        // Create the reply message
        $message = Message::create([
            'ticket_id' => $ticketId,
            'sender_id' => Auth::id(),
            'receiver_id' => self::ADMIN_ID,
            'content' => $validated['message'],
            'is_read' => false,
        ]);

        // Update ticket status to 'open' when user replies
        $ticket->update([
            'status' => 'open',
            'updated_at' => now(),
        ]);

        broadcast(new SupportMessageSent($message))->toOthers();

        // Return JSON response for AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'message_id' => $message->message_id,
                    'content' => $message->content,
                    'created_at' => $message->created_at->format('d M Y, H:i'),
                ],
                'status' => $ticket->status,
            ]);
        }

        return redirect()->route('support.show', $ticketId)
            ->with('success', 'Balasan berhasil dikirim.');
    }

    /**
     * Get messages for a support ticket (for polling).
     */
    public function getMessages(Request $request, $ticketId)
    {
        $ticket = SupportTickets::where('ticket_id', $ticketId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $afterId = $request->input('after', 0);

        $messages = Message::where('ticket_id', $ticketId)
            ->where('message_id', '>', $afterId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'message_id' => $msg->message_id,
                    'sender_id' => $msg->sender_id,
                    'content' => $msg->content,
                    'is_admin' => $msg->sender_id == self::ADMIN_ID,
                    'created_at' => $msg->created_at->format('d M Y, H:i'),
                ];
            });

        // Mark messages from admin as read
        Message::where('ticket_id', $ticketId)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'messages' => $messages,
            'status' => $ticket->fresh()->status,
        ]);
    }

    /**
     * Close a support ticket by user.
     */
    public function close($ticketId)
    {
        $ticket = SupportTickets::where('ticket_id', $ticketId)
            ->where('user_id', Auth::id())
            ->where('status', '!=', 'closed')
            ->firstOrFail();

        $ticket->update([
            'status' => 'closed',
            'updated_at' => now(),
        ]);

        return redirect()->route('support.show', $ticketId)
            ->with('success', 'Tiket berhasil ditutup.');
    }

    /**
     * Get subject categories and their sub-subjects.
     */
    private function getSubjectCategories(): array
    {
        return [
            'Lelang' => [
                'Pemenang Kabur / Tidak Bayar',
                'Barang Tidak Sesuai Deskripsi',
                'Gagal Melakukan Bid',
                'Lelang Dibatalkan Sepihak',
                'Seller Tidak Kirim Item',
                'Dispute Hasil Lelang',
                'Item Sudah Terjual di Tempat Lain',
            ],
            'Pembayaran' => [
                'Top Up Saldo Gagal',
                'Withdraw Tertunda / Lama',
                'Pembayaran Tidak Terverifikasi',
                'Refund Belum Diterima',
                'Potongan Biaya Tidak Sesuai',
                'Saldo Tidak Masuk',
                'Tagihan Tidak Muncul',
            ],
            'Transaksi' => [
                'Pesanan Tidak Diproses',
                'Status Pesanan Tidak Update',
                'Pembatalan Pesanan',
                'Komplain Tidak Ditanggapi Seller',
                'Kendala Konfirmasi Penerimaan',
                'Item Game Tidak Diterima',
            ],
            'Akun' => [
                'Tidak Bisa Login',
                'Lupa Password',
                'Akun Terkena Suspend',
                'Ganti Email / Username',
                'Verifikasi Akun Gagal',
                'Keamanan Akun',
                'Hapus Akun',
            ],
            'Toko' => [
                'Kendala Buka Toko',
                'Produk Tidak Bisa Diupload',
                'Toko Terkena Suspend',
                'Pengaturan Toko',
                'Verifikasi Toko',
            ],
            'Lainnya' => [
                'Pertanyaan Umum',
                'Saran & Masukan',
                'Kendala Teknis Website',
                'Laporan Bug',
                'Lainnya',
            ],
        ];
    }
}
