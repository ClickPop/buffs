import('./bootstrap');

$(document).ready(function() {
  let theme;
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

  $('#theme-selector').change(function(e) {
    e.preventDefault();
    if (e.target.value === 'light') {
      theme = e.target.value;
      $('.leaderboard').hide();
      $('.leaderboard-wrapper')
        .removeClass('leaderboard-theme_dark')
        .addClass('leaderboard-theme_light');
      $('.leaderboard').show(1);
    } else if (e.target.value === 'dark') {
      theme = e.target.value;
      $('.leaderboard').hide();
      $('.leaderboard-wrapper')
        .removeClass('leaderboard-theme_light')
        .addClass('leaderboard-theme_dark');
      $('.leaderboard').show(1);
    }
  });
  $('#theme-submit').click(function (e) { 
    e.preventDefault();
    fetch(`/leaderboard/theme/${theme}`)
      .then(res => res.json())
      .then(data => {
        $('#theme').before('<div id="theme-alert"></div>');
        let alert = $('#theme-alert').hide();
        alert.addClass("alert alert-success text-center").text(`Theme changed to ${data[0].theme}`).slideDown('fast');
        setTimeout(() => {
          alert.slideUp('fast', () => {
            alert.remove();
          });
        }, 4000);
      })
  });
});
