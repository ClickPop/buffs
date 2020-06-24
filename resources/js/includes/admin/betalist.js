$(document).ready(function () {
  if ($('.dashboard-wrapper').hasClass('admin-betalist')) {
    $('.app-content').css('overflow', 'auto');
    $('#betalist-table').DataTable();
  }

  $('#api_key_copy').click(function(e) {
    e.preventDefault();
    app.copyToClipboard($('#api_key'));
    app.displayAlert(
      $('#api_key_copy_alert'),
      'success',
      'API key copied',
      3
    );
  });

  $('.betalist_approve').click(function(e) {
    e.preventDefault();
    let $this = $(this);
    app.betalistAction($this, 'approve');
  });

  $('.betalist_deny').click(function(e) {
    e.preventDefault();
    let $this = $(this);
    app.betalistAction($this, 'deny');
  });
});
