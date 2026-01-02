@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            Chat with <strong>{{ $otherUser->username }}</strong>
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
            <form id="chat-form" action="{{ route('user.chat.store', ['userId' => $otherUser->user_id]) }}" method="POST">
                @csrf
                <div class="input-group">
                    <input type="text" id="message-input" class="form-control" placeholder="Type a message..." value="{{ $autoMessage }}" autocomplete="off">
                    <button type="submit" id="send-button" class="btn btn-primary">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Wait for Echo to be available
    const waitForEcho = setInterval(() => {
        if (window.Echo) {
            clearInterval(waitForEcho);
            initChat();
        }
    }, 100);

    function initChat() {
        const myId = {{ auth()->user()->user_id }};
        const partnerId = {{ $otherUser->user_id }}; // The person you are chatting with
        const messageInput = document.getElementById('message-input');
        const sendButton = document.getElementById('send-button');
        const chatBox = document.getElementById('chat-box');
        const form = document.getElementById('chat-form');
        const input = document.getElementById('message-input');

        function scrollToBottom() {
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function sendButtonHandler() {
            sendButton.disabled = !messageInput.value.trim();
        }

        sendButtonHandler();

        messageInput.addEventListener('input', sendButtonHandler);

        scrollToBottom();

        const smallerId = Math.min(myId, partnerId);
        const largerId  = Math.max(myId, partnerId);
        const channelName = `chat.${smallerId}.${largerId}`;

        console.log("Joining channel:", channelName); // Debugging


        window.Echo.private(channelName)
            .listen('MessageSent', (e) => {
                console.log("Event received:", e);

                // Only append if the message is NOT from me
                // (Because I already appended my own message when I clicked send)
                if (e.message.sender_id !== myId) {
                    appendMessage(e.message.content, 'received');
                }
            });

        // SENDER (Sending Messages)
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Stop page reload

            const message = input.value;
            if (!message) return; // Don't send empty strings

            // Optimistic UI: Add message to screen IMMEDIATELY (before server replies)
            appendMessage(message, 'sent');
            input.value = ''; // Clear input
            sendButton.disabled = true;

            // Send to Server
            axios.post(`/user/chat/${partnerId}`, {
                content: message
            })
            .then(response => {
                console.log("Message saved!");
            })
            .catch(error => {
                console.error("Error sending message:", error);
                alert("Failed to send message.");
            });
        });

        // UI HELPER: Appends HTML to the box
        function appendMessage(text, type) {
            const div = document.createElement('div');
            const isSent = type === 'sent';

            // Add CSS classes for alignment and color
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
