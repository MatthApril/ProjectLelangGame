@extends('layouts.template')

@section('title', 'Pesan | LelangGame')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <strong>{{ $otherUser->username }}</strong>
            </div>
            <a href="{{ $returnUrl ?? route('chat.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> {{ $returnLabel ?? 'Kembali' }}
            </a>
        </div>

        <div class="card-body chat-box" id="chat-box" style="height: 400px; overflow-y: scroll;">
            @foreach($messages as $message)
                <div class="d-flex {{ $message->sender_id == auth()->user()->user_id ? 'justify-content-end' : 'justify-content-start' }} mb-2">
                    <div class="p-3 rounded {{ $message->sender_id == auth()->user()->user_id ? 'bg-primary text-white' : 'bg-light text-dark border' }}"
                         style="max-width: 70%;">
                        <p class="mb-1">{{ $message->content }}</p>
                        <small class="d-block text-end" style="font-size: 0.7rem; opacity: 0.7;">
                            {{ $message->created_at->format('H:i') }}
                        </small>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card-footer">
            <form id="chat-form" action="{{ route('chat.store', ['userId' => $otherUser->user_id]) }}" method="POST">
                @csrf
                <div class="input-group">
                    <input type="text" id="message-input" name="content" class="form-control" 
                           placeholder="Ketik pesan" value="{{ $autoMessage ?? '' }}" autocomplete="off">
                    <button type="submit" id="send-button" class="btn btn-primary"><i class="bi bi-send-fill"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const waitForEcho = setInterval(() => {
        if (window.Echo) {
            clearInterval(waitForEcho);
            initChat();
        }
    }, 100);

    function initChat() {
        const myId = {{ auth()->user()->user_id }};
        const partnerId = {{ $otherUser->user_id }};
        const messageInput = document.getElementById('message-input');
        const sendButton = document.getElementById('send-button');
        const chatBox = document.getElementById('chat-box');
        const form = document.getElementById('chat-form');

        function scrollToBottom() {
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function updateSendButton() {
            sendButton.disabled = !messageInput.value.trim();
        }

        updateSendButton();
        messageInput.addEventListener('input', updateSendButton);
        scrollToBottom();

        const smallerId = Math.min(myId, partnerId);
        const largerId = Math.max(myId, partnerId);
        const channelName = `chat.${smallerId}.${largerId}`;

        console.log("Joining channel:", channelName);

        window.Echo.private(channelName)
            .listen('.MessageSent', (e) => {
                console.log("Event received:", e);
                if (e.message.sender_id !== myId) {
                    appendMessage(e.message.content, 'received');
                }
            });

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const message = messageInput.value;
            if (!message.trim()) return;

            appendMessage(message, 'sent');
            messageInput.value = '';
            sendButton.disabled = true;

            axios.post(`/chat/${partnerId}`, { content: message })
                .then(response => {
                    console.log("Message saved!");
                })
                .catch(error => {
                    console.error("Error sending message:", error);
                    alert("Failed to send message.");
                });
        });

        function appendMessage(text, type) {
            const div = document.createElement('div');
            const isSent = type === 'sent';

            div.className = `d-flex ${isSent ? 'justify-content-end' : 'justify-content-start'} mb-2`;
            div.innerHTML = `
                <div class="p-3 rounded ${isSent ? 'bg-primary text-white' : 'bg-light text-dark border'}" style="max-width: 70%;">
                    <p class="mb-1">${text}</p>
                    <small class="d-block text-end" style="font-size: 0.7rem; opacity: 0.7;">Just now</small>
                </div>
            `;

            chatBox.appendChild(div);
            scrollToBottom();
        }
    }
});
</script>
@endsection