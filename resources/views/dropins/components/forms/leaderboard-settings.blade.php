<div class="row">
  <div class="col-12 col-xl-6 mb-3">
    <h2 class="mb-3">My Leaderboard</h2>
    @include('dropins.components.leaderboard', ['preview' => true])
  </div>

  <div class="col-12 col-xl-6">
    <form>
      <div id='settings' class="card p-4 my-3">
        <h3>Leaderboard Settings</h3>
        <div id="embed-info" class="form-group xl-4">
          <p>
            Add a source in OBS or your tool of choice with the link below. The leaderboard will update in realtime when people refer your stream to their friends!
          </p>

          <label for="embed-link"><strong>Leaderboard Address</strong></label>

          <div class="input-group">
            <input id="embed-link" name="embed-link" class="form-control" type="text" disabled value="{{ $_SERVER['SERVER_NAME'] }}/embed/leaderboard/{{ Auth::user()->username }}"/>

            <div class="input-group-append">
              <button id="embed-copy" class="btn btn-secondary">
                Copy
              </button>
            </div>
          </div>

          @csrf
        </div>

        <div class="mb-4">
          <label for="theme-selector"><strong>Theme</strong></label>
          <select id="theme-selector" name="theme-selector" class="form-control">
            <option value="light" {{ $leaderboard->theme === 'light' ? 'selected' : '' }}>Light</option>
            <option value="dark" {{ $leaderboard->theme === 'dark' ? 'selected' : '' }}>Dark</option>
          </select>
        </div>

        <div class="form-group mb-4">
          <label for="leaderboard-length-slider"><strong>Number of rows on leaderboard:</strong> <span id="leaderboard-length">{{ $leaderboard->length }}</span></label>
          <input class="custom-range" type="range" name="leaderboard-length-slider" id="leaderboard-length-slider" min="3" max="10" value={{ $leaderboard->length }} />
        </div>

        <div class="justify-content-between d-flex">
          <button class="btn btn-primary" id='settings-submit' type="button" disabled>Save</button>
          <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#resetReferrals">
            Reset referrals
          </button>
        </div>

        <div id="leaderboard-alert" class="alert alert-success text-center mt-4 mb-0" style="display: none;"></div>
      </div>

      <div class="row my-4 align-items-center d-none d-xl-flex">
        <div class="col-12 col-xl-4">
          <img src="{{ asset('images/obs-tutorial-thumbnail.png') }}" class="img-fluid rounded" data-toggle="modal" data-target="#obsTutorial" loading="lazy">
        </div>

        <div class="col-12 col-xl-8">
          <p data-toggle="modal" data-target="#obsTutorial" class="m-0">
            Add a leaderboard using OBS.
          </p>
        </div>
      </div>

    </form>
  </div>
</div>

<!-- OBS Tutorial Modal -->
<div class="modal fade" id="obsTutorial" tabindex="-1" role="dialog" aria-labelledby="obsTutorial" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">OBS Tutorial</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-0">
        <div class="embed-responsive embed-responsive-16by9" id="obsTutorialVideo">
          <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/rcTR0a4hCTE" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Reset Leaderboard Modal -->
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
        <button id="leaderboard-reset" type="button" class="btn btn-danger">Reset</button>
      </div>
    </div>
  </div>
</div>
