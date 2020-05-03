require('./bootstrap');
require('./admin/chatbot');
require('./admin/betalist');
require('./dashboard');
require('./leaderboard');
require('./helpers');

window.csrfToken = helpers.getCsrfToken();

$(document).ready(function() {
  $('input.remember-me').on('change', function() {
    if ($(this).is(':checked')) {
      $('a.oauth-button').each(function() {
        var $link = $(this);
        $link.attr('href', $link.data('remember-href'));
      });
    } else {
      $('a.oauth-button').each(function() {
        var $link = $(this);
        $link.attr('href', $link.data('href'));
      });
    }
  });

  $('.logout-link').on('click', function(e) {
    e.preventDefault();
    $('#logout-form').submit();
  });
});
