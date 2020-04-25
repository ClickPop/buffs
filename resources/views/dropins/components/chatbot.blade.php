<p id="bot-action-statement">
  {{ $chatbot->message->data->joined ? 'The bot is in your channel' : 'The bot isn\'t in your channel yet' }}
</p>
<button id="bot-action-button" class="btn {{ $chatbot->message->data->joined ? 'btn-danger' : 'btn-primary' }}">{{ $chatbot->message->data->joined ? 'Part' : 'Join' }}</button>
