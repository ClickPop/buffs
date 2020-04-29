require('./bootstrap');
require('./admin/chatbot');
require('./admin/betalist');

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
  let leaderboard;
  let alert_timeout, button_timeout;
  let $leaderboard = $('.leaderboard');
  let initial_settings = getSettingsObject();
  let settings = initial_settings;

  $('#api_key_copy').click(function(e) {
    e.preventDefault();
    $('#api_key').removeAttr('disabled');
    $('#api_key').select();
    document.execCommand('copy');
    $('#api_key').attr('disabled', 'disabled');
    let $alert = $('#api_key_copy_alert');
    $alert.slideDown('fast');
    alert_timeout = setTimeout(() => {
      $alert.slideUp('fast');
    }, 3000);
  });

  $('.betalist_approve').click(function(e) {
    e.preventDefault();
    let csrf_token = document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute('content');
    $this = $(this);
    let email = $this
      .parents('.betalist')
      .find('td:eq(0)')
      .text();
    let username = $this
      .parents('.betalist')
      .find('td:eq(1)')
      .text();
    fetch(`betalist/addorupdate`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrf_token,
      },
      body: JSON.stringify({
        action: 'approve',
        email,
        username,
        tags: ['beta_enrolled'],
      }),
    })
      .then((res) => res.json())
      .then((data) => {
        $this.hide('fast');
        if ($this.siblings('.betalist_deny').css('display') === 'none') {
          $this.siblings('.betalist_deny').show('fast');
        }
        $this
          .parents('.betalist')
          .find('td:eq(2)')
          .text('approved');
      });
  });

  $('.betalist_deny').click(function(e) {
    e.preventDefault();
    let csrf_token = document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute('content');
    $this = $(this);
    let email = $this
      .parents('.betalist')
      .find('td:eq(0)')
      .text();
    let username = $this
      .parents('.betalist')
      .find('td:eq(1)')
      .text();
    let id = $this.parents('.betalist').attr('id');
    fetch(`betalist/addorupdate`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrf_token,
      },
      body: JSON.stringify({
        action: 'deny',
        email,
        username,
        id,
      }),
    })
      .then((res) => res.json())
      .then((data) => {
        $this.hide('fast');
        if ($this.siblings('.betalist_approve').css('display') === 'none') {
          $this.siblings('.betalist_approve').show('fast');
        }
        $this
          .parents('.betalist')
          .find('td:eq(2)')
          .text('denied');
      });
  });

  $('.admin_bot').click(function(e) {
    e.preventDefault();
    let csrf_token = document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute('content');
    $this = $(this);
    if ($this.hasClass('join')) {
      fetch('/chatbot/admin/join', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrf_token,
        },
        body: JSON.stringify({
          twitch_userId: $this.parents('tr').attr('twitch_id'),
        }),
      })
        .then((res) => res.json())
        .then((data) => {
          $this.prop('disabled', true);
          $this
            .siblings('.part')
            .prop('disabled', false)
            .removeProp('disabled');
          $this
            .parents('tr')
            .find('td:eq(2)')
            .find('span')
            .removeClass('badge-warning')
            .addClass('badge-success')
            .text('Joined');
        });
    } else {
      fetch('/chatbot/admin/part', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrf_token,
        },
        body: JSON.stringify({
          twitch_userId: $this.parents('tr').attr('twitch_id'),
        }),
      })
        .then((res) => res.json())
        .then((data) => {
          $this.prop('disabled', true);
          $this
            .siblings('.join')
            .prop('disabled', false)
            .removeProp('disabled');
          $this
            .parents('tr')
            .find('td:eq(2)')
            .find('span')
            .removeClass('badge-success')
            .addClass('badge-warning')
            .text('Parted');
        });
    }
  });

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
          if (data.status_code === 200 && data.message.data.joined === false) {
            $button.removeClass('btn-danger').addClass('btn-primary');
            revertButton($button, 'Join');
            $label
              .fadeTo('fast', 1)
              .text("The bot isn't in your channel yet.")
              .css('color', 'rgb(11, 13, 19)');
          }
        })
        .catch((err) => {
          $label
            .fadeTo('fast', 1)
            .text('An error occurred, please try again.')
            .css('color', 'rgb(194, 0, 0)');
          revertButton($button, 'Part');
          console.error(err);
        });
    } else if ($button.text() === 'Join') {
      waitingButton($button, 'Joining...');
      $label.fadeTo('fast', 0);
      fetch('/chatbot/join')
        .then((res) => res.json())
        .then((data) => {
          if (data.status_code === 200 && data.message.data.joined === true) {
            $button.removeClass('btn-primary').addClass('btn-danger');
            revertButton($button, 'Part');
            $label
              .fadeTo('fast', 1)
              .text('The bot is in your channel.')
              .css('color', 'rgb(11, 13, 19)');
          }
        })
        .catch((err) => {
          $label
            .fadeTo('fast', 1)
            .text('An error occurred, please try again.')
            .css('color', 'rgb(194, 0, 0)');
          revertButton($button, 'Join');
          console.error(err);
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
                    index <= data.referrals.length &&
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
                    index <= $('#leaderboard-length-slider').val() &&
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
              $('.leaderboard__row').each(function(index, row) {
                if (index > 0) {
                  if (
                    index <= initial_settings['leaderboard-length-slider'] &&
                    index <= leaderboard.referrals.length &&
                    leaderboard.referrals.length > 0
                  ) {
                    $(row).hide();
                    $(row)
                      .find('div:eq(0)')
                      .text(leaderboard.referrals[index - 1].referrer);
                    $(row)
                      .find('div:eq(1)')
                      .text(leaderboard.referrals[index - 1].count);
                    $(row).show('fast');
                  } else if (
                    index <= initial_settings['leaderboard-length-slider'] &&
                    leaderboard.referrals.length === 0
                  ) {
                    $(row).hide();
                    $(row).show('fast');
                  } else {
                    $(row).hide();
                  }
                }
              });
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

    if ($leaderboard) {
      wizards = JSON.parse(wizards);
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
                    index <= data.leaderboard.length &&
                    index <= data.referrals.length &&
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
                  } else {
                    $(row).hide();
                  }
                }
              });

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
      .on('change', function(e) {
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

  // Stop modal video when modal closes
  $('#obsTutorial').on('hidden.bs.modal', function() {
    $('#obsTutorial iframe').attr('src', $('#obsTutorial iframe').attr('src'));
  });
});
