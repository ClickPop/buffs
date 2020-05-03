require('../helpers');

$(document).ready(() => {
  if ($('.dashboard-wrapper').hasClass('admin-betalist')) {
    $('.app-content').css('overflow', 'auto');
    $('#betalist-table').DataTable();
  }

  $('#api_key_copy').click(function(e) {
    e.preventDefault();
    helpers.copy($('#api_key'));
    helpers.displayAlert(
      $('#api_key_copy_alert'),
      'success',
      'API key copied',
      3
    );
  });

  $('.betalist_approve').click(function(e) {
    e.preventDefault();
    $this = $(this);
    helpers.betalistAction($this, 'approve');
  });

  $('.betalist_deny').click(function(e) {
    e.preventDefault();
    $this = $(this);
    helpers.betalistAction($this, 'deny');
  });
});
