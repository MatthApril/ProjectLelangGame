<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\NotificationLog;
use App\Models\NotificationRecipient;
use App\Models\NotificationTemplate;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Mockery\Matcher\Not;

class NotificationService
{
    /**
     * Send a notification using a predefined template.
     *
     * @param int|array $userIds  One User ID or an array of User IDs
     * @param string $templateKey The key from config/notification_templates.php
     * @param array $variables    Data to replace placeholders (e.g., ['name' => 'Budi'])
     */
    public function send($userIds, $tag, array $variables = []){

        $template = NotificationTemplate::where('code_tag', $tag)->first();

        if (!$template){
            return null;
        }

        $subject = $template->subject;
        $body = $template->body;

        foreach ($variables as $key => $value){
            $subject = str_replace("{{$key}}", $value, $subject);
            $body = str_replace("{{$key}}", $value, $body);
        }

        $notification = Notification::create([
            'title' => $subject,
            'body' => $body,
            'category' => $template->category ?? 'tr',
        ]);

        $ids = is_array($userIds) ? $userIds : [$userIds];
        $recipientsData = [];

        foreach ($ids as $userId) {
            $recipientsData[] = [
                'notification_id' => $notification->notification_id,
                'user_id'         => $userId,
            ];
        }

        NotificationRecipient::insert($recipientsData);

        return $notification;
    }
    /**
     * Broadcast a notification to a large audience based on a template.
     *
     * @param string $templateTag The code tag of the notification template
     * @param string|null $targetAudience 'buyer', 'seller', 'both', or null for all users
     */
    public function broadcast($templateTag, $targetAudience = null)
    {
        $template = NotificationTemplate::where('code_tag', $templateTag)->first();
        if (!$template) return;

        $subject = $template->subject;
        $body = $template->body;
        $notification = Notification::create([
            'title' => $subject,
            'body' => $body,
            'category' => $template->category,
        ]);

        $query = User::query()->whereNull('deleted_at');
        switch ($targetAudience) {
            case 'buyer':  $query->where('role', 'user'); break;
            case 'seller': $query->where('role', 'seller'); break;
            case 'both':   $query->whereIn('role', ['user', 'seller']); break;
        }

        $recipientsCount = $query->count();

        // Menghindari memory overload dengan memproses dalam batch
        $query->chunk(1000, function ($users) use ($notification) {
            $dataToInsert = [];
            foreach ($users as $user) {
                $dataToInsert[] = [
                    'notification_id' => $notification->notification_id,
                    'user_id' => $user->user_id,
                ];
            }
            NotificationRecipient::insert($dataToInsert);
        });

        NotificationLog::create([
            'notif_temp_id' => $template->notif_temp_id,
            'admin_id' => Auth::user()->user_id,
            'target_audience' => $targetAudience ?? 'all',
            'recipients_count' => $recipientsCount,
            'sent_at' => now(),
        ]);
    }
}
