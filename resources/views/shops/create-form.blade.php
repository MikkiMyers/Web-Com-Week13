@extends('layouts.main')

@section('title', $title)

@section('content')
    <main>
        <form action="{{ route('shops.create') }}" method="post">
            @csrf
            <div class="app-cmp-form-detail">
                
                <label for="app-inp-code">Code</label>
                <input id="app-inp-code" type="text" name="code" class="app-cl-code" value="{{ old('code') }}">
                @error('code')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <label for="app-inp-name">Name</label>
                <input id="app-inp-name" type="text" name="name" class="app-cl-name" value="{{ old('name') }}">
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <label for="app-inp-owner">Owner</label>
                <input id="app-inp-owner" type="text" name="owner" class="app-cl-name" value="{{ old('owner') }}">
                @error('owner')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <label for="app-inp-latitude">Latitude</label>
                <input id="app-inp-latitude" type="number" step="any" name="latitude" class="app-cl-lalong" value="{{ old('latitude') }}">
                @error('latitude')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <label for="app-inp-longtitude">Longitude</label>
                <input id="app-inp-longtitude" type="number" step="any" name="longtitude" class="app-cl-lalong" value="{{ old('longtitude') }}">
                @error('longtitude')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <label for="app-inp-address">Address</label>
                <textarea id="app-inp-address" name="address" cols="80" rows="10">{{ old('address') }}</textarea>
                @error('address')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            
                <div class="app-cmp-actions-bar">
                    <button type="submit" class="app-cl-primary">Create</button>
                    <a href="{{ route('shops.list') }}">
                        <button type="button">Cancel</button>
                    </a>   
                </div>
            </div>
        </form>
    </main>
@endsection
