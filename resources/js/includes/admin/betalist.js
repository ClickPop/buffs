$(document).ready(function () {
  if ($('.dashboard-wrapper').hasClass('admin-betalist')) {
    $('.app-content').css('overflow', 'auto');
    $('#betalist-table').DataTable();
  }

  $('#api_key_copy').click(function (e) {
    e.preventDefault();
    app.copyToClipboard($('#api_key'));
    app.displayAlert(
      $('#api_key_copy_alert'),
      'success',
      'API key copied',
      3
    );
  });

  $('#betalist-table').on("click", '.betalist_approve', function (e) {
    e.preventDefault();
    let $this = $(this);
    app.betalistAction($this, 'approve');
  });

  $('#betalist-table').on("click", '.betalist_deny', function (e) {
    e.preventDefault();
    let $this = $(this);
    app.betalistAction($this, 'deny');
  });
});
