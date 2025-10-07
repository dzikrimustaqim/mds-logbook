@extends('layouts.guest')
@section('content')
<div class="card-switch wrapper"> 
    <label class="switch">
        <input type="checkbox" class="toggle" id="auth-toggle">
        <span class="slider"></span>
        <span class="card-side"></span>
        <div class="flip-card__inner">
            <div class="flip-card__front">
                <div class="title">Log in</div>
                <form class="flip-card__form" action="{{ route('login') }}" method="POST">
                    @csrf
                    <input class="flip-card__input" name="username" placeholder="Username" type="text" value="{{ old('username') }}" required autofocus>
                    @error('username')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    <input class="flip-card__input" name="password" placeholder="Password" type="password" required>
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    <button class="flip-card__btn" type="submit">Let`s go!</button>
                </form>
            </div>
            <div class="flip-card__back">
                <div class="title">Sign up</div>
                <form class="flip-card__form" action="{{ route('register') }}" method="POST">
                    @csrf
                    <input class="flip-card__input" name="name" placeholder="Name" type="text" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    <input class="flip-card__input" name="username" placeholder="Username" type="text" value="{{ old('username') }}" required>
                    @error('username')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    <input class="flip-card__input" name="password" placeholder="Password" type="password" required>
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    <input class="flip-card__input" name="password_confirmation" placeholder="Confirm Password" type="password" required>
                    <button class="flip-card__btn" type="submit">Confirm!</button>
                </form>
            </div>
        </div>
    </label>
</div>
@endsection