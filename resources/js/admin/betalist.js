$(document).ready(() => {
  if ($('.dashboard-wrapper').hasClass('admin-betalist')) {
    $('.app-content').css('overflow', 'auto');
    $('#betalist-table').DataTable();
  }
});