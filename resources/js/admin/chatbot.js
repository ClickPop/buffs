$(document).ready(() => {
  if ($('.dashboard-wrapper').hasClass('admin-chatbot')) {
    $('.app-content').css('overflow', 'auto');
    $('.assigned-chatbots-table').DataTable();
    $('.unassigned-chatbots-table').DataTable();
  }
});