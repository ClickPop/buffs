<div class="form-group">
  <div class="row">
    <div class="col-12 col-lg-6">
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
          <label id="leaderboard-reset-label" for="leaderboard-reset" class="btn btn-danger">
            <input name="leaderboard-reset" id="leaderboard-reset" type="checkbox"/>
            Reset Leaderboard
          </label>
        </div>
        <div id="leaderboard-reset-confirm-alert" class="alert alert-danger text-center" style="display: none;">
          <label for="leaderboard-reset-confirm">Are you sure you want to reset the leaderboard?</label>
          <input type="checkbox" name="leaderboard-reset-confirm-checkbox" id="leaderboard-reset-confirm-checkbox" />
        </div>
      </div>
    </form>
  </div>
</div>
