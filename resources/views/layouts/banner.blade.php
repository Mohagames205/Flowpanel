@extends('layouts.app')

@section('content')

    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
        <div class="name">
            <a href="/">⌂ Home </a>
            <p id='a'> Hoi {{ Auth::User()->name }} </p>
        </div>

    @yield("menu")
@endsection
