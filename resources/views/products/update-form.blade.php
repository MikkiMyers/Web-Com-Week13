@extends('layouts.main')

@section('title', $title)

@section('content')
    <main>
        <form action="{{ route('products.update', ['product' => $product->code, ]) }}" method="post">
            @csrf
            <div class="app-cmp-form-detail">
                
                <label for="app-inp-code">Code</label>
                <input id="app-inp-code" type="text" name="code" value="{{ old('code', $product->code) }}" class="app-cl-code">
                @error('code')
                    <div class="error-message">{{ $message }}</div>
                @enderror
        
                <label for="app-inp-name">Name</label>
                <input id="app-inp-name" type="text" name="name" value="{{ old('name', $product->name) }}" class="app-cl-name">
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
        
                <label for="app-inp-category">Category</label>
                <select id="app-inp-category" name="category" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->code }}" @selected(old('category', $product->category->getKey()) === $category->getKey())>[{{ $category->code }}] {{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category')
                    <div class="error-message">{{ $message }}</div>
                @enderror
        
                <label for="app-inp-price">Price</label>
                <input id="app-inp-price" type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" class="app-cl-price">
                @error('price')
                    <div class="error-message">{{ $message }}</div>
                @enderror
        
                <label for="app-inp-description">Description</label>
                <textarea id="app-inp-description" name="description" cols="80" rows="10">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror

            </div>
            <div class="app-cmp-actions-bar">
                <button type="submit" class="app-cl-primary">Update</button>
                <a href="{{ route('products.view', ['product' => $product->code, ]) }}">
                    <button type="button">Cancel</button>
                </a>
            </div>
        </form>
    </main>
@endsection
