<div class="form-group">
  <div class="row">
    <div class="col-12 col-xl-6">
      @include('dropins.components.leaderboard', ['preview' => true])
    </div>

    <form method="POST" class="col-12 col-xl-6">

      <div id='settings' class="card p-4">
        <h3>Leaderboard Settings</h3>
        <div id="embed-info" class="form-group xl-4">
          <p>
            Add a source in OBS or your tool of choice with the link below. The leaderboard will update in realtime when people refer your stream to their friends!
          </p>

          <label for="embed-link">Leaderboard Address</label>

          <div class="input-group">
            <input id="embed-link" name="embed-link" class="form-control" type="text" disabled value="{{ $_SERVER['SERVER_NAME'] }}/embed/leaderboard/{{ Auth::user()->username }}"/>

            <div class="input-group-append">
              <button id="embed-copy" class="btn btn-secondary">
                Copy
              </button>
            </div>
          </div>

          @csrf
          <div id="leaderboard-alert" class="alert alert-success text-center mt-3" style="display: none;"></div>

        </div>

        <div class="mb-4">
          <label for="theme-selector">Theme</label>
          <select id="theme-selector" name="theme-selector" class="form-control">
            <option value="light" {{ $leaderboard->theme === 'light' ? 'selected' : '' }}>Light</option>
            <option value="dark" {{ $leaderboard->theme === 'dark' ? 'selected' : '' }}>Dark</option>
          </select>
        </div>

        <div class="form-group mb-4">
          <label for="leaderboard-length-slider">Number of rows on leaderboard: <span id="leaderboard-length">{{ $leaderboard->length }}</span></label>
          <input class="custom-range" type="range" name="leaderboard-length-slider" id="leaderboard-length-slider" min="3" max="10" value={{ $leaderboard->length }} />
        </div>

        <div class="mb-4">
          <button class="btn btn-primary" id='settings-submit' type="submit" value="Submit">Save</button>
        </div>

        <div class="btn-group-toggle form-group">
          <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#resetReferrals">
            Reset referrals
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="resetReferrals" tabindex="-1" role="dialog" aria-labelledby="resetReferrals" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Reset leaderboard</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to reset your referrals and start fresh?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger">Reset</button>
      </div>
    </div>
  </div>
</div>
