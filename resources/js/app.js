require('./bootstrap');
require('./admin/chatbot');
require('./admin/betalist');
require('./dashboard');
require('./leaderboard');

module.exports = window.helpers = {
  copy: ($copy_data) => {
    $copy_data.removeAttr('disabled');
    $copy_data.select();
    document.execCommand('copy');
    $copy_data.attr('disabled', 'disabled');
  },
  displayAlert: ($alert, type, text, duration = 3, func = () => {}) => {
    if (!$alert.hasClass(`alert-${type}`)) {
      $alert.removeClass().addClass(`alert alert-${type} text-center`);
    }
    $alert.text(text).slideDown('fast', func);
    alertTimeout = setTimeout(() => {
      $alert.slideUp('fast');
    }, duration * 1000);
  },
  betalistAction: ($button, action) => {
    let email = $button
      .parents('.betalist')
      .find('td:eq(0)')
      .text();
    let username = $button
      .parents('.betalist')
      .find('td:eq(1)')
      .text();
    let id = $button.parents('.betalist').data('twitch-id');
    fetch(`betalist/addorupdate`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
      },
      body: JSON.stringify({
        action,
        email,
        username,
        id,
        tags: action === 'approve' ? ['beta_enrolled'] : '',
      }),
    })
      .then((res) => res.json())
      .then((data) => {
        $button.hide('fast');
        if (action === 'approve') {
          if ($button.siblings('.betalist_deny').css('display') === 'none') {
            $button.siblings('.betalist_deny').show('fast');
          }
          helpers.changeBadge($button, 'success', 'Approved');
        } else {
          if ($button.siblings('.betalist_approve').css('display') === 'none') {
            $button.siblings('.betalist_approve').show('fast');
          }
          helpers.changeBadge($button, 'danger', 'Denied');
        }
      });
  },
  adminBotAction: ($button, action, admin) => {
    join = action === 'join';
    let buttonClass = `
    btn btn-${join ? 'danger part' : 'primary join'}
    `;
    let buttonText = join ? 'Part' : 'Join';
    let badgeType = join ? 'success' : 'warning';
    let badgeText = join ? 'Joined' : 'Parted';
    let url = `/chatbot/${admin ? `admin/${action}` : action}`;
    let body = {};
    body.twitch_username = admin
      ? $button
          .parents('tr')
          .find('td:eq(1)')
          .text()
          .toLowerCase()
      : undefined;
    body.twitch_userId = admin
      ? $button.parents('tr').data('twitch-id')
      : undefined;
    fetchInfo = {
      method: admin ? 'POST' : 'GET',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
      },
    };
    join
      ? helpers.waitingButton($button, 'Joining...')
      : helpers.waitingButton($button, 'Parting...');
    if (!admin) {
      var $label = $('#bot-action-statement');
      $label.fadeTo('fast', 0);
      var labelText =
        action === 'join'
          ? 'The bot is in your channel'
          : "The bot isn't in your channel yet";
    }

    fetchInfo.body = admin ? JSON.stringify(body) : undefined;
    fetch(url, fetchInfo)
      .then((res) => res.json())
      .then((data) => {
        if (data.status_code === 200) {
          $button.removeClass().addClass(buttonClass);
          helpers.revertButton($button, buttonText);
          if (!admin) {
            helpers.changeLabel($label, labelText, false);
          } else {
            helpers.changeBadge($button, badgeType, badgeText);
          }
        } else {
          helpers.revertButton($button, !join ? 'Part' : 'Join');
          if (!admin) {
            helpers.changeLabel(
              $label,
              'An error occured, please try again later',
              true
            );
          } else {
            helpers.changeBadge(
              $button,
              'danger',
              'Error, please check console'
            );
          }
        }
      })
      .catch((err) => {
        helpers.revertButton($button, !join ? 'Part' : 'Join');
        if (!admin) {
          helpers.changeLabel(
            $label,
            'An error occured, please try again later',
            true
          );
        } else {
          helpers.changeBadge($button, 'danger', 'Error, please check console');
        }
        console.error(err);
      });
  },
  changeLabel: ($label, text, error) => {
    let color = error ? 'rgb(194, 0, 0)' : 'rgb(11, 13, 19)';
    $label
      .fadeTo('fast', 1)
      .text(text)
      .css('color', color);
  },
  changeBadge: ($row, badge, text) => {
    $row
      .parents('tr')
      .find('td:eq(2)')
      .find('span')
      .removeClass()
      .addClass(`badge badge-${badge}`)
      .text(text);
  },
  getSettingsObject: () => {
    let tempTheme = $('#theme-selector').val();
    let tempLength = $('#leaderboard-length-slider').val();
    let tempSettings = {
      'theme-selector': tempTheme,
      'leaderboard-length-slider': tempLength,
    };

    return tempSettings;
  },
  waitingButton: ($this, text = 'Saving...') => {
    $this.prop('disabled', true);
    $this.html(`<span class="spinner-grow spinner-grow-sm" 
      role="status"
      aria-hidden="true"></span>
      ${text}`);
  },
  revertButton: ($this, original) => {
    $this
      .html(original)
      .prop('disabled', false)
      .removeProp('disabled');
  },
  updateTheme: ($leaderboard, theme) => {
    $leaderboard.hide();
    $leaderboard
      .parents('.leaderboard-wrapper')
      .removeClass(function(index, className) {
        return (className.match(/\btheme-\S+/g) || []).join(' ');
      })
      .addClass(`theme-${theme}`);
    $leaderboard.show(1);
  },
  getCsrfToken: () => {
    return document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute('content');
  },
};
window.csrfToken = helpers.getCsrfToken();

$(document).ready(function() {
  let leaderboard;
  let alertTimeout;
  let $leaderboard = $('.leaderboard');
  let initialSettings = helpers.getSettingsObject();
  let currentSettings = initialSettings;

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

  $('#leaderboard-reset').click(function(e) {
    e.preventDefault();
    let $button = $(this);
    let original_button_content = $button.html();
    let referralsURL = `/referrals`;

    helpers.waitingButton($button, 'Resetting...');
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
                helpers.revertButton($button, original_button_content);
                $('#resetReferrals').modal('hide');
              }, 500);
            });
        }
      });
  });

  $('#settings-submit').click(function(e) {
    let $button = $(this);
    let original_button_content = $button.html();
    helpers.waitingButton($button, 'Saving...');
    fetch('/', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
      },
      body: JSON.stringify(currentSettings),
    })
      .then((res) => res.json())
      .then((data) => {
        initialSettings = currentSettings;
        helpers.displayAlert(
          $('#leaderboard-alert'),
          'success',
          'Settings saved',
          3,
          function() {
            setTimeout(() => {
              helpers.revertButton($button, original_button_content);
              $('#theme-selector').trigger('change');
              $('.leaderboard__row').each(function(index, row) {
                if (index > 0) {
                  if (
                    index <= initialSettings['leaderboard-length-slider'] &&
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
                    index <= initialSettings['leaderboard-length-slider'] &&
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
          }
        );
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
      currentSettings = helpers.getSettingsObject();
      if (JSON.stringify(currentSettings) !== JSON.stringify(initialSettings)) {
        $('#settings-submit')
          .prop('disabled', false)
          .removeProp('disabled');
      } else {
        $('#settings-submit').prop('disabled', true);
      }
      helpers.updateTheme($leaderboard, theme);
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
                helpers.updateTheme($leaderboard, data.leaderboard.theme);
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
      helpers.copy($('#embed-link'));
      helpers.displayAlert(
        $('#leaderboard-alert'),
        'success',
        'Link copied to clipboard',
        3
      );
    });
    $('#leaderboard-length-slider')
      .on('input', function(e) {
        e.preventDefault();
        $('#leaderboard-length').text(e.target.value);
      })
      .on('change', function(e) {
        currentSettings = helpers.getSettingsObject();
        if (
          JSON.stringify(currentSettings) !== JSON.stringify(initialSettings)
        ) {
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
