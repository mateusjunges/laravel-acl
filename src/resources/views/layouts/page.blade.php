@extends('acl::layouts.master')
@section('acl_css')
    <!-- In <head> after the Bootstrap CSS. -->
    <link rel="stylesheet" href="https://unpkg.com/@bootstrapstudio/bootstrap-better-nav/dist/bootstrap-better-nav.min.css">
    @stack('css')
    @yield('css')
@endsection
@section('acl_js')
    <script src="https://unpkg.com/@bootstrapstudio/bootstrap-better-nav/dist/bootstrap-better-nav.min.js"></script>
    @stack('js')
    @yield('js')
@endsection

@section('body')
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">{{ config('acl.app.name')}}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                @each('acl::partials.menu-item', $acl->menu(), 'item')
            </ul>
            <div class="nav-item dropdown">
                <a id="navbarDropdown"
                   style="color: #000;"
                   class="nav-link dropdown-toggle" href="#"
                   role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ Auth::user()->name }} <span class="caret"></span>
                </a>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </nav>
    @if(session('message') != null)
        <div class="container">
            <div class="alert alert-{{session('message')['type']}}" role="alert">
                <h4 class="alert-heading">{{ session('message')['title'] }}</h4>
                <p>{{ session('message')['text'] }}</p>
            </div>
        </div>
    @endif

    @yield('content')
@endsection