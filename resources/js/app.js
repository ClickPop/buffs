require('./bootstrap');

$(document).ready(function() {
  let theme;
  let alert_timeout;
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
  // $('#theme-submit').click(function (e) { 
  //   e.preventDefault();
  //   fetch(`/leaderboard/theme/${theme}`)
  //     .then(res => res.json())
  //     .then(data => {
  //       let $alert = $('#leaderboard-alert');
  //       $alert.text(`Theme changed to ${data[0].theme}`).slideDown('fast');
  //       alert_timeout = setTimeout(() => {$alert.slideUp('fast');}, 4000);
  //     })
  // });
  if ($('.leaderboard') && location.pathname.includes('/embed/leaderboard/')) {
    let channel = location.pathname.replace('/embed/leaderboard/', '');
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
          if ($('.leaderboard').hasClass('leaderboard-theme_dark') && data.leaderboard.theme === 'light') {
            $('.leaderboard').hide();
            $('.leaderboard')
              .removeClass('leaderboard-theme_dark')
              .addClass('leaderboard-theme_light');
            $('.leaderboard').show(1);
          } else if ($('.leaderboard').hasClass('leaderboard-theme_light') && data.leaderboard.theme === 'dark') {
            $('.leaderboard').hide();
            $('.leaderboard')
              .removeClass('leaderboard-theme_light')
              .addClass('leaderboard-theme_dark');
            $('.leaderboard').show(1);
          }
          $('.leaderboard__row').each(function (index, row) { 
            if (index > 0) {
              if (index <= data.leaderboard.length) {
                $(row).hide()
                $(row).find('div:eq(0)').text(data.referrals[index-1].referrer);
                $(row).find('div:eq(1)').text(data.referrals[index-1].count);
                $(row).show('fast');
              } else {
                $(row).hide();
              }
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
    if ($('#embed-alert')) {

    }
    let $alert = $('#leaderboard-alert');
    $alert.addClass("alert alert-success text-center").text('Link copied to clipboard').slideDown('fast');
    alert_timeout = setTimeout(() => {$alert.slideUp('fast');}, 4000);
  });
  $('#leaderboard-length-slider').on('input', function (e) { 
    e.preventDefault();
    $('#leaderboard-length').text(e.target.value);
  });
  $('#leaderboard-length-slider').change(function (e) { 
    $('.leaderboard__row').each((index, row) => { 
         $(row).hide();
    });
    for (let i = 0; i <= e.target.value; i++) {
      $(`.leaderboard__row:eq(${i})`).show();
    }
  });
});
