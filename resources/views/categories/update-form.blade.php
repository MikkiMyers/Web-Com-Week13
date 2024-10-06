@extends('layouts.main')

@section('title', $title)

@section('content')
    <main>
        <form action="{{ route('categories.update', ['category' => $category->code, ]) }}" method="post">
            @csrf
            <div class="app-cmp-form-detail">
                <label for="app-inp-code">Code</label>
                <input id="app-inp-code" type="text" name="code" value="{{ old('code', $category->code) }}" class="app-cl-code">
                @error('code')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <label for="app-inp-name">Name</label>
                <input id="app-inp-name" type="text" name="name" value="{{ old('name', $category->name) }}" class="app-cl-name">
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <label for="app-inp-description">Description</label>
                <textarea id="app-inp-description" name="description" cols="80" rows="10">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="app-cmp-actions-bar">
                <button type="submit" class="app-cl-primary">Update</button>
                <a href="{{ route('categories.view', ['category' => $category->code, ]) }}">
                    <button type="button">Cancel</button>
                </a>
            </div>
        </form>
    </main>
@endsection
