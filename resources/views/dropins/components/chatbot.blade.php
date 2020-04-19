<p id="bot-action-statement">
  {{ $chatbot->message->bot->joined ? 'The bot is in your channel' : 'The bot isn\'t in your channel yet' }}
</p>
<button id="bot-action-button" class="btn {{ $chatbot->message->bot->joined ? 'btn-danger' : 'btn-primary' }}">{{ $chatbot->message->bot->joined ? 'Part' : 'Join' }}</button>
