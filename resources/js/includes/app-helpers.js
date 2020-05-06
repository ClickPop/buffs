class AppHelpers {
  constructor() {
    this.leaderboard = null;
    this.alertTimeout = null;
  }

  copyToClipboard($copy_data) {
    $copy_data.removeAttr('disabled');
    $copy_data.select();
    document.execCommand('copy');
    $copy_data.attr('disabled', 'disabled');
  }
  displayAlert($alert, type, text, duration = 3, func = () => {}) {
    if (!$alert.hasClass(`alert-${type}`)) {
      $alert.removeClass().addClass(`alert alert-${type} text-center`);
    }
    $alert.text(text).slideDown('fast', func);
    this.alertTimeout = setTimeout(() => {
      $alert.slideUp('fast');
    }, duration * 1000);
  }
  betalistAction($button, action) {
    let buttonText = $button.text();
    let email = $button
      .parents('.betalist')
      .find('td:eq(0)')
      .text();
    let username = $button
      .parents('.betalist')
      .find('td:eq(1)')
      .text();
    let id = $button.parents('.betalist').data('twitch-id');
    this.waitingButton($button, 'Processing..');
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
      }),
    })
      .then((res) => res.json())
      .then((data) => {
        $button.hide('fast');
        this.revertButton($button, buttonText);
        if (action === 'approve') {
          if ($button.siblings('.betalist_deny').css('display') === 'none') {
            $button.siblings('.betalist_deny').show('fast');
          }
          this.changeBadge($button, 'success', 'Approved');
        } else {
          if ($button.siblings('.betalist_approve').css('display') === 'none') {
            $button.siblings('.betalist_approve').show('fast');
          }
          this.changeBadge($button, 'danger', 'Denied');
        }
      });
  }
  botAction($button, action, admin) {
    let join = action === 'join';
    let buttonClass = `
    btn btn-${join ? 'danger part' : 'primary join'}
    `;
    let buttonText = join ? 'Part' : 'Join';
    let badgeType = join ? 'success' : 'warning';
    let badgeText = join ? 'Joined' : 'Parted';
    let isAdmin = admin ? `admin/${action}` : action;
    let url = `/chatbot/${isAdmin}`;
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
      ? this.waitingButton($button, 'Joining...')
      : this.waitingButton($button, 'Parting...');
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
          this.revertButton($button, buttonText);
          if (!admin) {
            this.changeLabel($label, labelText, false);
          } else {
            this.changeBadge($button, badgeType, badgeText);
          }
        } else {
          this.revertButton($button, !join ? 'Part' : 'Join');
          if (!admin) {
            this.changeLabel(
              $label,
              'An error occured, please try again later',
              true
            );
          } else {
            this.changeBadge($button, 'danger', 'Error, please check console');
          }
        }
      })
      .catch((err) => {
        this.revertButton($button, !join ? 'Part' : 'Join');
        if (!admin) {
          this.changeLabel(
            $label,
            'An error occured, please try again later',
            true
          );
        } else {
          _this.changeBadge($button, 'danger', 'Error, please check console');
        }
        console.error(err);
      });
  }
  changeLabel($label, text, error) {
    let color = error ? 'rgb(194, 0, 0)' : 'rgb(11, 13, 19)';
    $label
      .fadeTo('fast', 1)
      .text(text)
      .css('color', color);
  }
  changeBadge($row, badge, text) {
    $row
      .parents('tr')
      .find('td:eq(2)')
      .find('span')
      .removeClass()
      .addClass(`badge badge-${badge}`)
      .text(text);
  }
  getSettingsObject() {
    let tempTheme = $('#theme-selector').val();
    let tempLength = $('#leaderboard-length-slider').val();
    let tempSettings = {
      'theme-selector': tempTheme,
      'leaderboard-length-slider': tempLength,
    };

    return tempSettings;
  }
  waitingButton($this, text = 'Saving...') {
    $this.prop('disabled', true);
    $this.html(`<span class="spinner-grow spinner-grow-sm" 
      role="status"
      aria-hidden="true"></span>
      ${text}`);
  }
  revertButton($this, original) {
    $this
      .html(original)
      .prop('disabled', false)
      .removeProp('disabled');
  }
  updateTheme($leaderboard, theme) {
    $leaderboard.hide();
    $leaderboard
      .parents('.leaderboard-wrapper')
      .removeClass((index, className) => {
        return (className.match(/\btheme-\S+/g) || []).join(' ');
      })
      .addClass(`theme-${theme}`);
    $leaderboard.show(1);
  }
  getCsrfToken() {
    return document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute('content');
  }
  setLeaderboardData(data) {
    this.leaderboard = data;
  }
}

window.app = new AppHelpers();
