require('../app-helpers');

$(document).ready(() => {
  if ($('.dashboard-wrapper').hasClass('admin-chatbot')) {
    $('.app-content').css('overflow', 'auto');
    $('.assigned-chatbots-table').DataTable();
    $('.unassigned-chatbots-table').DataTable();
  }

  $('.admin_bot').click(function(e) {
    e.preventDefault();
    $this = $(this);
    if ($this.hasClass('join')) {
      helpers.botAction($this, 'join', true);
    } else {
      helpers.botAction($this, 'part', true);
    }
  });
});
