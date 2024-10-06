@extends('layouts.main')

@section('title', $title)

@section('content')
    <div class="container">
        <h1 class="page-title">Edit Your Profile</h1>

        <form action="{{ route('users.update-self-submit', $user->id) }}" method="post" class="search-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" class="price-input" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="price-input" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="price-input" placeholder="Leave blank if you don't want to change">
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="button-group">
                <button type="submit" class="primary-button">Update Profile</button>
                <a href="{{ route('users.self', $user->id) }}" class="secondary-button">Cancel</a>
            </div>
        </form>
    </div>
@endsection
