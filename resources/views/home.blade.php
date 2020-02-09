@extends("layouts.banner")

@section("menu")

    <div class="search">
    <form method="POST" action="/user">
        @csrf
        <p><b>Username</b></p>
        <input type='text' name='naam' id='name' placeholder='Username'>
    </form>
    </div>

    @endsection
