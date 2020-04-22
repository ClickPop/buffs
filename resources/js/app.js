require('./bootstrap');

function getSettingsObject() {
  let temp_theme = $('#theme-selector').val();
  let temp_length = $('#leaderboard-length-slider').val();
  let temp_settings = {
    'theme-selector': temp_theme,
    'leaderboard-length-slider': temp_length,
  };

  return temp_settings;
}

function waitingButton($this, text = 'Saving...') {
  let html = `<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
  ${text}`;
  $this.prop('disabled', true);
  $this.html(html);
}
function revertButton($this, original) {
  $this
    .html(original)
    .prop('disabled', false)
    .removeProp('disabled');
}

function updateTheme($leaderboard, theme) {
  let $wrapper = $leaderboard.parents('.leaderboard-wrapper');
  $leaderboard.hide();
  $wrapper
    .removeClass(function(index, className) {
      return (className.match(/\btheme-\S+/g) || []).join(' ');
    })
    .addClass(`theme-${theme}`);
  $leaderboard.show(1);
}

$(document).ready(function() {
  let alert_timeout, button_timeout;
  let $leaderboard = $('.leaderboard');
  let initial_settings = getSettingsObject();
  let settings = initial_settings;

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

  $('#bot-action-button').click(function(e) {
    e.preventDefault();
    $button = $(this);
    $label = $('#bot-action-statement');
    if ($button.text() === 'Part') {
      waitingButton($button, 'Parting...');
      $label.fadeTo('fast', 0);
      fetch('/chatbot/part')
        .then((res) => res.json())
        .then((data) => {
          $button.removeClass('btn-danger').addClass('btn-primary');
          revertButton($button, 'Join');
          $label.fadeTo('fast', 1).text("The bot isn't in your channel yet.");
        });
    } else if ($button.text() === 'Join') {
      waitingButton($button, 'Joining...');
      $label.fadeTo('fast', 0);
      fetch('/chatbot/join')
        .then((res) => res.json())
        .then((data) => {
          $button.removeClass('btn-primary').addClass('btn-danger');
          revertButton($button, 'Part');
          $label.fadeTo('fast', 1).text('The bot is in your channel.');
        });
    }
  });

  $('#leaderboard-reset').click(function(e) {
    e.preventDefault();
    let $button = $(this);
    let original_button_content = $button.html();
    let referralsURL = `/referrals`;

    waitingButton($button, 'Resetting...');
    fetch('/leaderboards/reset')
      .then((res) => res.json())
      .then((data) => {
        if (data.status === 'success') {
          fetch(referralsURL)
            .then((res) => res.json())
            .then((data) => {
              $('.leaderboard__row').each(function(index, row) {
                if (index > 0) {
                  if (
                    index <= data.leaderboard.length &&
                    data.referrals.length > 0
                  ) {
                    $(row).hide();
                    $(row)
                      .find('div:eq(0)')
                      .text(data.referrals[index - 1].referrer);
                    $(row)
                      .find('div:eq(1)')
                      .text(data.referrals[index - 1].count);
                    $(row).show('fast');
                  } else if (
                    data.referrals.length === 0 &&
                    route === 'dashboard'
                  ) {
                    $(row).hide();
                    $(row)
                      .find('div:eq(0)')
                      .text(wizards[index - 1].referrer);
                    $(row)
                      .find('div:eq(1)')
                      .text(wizards[index - 1].count);
                    $(row).show('fast');
                  } else {
                    $(row).hide();
                  }
                }
              });
              leaderboard = data;
              setTimeout(() => {
                revertButton($button, original_button_content);
                $('#resetReferrals').modal('hide');
              }, 500);
            });
        }
      });
  });

  $('#settings-submit').click(function(e) {
    let csrf_token = document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute('content');
    let $button = $(this);
    let original_button_content = $button.html();
    waitingButton($button, 'Saving...');
    fetch('/', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrf_token,
      },
      body: JSON.stringify(settings),
    })
      .then((res) => res.json())
      .then((data) => {
        let $alert = $('#leaderboard-alert');

        initial_settings = settings;
        $alert
          .addClass('alert alert-success text-center')
          .text('Settings Saved')
          .slideDown('fast', function() {
            setTimeout(() => {
              revertButton($button, original_button_content);
              $('#theme-selector').trigger('change');
            }, 500);
            alert_timeout = setTimeout(() => {
              $alert.slideUp('fast');
            }, 3000);
          });
      });
  });

  if ($leaderboard.length > 0) {
    let isPreview = $leaderboard
      .parents('.leaderboard-wrapper')
      .hasClass('preview')
      ? true
      : false;

    $('#theme-selector').change(function(e) {
      e.preventDefault();
      let $this = $(this);
      let theme = $this.val();
      settings = getSettingsObject();
      if (JSON.stringify(settings) !== JSON.stringify(initial_settings)) {
        $('#settings-submit')
          .prop('disabled', false)
          .removeProp('disabled');
      } else {
        $('#settings-submit').prop('disabled', true);
      }
      updateTheme($leaderboard, theme);
    });

    if ($leaderboard && location.pathname.includes('/embed')) {
      wizards = JSON.parse(wizards);
      let leaderboard;
      let referralsURL = `/referrals/${channel}`;
      fetch(referralsURL)
        .then((res) => res.json())
        .then((data) => {
          leaderboard = data;
        });
      setInterval(() => {
        fetch(referralsURL)
          .then((res) => res.json())
          .then((data) => {
            if (
              JSON.stringify(data.referrals) !==
                JSON.stringify(leaderboard.referrals) ||
              (route !== 'dashboard' &&
                JSON.stringify(data) !== JSON.stringify(leaderboard))
            ) {
              if (
                typeof data.leaderboard.theme === 'string' &&
                data.leaderboard.theme.length > 0 &&
                data.leaderboard.theme !== $('#theme-selector').val()
              ) {
                updateTheme($leaderboard, data.leaderboard.theme);
              }
              $('.leaderboard__row').each(function(index, row) {
                if (index > 0) {
                  if (
                    index < data.referrals.length &&
                    data.referrals.length > 0
                  ) {
                    $(row).hide();
                    $(row)
                      .find('div:eq(0)')
                      .text(data.referrals[index - 1].referrer);
                    $(row)
                      .find('div:eq(1)')
                      .text(data.referrals[index - 1].count);
                    $(row).show('fast');
                  } else if (index < data.length) {
                  } else if (route !== 'dashboard') {
                    $(row).hide();
                  } else if (
                    route === 'dashboard' &&
                    data.referrals.length < data.leaderboard.length &&
                    index >= data.referrals.length
                  ) {
                    $(row).hide();
                    $(row).show('fast');
                  }
                }
              });

              if (
                leaderboard.referrals.length === 0 &&
                data.referrals.length > 0 &&
                $('.leaderboard__row').length === 1
              ) {
                data.referrals.forEach((referral) => {
                  let row = `
                  <div class="leaderboard__row">
                    <div>${referral.referrer}</div>
                    <div>${referral.count}</div>
                  </div>
                  `;
                  $('.leaderboard__container').append(row);
                });
              }

              leaderboard = data;
            }
          });
      }, 5000);
    }
    $('#embed-copy').click(function(e) {
      e.preventDefault();
      $('#embed-link').removeAttr('disabled');
      $('#embed-link').select();
      document.execCommand('copy');
      $('#embed-link').attr('disabled', 'disabled');
      if ($('#embed-alert')) {
      }
      let $alert = $('#leaderboard-alert');
      $alert
        .addClass('alert alert-success text-center')
        .text('Link copied to clipboard')
        .slideDown('fast');
      alert_timeout = setTimeout(() => {
        $alert.slideUp('fast');
      }, 3000);
    });
    $('#leaderboard-length-slider')
      .on('input', function(e) {
        e.preventDefault();
        $('#leaderboard-length').text(e.target.value);
      })
      .on('change blur', function(e) {
        settings = getSettingsObject();
        if (JSON.stringify(settings) !== JSON.stringify(initial_settings)) {
          $('#settings-submit').removeAttr('disabled');
        } else {
          $('#settings-submit').attr('disabled', 'disabled');
        }
        $('.leaderboard__row').each((index, row) => {
          $(row).hide();
        });
        for (let i = 0; i <= e.target.value; i++) {
          $(`.leaderboard__row:eq(${i})`).show();
        }
      });
  }
});
