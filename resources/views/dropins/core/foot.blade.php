    @auth
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
    @endauth
    <!-- Scripts -->
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
