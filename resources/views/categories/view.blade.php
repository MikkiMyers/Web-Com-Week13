@extends('layouts.main')

@section('title', $title)

@section('content')
    <nav class="app-cmp-link-cat">
        <ul>
            <li>
                <a href="{{ session()->get('bookmark.categories.view', route('categories.list')) }}">&lt;Back</a>
            </li>
            <li class="app-cl-primary">
                <a href="{{ route('categories.view-products', ['category' => $category->code, ]) }}">Show Products</a>
            </li>
            @can('update', \App\Models\Category::class)
                <li class="app-cl-primary app-cl-cmd-cat">
                    <a href="{{ route('categories.update-form', ['category' => $category->code, ]) }}">Update</a>
                </li>
            @endcan
            @can('delete',  $category)
                <li class="app-cl-warn app-cl-cmd-cat">
                    <a href="{{ route('categories.delete', ['category' => $category->code, ]) }}">Delete</a>
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
        <dl class="app-cmp-data-detail-cat">
            <dt>Code</dt>
            <dd>
                <span class="app-cl-code-cat">{{ $category->code }}</span>
            </dd>

            <dt>Name</dt>
            <dd>
                <span class="app-al-name-cat">{{ $category->name }}</span>
            </dd>

            <dt>Description</dt>
            <dd>
                <pre>{{ $category->description }}</pre>
            </dd>
        </dl>
    </main>
@endsection