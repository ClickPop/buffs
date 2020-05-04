require('./helpers');
$(document).ready(function() {
  let $leaderboard = $('.leaderboard');
  let initialSettings = helpers.getSettingsObject();
  let currentSettings = initialSettings;
  // Stop modal video when modal closes
  $('#obsTutorial').on('hidden.bs.modal', function() {
    $('#obsTutorial iframe').attr('src', $('#obsTutorial iframe').attr('src'));
  });

  // Handles the bot action on the dashboard
  $('#bot-action-button').click(function(e) {
    e.preventDefault();
    $this = $(this);
    if ($this.hasClass('join')) {
      helpers.botAction($this, 'join', false);
    } else {
      helpers.botAction($this, 'part', false);
    }
  });

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
      if (JSON.stringify(currentSettings) !== JSON.stringify(initialSettings)) {
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
              helpers.setLeaderboardData(data);
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
                    index <= helpers.getLeaderboardData().referrals.length &&
                    helpers.getLeaderboardData().referrals.length > 0
                  ) {
                    $(row).hide();
                    $(row)
                      .find('div:eq(0)')
                      .text(
                        helpers.getLeaderboardData().referrals[index - 1]
                          .referrer
                      );
                    $(row)
                      .find('div:eq(1)')
                      .text(
                        helpers.getLeaderboardData().referrals[index - 1].count
                      );
                    $(row).show('fast');
                  } else if (
                    index <= initialSettings['leaderboard-length-slider'] &&
                    helpers.getLeaderboardData().referrals.length === 0
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
});
