$(document).ready(() => {
  let $leaderboard = $('.leaderboard');
  let initialSettings = app.getSettingsObject();
  let currentSettings = initialSettings;
  // Stop modal video when modal closes
  $('#obsTutorial').on('hidden.bs.modal', () => {
    $('#obsTutorial iframe').attr('src', $('#obsTutorial iframe').attr('src'));
  });

  // Handles the bot action on the dashboard
  $('#bot-action-button').on('click', (e) => {
    e.preventDefault();
    let $this = $(this);
    if ($this.hasClass('join')) {
      app.botAction($this, 'join', false);
    } else {
      app.botAction($this, 'part', false);
    }
  });

  $('#embed-copy').on('click', (e) => {
    e.preventDefault();
    app.copy($('#embed-link'));
    app.displayAlert(
      $('#leaderboard-alert'),
      'success',
      'Link copied to clipboard',
      3
    );
  });

  $('#leaderboard-length-slider')
    .on('input', (e) => {
      e.preventDefault();
      $('#leaderboard-length').text(e.target.value);
    })
    .on('change', (e) => {
      currentSettings = app.getSettingsObject();
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

  $('#theme-selector').on('change', (e) => {
    e.preventDefault();
    let $this = $(this);
    let theme = $this.val();
    currentSettings = app.getSettingsObject();
    if (JSON.stringify(currentSettings) !== JSON.stringify(initialSettings)) {
      $('#settings-submit')
        .prop('disabled', false)
        .removeProp('disabled');
    } else {
      $('#settings-submit').prop('disabled', true);
    }
    app.updateTheme($leaderboard, theme);
  });

  $('#leaderboard-reset').on('click', (e) => {
    e.preventDefault();
    let $button = $(this);
    let original_button_content = $button.html();
    let referralsURL = `/referrals`;

    app.waitingButton($button, 'Resetting...');
    fetch('/leaderboards/reset')
      .then((res) => res.json())
      .then((data) => {
        if (data.status === 'success') {
          fetch(referralsURL)
            .then((res) => res.json())
            .then((data) => {
              $('.leaderboard__row').each((index, row) => {
                if (index > 0) {
                  if (
                    index <= data.referrals.length &&
                    data.referrals.length > 0
                  ) {
                    $(row).hide();
                    $(row)
                      .find('div:eq(0)')
                      .text(data.referrals[index - 1].referrer)
                      .next('div')
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
                      .text(wizards[index - 1])
                      .next('div')
                      .text("0");
                    $(row).show('fast');
                  } else {
                    $(row).hide();
                  }
                }
              });
              app.setLeaderboardData(data);
              setTimeout(() => {
                app.revertButton($button, original_button_content);
                $('#resetReferrals').modal('hide');
              }, 500);
            });
        }
      });
  });

  $('#settings-submit').on('click', (e) => {
    let $button = $(this);
    let original_button_content = $button.html();
    app.waitingButton($button, 'Saving...');
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
        app.displayAlert(
          $('#leaderboard-alert'),
          'success',
          'Settings saved',
          3,
          () => {
            setTimeout(() => {
              app.revertButton($button, original_button_content);
              $('#theme-selector').trigger('change');
              $('.leaderboard__row').each((index, row) => {
                if (index > 0) {
                  if (
                    index <= initialSettings['leaderboard-length-slider'] &&
                    index <= app.leaderboard.referrals.length &&
                    app.leaderboard.referrals.length > 0
                  ) {
                    $(row).hide();
                    $(row)
                      .find('div:eq(0)')
                      .text(
                        app.leaderboard.referrals[index - 1]
                          .referrer
                      );
                    $(row)
                      .find('div:eq(1)')
                      .text(
                        app.leaderboard.referrals[index - 1].count
                      );
                    $(row).show('fast');
                  } else if (
                    index <= initialSettings['leaderboard-length-slider'] &&
                    app.leaderboard.referrals.length === 0
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
