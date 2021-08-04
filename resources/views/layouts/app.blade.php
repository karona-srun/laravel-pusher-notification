<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .dropdown-item{
            background: transparent !important;
        } 
        .text-none:hover{
            text-decoration: none !important;
        }
    </style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" v-pre>
                                    Notification <span
                                        class="count position-absolute text-white top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $notifications->count() }}</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    @forelse($notifications as $notification)
                                        <div class="dropdown-item  clearfix">
                                            <a href="{{ $notification->data['link'] }}" class="text-dark text-none">
                                                [{{ $notification->created_at }}]
                                                {{ $notification->data['email'] }} has just registered.
                                                <a href="#" class="float-right mark-as-read btn btn-sm btn-danger"
                                                    data-id="{{ $notification->id }}">
                                                    Mark as read
                                                </a>
                                            </a>
                                        </div>
                                        @if ($loop->last)
                                            <div class="dropdown-item">
                                                <a href="#" id="mark-all">
                                                        Mark all as read
                                                </a>
                                            </div>
                                        @endif

                                    @empty
                                        <div class="dropdown-item">
                                            <p id="mark-all">
                                                There are no new notifications
                                            </p>
                                        </div>
                                    @endforelse
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                 document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script>
        function sendMarkRequest(id = null) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            return $.ajax("{{ url('mark-as-read') }}", {
                type: 'POST',
                data: {
                    id
                }
            });
        }
        $(function() {
            $('.mark-as-read').click(function() {
                let request = sendMarkRequest($(this).data('id'));
                request.done(() => {
                    $(this).parents('div.dropdown-item').remove();
                    let count = $('span.count').html();
                    $('span.count').html(count - 1);
                });
            });
            $('#mark-all').click(function() {
                let request = sendMarkRequest();
                request.done(() => {
                    $('div.dropdown-item').remove();
                    $('span.count').html(0);
                })
            });
        });
    </script>
    @yield('js')
</body>

</html>
