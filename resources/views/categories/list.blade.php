@extends('layouts.main')

@section('title', $title)

@section('content')
    <form action="{{ route('categories.list') }}" method="get" class="app-cmp-search-form">
        <div class="app-cmp-form-detail-shop">
                <div class="app-cmp-form-detail">
                    <label for="app-search-term">Search</label>
                    <input id="app-search-term" type="text" name="term" value="{{ $search['term'] }}">
                </div>
                <div class="app-cmp-actions-bar app-st-dense">
                    <button type="submit" class="app-cl-primary">Search</button>
                    <a href="{{ route('categories.list') }}">
                        <button type="button" class="app-cl-warn">Clear</button>
                    </a>
                </div>
            </form>
        </div>
    <nav class="app-cmp-links">
        <ul>
            @can('create', \App\models\Category::class)
                <li class="app-cl-primary app-cl-cmd">
                    <a href="{{ route('categories.create-form') }}">New Category</a>
                </li>
            @endcan
        </ul>
    </nav>

    <main>
        <table class="app-cmp-data-list app-cl-common-links-container">
            <caption>Categories</caption>
            <colgroup>
                <col style="width: 0px;">
                <col>
                <col style="width: 0px;">
            </colgroup>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>No. of Products</th>
                </tr>
            </thead>
            <tbody>
                @php
                    session()->put('bookmark.categories.view', url()->full());
                @endphp

                @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                @endif

                @foreach ($categories as $category)
                    <tr>
                        <td>
                            <a href="{{ route('categories.view', ['category' => $category->code, ]) }}" class="app-cl-code">{{ $category->code }}</a>
                        </td>
                        <td>
                            <span class="app-cl-name">{{ $category->name }}</span>
                        </td>
                        <td>
                            <span class="app-cl-number">{{ $category->products_count }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>

    <div>{{ $categories->withQueryString()->links() }}</div>
@endsection