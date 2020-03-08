@include('dropins.core.head')

<div class="app-wrapper has-sidebar">
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3><img class="logo" src="{{ asset('images/brand/buffs_logo.svg') }}" alt="BUFFS"></h3>
            <strong>BS</strong>
        </div>

        <ul class="list-unstyled components">
            <li class="active">
                <a href="{{ route('dashboard') }}">
                    @svg('icons/columns-gutters')
                    Dashboard
                </a>
            </li>
            <li>
                <a href="#">
                    @svg('icons/wifi')
                    Streams
                </a>
            </li>
            <li>
                <a href="{{ route('admin.leaderboards') }}">
                    @svg('icons/kanban')
                    Leaderboards
                </a>
            </li>
            <li>
                <a href="#">
                    @svg('icons/eye')
                    Referrals
                </a>
            </li>
            <li>
                <a href="#adminSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    @svg('icons/shield-lock')
                    Admin
                </a>
                <ul class="collapse list-unstyled" id="adminSubmenu">
                    <li>
                        <a href="#">Users</a>
                    </li>
                    <li>
                        <a href="#">Streams</a>
                    </li>
                    <li>
                        <a href="{{route('leaderboards.index')}}">Leaderboards</a>
                    </li>
                    <li>
                        <a href="#">Referrals</a>
                    </li>
                </ul>
            </li>
        </ul>

    </nav>

    <main role="main" class="app-content py-3">
        @yield('content')
    </main>
</div>

@include('dropins.core.foot')