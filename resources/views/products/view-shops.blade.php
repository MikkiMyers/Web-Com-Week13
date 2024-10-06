@extends('layouts.main')

@section('title', $title)

@section('content')
    <nav class="app-cmp-link-cat">
        <ul>
            <li>
                <a href="{{ route('products.view', ['product' => $product->code, ]) }}">&lt;Back</a>
            </li>
            @can('update', \App\Models\Product::class)
                <li class="app-cl-primary app-cl-cmd-cat">
                    <a href="{{ route('products.add-shops-form', ['product' => $product->code, ]) }}">Add Shops</a>
                </li>
            @endcan
        </ul>
    </nav>

    <form action="{{ route('products.view-shops', ['product' => $product->code, ]) }}" method="get" class="app-cmp-search-form">
        <div class="app-cmp-form-detail-shop">
            <div class="app-cmp-form-detail">
                <label for="app-search-term">Search</label>
                <input id="app-search-term" type="text" name="term" value="{{ $search['term'] }}">
            </div>
            <div class="app-cmp-actions-bar app-st-dense">
                <button type="submit" class="app-cl-primary">Search</button>
                <a href="{{ route('products.list') }}">
                    <button type="button" class="app-cl-warn">Clear</button>
                </a>
            </div>
        </div>
    </form>

    <main>
        <table class="app-cmp-data-list app-cl-common-links-container">
            <caption>Shop of {{ $product->name }}</caption>
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
                    @can('update', \App\Models\Product::class)
                        <th>&nbsp;</th>
                    @endcan
                </tr>
            </thead>
            <tbody>
                @php
                    session()->put('bookmark.shops.view', url()->full());
                @endphp

                @foreach ($shops as $shop)
                    <tr>
                        <td>
                            <a href="{{ route('shops.view', ['shop' => $shop->code, ]) }}" class="app-cl-code">{{ $shop->code }}</a>
                        </td>
                        <td>
                            <span class="app-cl-name">{{ $shop->name }}</span>
                        </td>
                        <td>{{ $shop->owner }}</td>
                        @can('update', \App\Models\Product::class)
                            <td>
                                <a href="{{ route('products.remove-shop', ['product' => $product->code, 'shop' => $shop->code, ]) }}">
                                    <button type="button" class="cpp-cl-warn">Remove</button>
                                </a>
                            </td>
                        @endcan
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
    <div>{{ $shops->withQueryString()->links() }}</div>
@endsection