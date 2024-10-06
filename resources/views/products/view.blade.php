@extends('layouts.main')

@section('title', $title)

@section('content')
    <nav class="app-cmp-link-cat">
        <ul>
            <li>
                <a href="{{ session()->get('bookmark.products.view', route('products.list')) }}">&lt;Back</a>
            </li>
            <li class="app-cl-primary">
                <a href="{{ route('products.view-shops', ['product' => $product->code, ]) }}">Show Shops</a>
            </li>
            @can('update', \App\Models\Product::class)
                <li class="app-cl-primary app-cl-cmd-cat">
                    <a href="{{ route('products.update-form', ['product' => $product->code, ]) }}">Update</a>
                </li>
            @endcan
            @can('delete', \App\Models\Product::class)
                <li class="app-cl-warn app-cl-cmd-cat">
                    <a href="{{ route('products.delete', ['product' => $product->code, ]) }}">Delete</a>
                </li>
            @endcan
        </ul>
    </nav>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    
    <main>
        <dl class="app-cmp-data-detail">
            <dt>Code</dt>
            <dd>
                <span class="app-cl-code">{{ $product->code }}</span>
            </dd>

            <dt>Name</dt>
            <dd>
                <span class="app-al-name">{{ $product->name }}</span>
            </dd>

            <dt>Category</dt>
            <dd>
                <span class="app-al-code">[{{ $product->category->code }}]</span> {{ $product->category->name }}
            </dd>

            <dt>Price</dt>
            <dd>
                <span class="app-al-number">{{ number_format((float) $product->price, 2) }}</span>
            </dd>
        </dl>
      
        <pre>{{ $product->description }}</pre>
    </main>
@endsection