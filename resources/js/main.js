require('./includes/bootstrap');
require('./includes/app-helpers');
require('./includes/admin/chatbot');
require('./includes/admin/betalist');
require('./includes/dashboard');
require('./includes/leaderboard');

window.csrfToken = app.getCsrfToken();

$(document).ready(() => {
  $('input.remember-me').on('change', () => {
    if ($(this).is(':checked')) {
      $('a.oauth-button').each(() => {
        var $link = $(this);
        $link.attr('href', $link.data('remember-href'));
      });
    } else {
      $('a.oauth-button').each(() => {
        var $link = $(this);
        $link.attr('href', $link.data('href'));
      });
    }
  });

  $('.logout-link').on('click', (e) => {
    e.preventDefault();
    $('#logout-form').submit();
  });
});
