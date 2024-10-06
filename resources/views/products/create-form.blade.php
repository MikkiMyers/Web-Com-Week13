@extends('layouts.main')

@section('title', $title)

@section('content')
    <main>

       


        <form action="{{ route('products.create') }}" method="post">
            @csrf
            <div class="app-cmp-form-detail">
                
                <label for="app-inp-code">Code</label>
                <input id="app-inp-code" type="text" name="code" value="{{ old('code') }}" class="app-cl-code">
                @error('code')
                    <div class="error-message">{{ $message }}</div>
                @enderror
        
                <label for="app-inp-name">Name</label>
                <input id="app-inp-name" type="text" name="name" value="{{ old('name') }}" class="app-cl-name">
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
        
                <label for="app-inp-category">Category</label>
                <select id="app-inp-category" name="category" required>
                    <option value="">--Please select--</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->code }}" @selected(old('category') === $category->code)>[{{ $category->code }}] {{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category')
                    <div class="error-message">{{ $message }}</div>
                @enderror
        
                <label for="app-inp-description">Description</label>
                <textarea id="app-inp-description" name="description" cols="80" rows="10">{{ old('description') }}</textarea>
                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                
            
                <div class="app-cmp-actions-bar">
                    <button type="submit" class="app-cl-primary">Create</button>
                    <a href="{{ route('products.list') }}">
                        <button type="button">Cancel</button>
                    </a>
                </div>
                
            </div>
        </form>
    </main>
@endsection