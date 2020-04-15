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
      $('.leaderboard')
        .removeClass('leaderboard-theme_dark')
        .addClass('leaderboard-theme_light');
      $('.leaderboard').show(1);
    } else if (e.target.value === 'dark') {
      theme = e.target.value;
      $('.leaderboard').hide();
      $('.leaderboard')
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
  if ($('.leaderboard') && location.pathname.includes('/leaderboard/')) {
    let channel = location.pathname.replace('/leaderboard/', '');
    let leaderboard;
    fetch(`/referrals/${channel}`)
    .then(res => res.json())
    .then(data => {
      leaderboard = data;
      });
    setInterval(() => {
      fetch(`/referrals/${channel}`)
      .then(res => res.json())
      .then(data => {
        if (JSON.stringify(data) !== JSON.stringify(leaderboard)) {
          $('.leaderboard__row').each(function (index, row) { 
            if (index > 0) {
              $(row).hide()
              $(row).find('div:eq(0)').text(data.referrals[index-1].referrer);
              $(row).find('div:eq(1)').text(data.referrals[index-1].count);
              $(row).show('fast');
            }
          });
          leaderboard = data;
        }
      })
    }, 5000);
  }
  $('#embed-copy').click(function (e) { 
    e.preventDefault();
    $('#embed-link').removeAttr('disabled');
    $('#embed-link').select();
    document.execCommand("copy");
    $('#embed-link').attr('disabled', 'disabled');
    $('#embed-info').after('<div id="embed-alert"></div>');
    let alert = $('#embed-alert').hide();
    alert.addClass("alert alert-success text-center").text('Link copied to clipboard').slideDown('fast');
    setTimeout(function () {
      alert.slideUp('fast', function () {
        alert.remove();
      });
    }, 4000);
  });
});
