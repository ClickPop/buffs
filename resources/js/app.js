require('./bootstrap');

function updateTheme($leaderboard, theme) {
  let $wrapper = $leaderboard.parents('.leaderboard-wrapper');
  $leaderboard.hide();
  $wrapper.removeClass (function (index, className) {
    return (className.match (/\btheme-\S+/g) || []).join(' ');
  }).addClass(`theme-${theme}`);
  $leaderboard.show(1);
}

$(document).ready(function() {
  let alert_timeout;
  let $leaderboard = $('.leaderboard');

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

  $('#bot-action-button').click(function (e) {
    e.preventDefault();
    if ($(this).text() === 'Part') {
      fetch('/chatbot/part')
      .then(res => res.json())
      .then(data => {
        $(this).removeClass('btn-danger').addClass('btn-primary').text('Join');
        $('#bot-action-statement').text('The bot isn\'t in your channel yet.');
      });
    } else if ($(this).text() === 'Join') {
      fetch('/chatbot/join')
      .then(res => res.json())
      .then(data => {
        $(this).removeClass('btn-primary').addClass('btn-danger').text('Part');
        $('#bot-action-statement').text('The bot is in your channel.');
      });
    }
  });

  $('#leaderboard-reset').change(function (e) {
    e.preventDefault();
    if (this.checked) {
      $('#leaderboard-reset-label').addClass('active');
      $('#leaderboard-reset-confirm-alert').slideDown('fast');
    } else {
      $('#leaderboard-reset-label').removeClass('active');
      $('#leaderboard-reset-confirm-alert').slideUp('fast');
    }
  });

  if ($leaderboard.length > 0) {
    let isPreview = $leaderboard.parents('.leaderboard-wrapper').hasClass('preview') ? true : false;

    $('#theme-selector').change(function(e) {
      e.preventDefault();
      let $this = $(this);
      let theme = $this.val();
      updateTheme($leaderboard, theme);
    });

    if ($leaderboard && location.pathname.includes('/embed/leaderboard/')) {
      let channel = location.pathname.replace('/embed/leaderboard/', '');
      let leaderboard;
      let referralsURL = `/referrals/${channel}${isPreview ? '/preview' : ''}`;

      fetch(referralsURL)
      .then(res => res.json())
      .then(data => {
        leaderboard = data;
        });
      setInterval(() => {
        fetch(referralsURL)
        .then(res => res.json())
        .then(data => {
          if (JSON.stringify(data) !== JSON.stringify(leaderboard)) {
            if (typeof data.leaderboard.theme === "string" && data.leaderboard.theme.length > 0) {
              updateTheme($leaderboard, data.leaderboard.theme);
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
  }
});
