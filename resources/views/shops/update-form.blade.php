@extends('layouts.main')

@section('title', $title)

@section('content')
    <main>
        <form action="{{ route('shops.update', ['shop' => $shop->code]) }}" method="post">
            @csrf
            <div class="app-cmp-form-detail">
                
                <label for="app-inp-code">Code</label>
                <input id="app-inp-code" type="text" name="code" value="{{ old('code', $shop->code) }}" class="app-cl-code">
                @error('code')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <label for="app-inp-name">Name</label>
                <input id="app-inp-name" type="text" name="name" value="{{ old('name', $shop->name) }}" class="app-cl-name">
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <label for="app-inp-owner">Owner</label>
                <input id="app-inp-owner" type="text" name="owner" value="{{ old('owner', $shop->owner) }}" class="app-cl-name">
                @error('owner')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <label for="app-inp-latitude">Latitude</label>
                <input id="app-inp-latitude" type="number" step="any" name="latitude" value="{{ old('latitude', $shop->latitude) }}" class="app-cl-lalong">
                @error('latitude')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <label for="app-inp-longtitude">Longitude</label>
                <input id="app-inp-longtitude" type="number" step="any" name="longtitude" value="{{ old('longtitude', $shop->longtitude) }}" class="app-cl-lalong">
                @error('longtitude')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <label for="app-inp-address">Address</label>
                <textarea id="app-inp-address" name="address" cols="80" rows="10">{{ old('address', $shop->address) }}</textarea>
                @error('address')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="app-cmp-actions-bar">
                <button type="submit" class="app-cl-primary">Update</button>
                <a href="{{ route('shops.view', ['shop' => $shop->code]) }}">
                    <button type="button">Cancel</button>
                </a>
            </div>
        </form>
    </main>
@endsection
