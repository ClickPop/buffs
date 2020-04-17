<div class="btn-group btn-group-toggle">
  <button id="bot-part-button" class="btn btn-lg btn-danger {{ $chatbot->message->bot->joined ? '' : 'disabled' }}" {{ $chatbot->message->bot->joined ? '' : 'disabled' }}>
    Part
  </button>
  <button id="bot-join-button" class="btn btn-lg btn-info {{ $chatbot->message->bot->joined ? 'disabled' : '' }}" {{ $chatbot->message->bot->joined ? 'disabled' : '' }}>
    Join
  </button>
</div>