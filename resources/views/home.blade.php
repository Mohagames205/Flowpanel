@extends('layouts.app')

@section('content')

    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
        <div class="name">
            <a href="/">⌂ Home </a>
            <p id='a'> Hoi {{ Auth::User()->name }} </p>
        </div>

    <div class='search'>
        <form method="POST" action="/user">
            <p><b>Username</b></p>
            @csrf
            <input type='text' name='naam' id='name' placeholder='Username'>
            <button type="submit">Zoeken</button>
        </form>
    </div>
@endsection
