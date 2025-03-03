@extends('layouts.main')

@section('title', $title)

@section('content')
    <form action="{{ route('shops.list') }}" method="get" class="app-cmp-search-form">
        <div class="app-cmp-form-detail-shop">
            <div class="app-cmp-form-detail">
                <label for="app-search-term">Search</label>
                <input id="app-search-term" type="text" name="term" value="{{ $search['term'] }}">
            </div>
            <div class="app-cmp-actions-bar app-st-dense">
                <button type="submit" class="app-cl-primary">Search</button>
                <a href="{{ route('shops.list') }}">
                    <button type="button" class="app-cl-warn">Clear</button>
                </a>
            </div>
        </div>
    </form>

    <nav class="app-cmp-links">
        <ul>
            @can('create', \App\models\Product::class)
                <li class="app-cl-primary app-cl-cmd">
                    <a href="{{ route('shops.create-form') }}">New Shop</a>
                </li>
            @endcan
        </ul>
    </nav>

    <main>
        <table class="app-cmp-data-list app-cl-common-links-container">
            <caption>shop</caption>
            <colgroup>
                <col style="width: 0px;">
                <col>
                <col style="width: 0px;">
                <col style="width: 0px;">
            </colgroup>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Owner</th>
                    <th>No. of Products</th>
                </tr>
            </thead>
            <tbody>
                @php
                    session()->put('bookmark.shops.view', url()->full());
                @endphp

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                
                @foreach ($shops as $shop)
                    <tr>
                        <td>
                            <a href="{{ route('shops.view', ['shop' => $shop->code, ]) }}" class="app-cl-code">{{ $shop->code }}</a>
                        </td>
                        <td>
                            <span class="app-cl-name">{{ $shop->name }}</span>
                        </td>
                        <td>{{ $shop->owner }}</td>
                        <td>
                            <span class="app-cl-number">{{ $shop->products_count }} </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>

    <div>{{ $shops->withQueryString()->links() }}</div>
@endsection