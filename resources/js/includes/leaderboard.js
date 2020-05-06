$(document).ready(function () {
  let $leaderboard = $('.leaderboard');
  if ($leaderboard.length) {
    let referralsURL = `/referrals/${channel}`;
    fetch(referralsURL)
      .then((res) => res.json())
      .then((data) => {
        app.setLeaderboardData(data);
      });
    setInterval(() => {
      fetch(referralsURL)
        .then((res) => res.json())
        .then((data) => {
          if (
            JSON.stringify(data.referrals) !==
              JSON.stringify(app.leaderboard.referrals) ||
            (route !== 'dashboard' &&
              JSON.stringify(data) !==
                JSON.stringify(app.leaderboard))
          ) {
            if (
              typeof data.leaderboard.theme === 'string' &&
              data.leaderboard.theme.length > 0 &&
              data.leaderboard.theme !== $('#theme-selector').val()
            ) {
              app.updateTheme($leaderboard, data.leaderboard.theme);
            }
            $('.leaderboard__row').each(function (index, row) {
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

            app.setLeaderboardData(data);
          }
        });
    }, 5000);
  }
});
