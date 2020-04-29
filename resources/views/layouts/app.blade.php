@include('dropins.core.head')

<div class="app-wrapper d-flex">
  <nav class="nav">
    <div class="nav__header">
      <h3>
        <a href="{{ route('dashboard') }}">
          <img class="logo p-3 d-none d-md-block" src="{{ asset('images/brand/buffs_logo.svg') }}" alt="BUFFS" />
          <img class="logo p-3 d-block d-md-none" src="{{ asset('images/brand/potion.png') }}" alt="BUFFS" />
        </a>
      </h3>
    </div>

    <ul class="nav__items list-unstyled components m-0">
      {{-- <li>
        <a href="{{ route('dashboard-chatbot') }}">
          @svg('icons/chat')
          Chatbot
        </a>
      </li> --}}
      @if(Auth::user()->hasRole('admin'))
      <li>
        <a href="#adminSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle p-3 d-none d-md-block">
          @svg('icons/shield-lock')
          Admin
        </a>
        <ul class="collapse list-unstyled" id="adminSubmenu">
          <li>
            <a href="{{ route('admin.dashboard') }}" class="p-3 d-block">Dashboard</a>
          </li>
          <li>
            <a href="{{ route('admin.chatbots') }}" class="p-3 d-block">Chatbots</a>
          </li>
          <li>
            <a href="{{ route('admin.betalist') }}" class="p-3 d-block">BetaList</a>
          </li>

        </ul>
      </li>
      @endif
      <li>
        <a href="#" class="p-3 d-block logout-link">
          @svg('icons/arrow-bar-right')
          Logout
        </a>
      </li>
    </ul>

  </nav>

  <main role="main" class="app-content flex-grow-1 py-5 px-4">
    @yield('content')
  </main>
</div>

@include('dropins.core.foot')
