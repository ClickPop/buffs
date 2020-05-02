require('../app');

$(document).ready(() => {
  if ($('.dashboard-wrapper').hasClass('admin-chatbot')) {
    $('.app-content').css('overflow', 'auto');
    $('.assigned-chatbots-table').DataTable();
    $('.unassigned-chatbots-table').DataTable();
  }

  $('.admin_bot').click(function(e) {
    e.preventDefault();
    $this = $(this);
    console.log($this.html());
    if ($this.hasClass('join')) {
      helpers.adminBotAction($this, 'join');
    } else {
      helpers.adminBotAction($this, 'part');
    }
  });
});
