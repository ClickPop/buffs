$(document).ready(function () {
  if ($('.dashboard-wrapper').hasClass('admin-chatbot')) {
    $('.app-content').css('overflow', 'auto');
    $('.assigned-chatbots-table').DataTable();
    $('.unassigned-chatbots-table').DataTable();
  }

  $('.admin_bot').click(function (e) {
    e.preventDefault();
    let $this = $(this);
    if ($this.hasClass('join')) {
      app.botAction($this, 'join', true);
    } else {
      app.botAction($this, 'part', true);
    }
  });
});
