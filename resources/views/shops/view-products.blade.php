@extends('layouts.main')

@section('title', $title)

@section('content')
    <nav class="app-cmp-link-cat">
        <ul>
            <li>
                <a href="{{ route('shops.view', ['shop' => $shop->code, ]) }}">&lt;Back</a>
            </li>
            @can('update', \App\Models\Shop::class)
                <li class="app-cl-primary app-cl-cmd-cat">
                    <a href="{{ route('shops.add-products-form', ['shop' => $shop->code, ]) }}">Add Products</a>
                </li>
            @endcan
        </ul>
    </nav>

    <form action="{{ route('shops.view-products', ['shop' => $shop->code, ]) }}" method="get" class="app-cmp-search-form">
        
        <div class="app-cmp-form-detail">
            <div class="app-cmp-search-form">
                <label for="app-search-term">Search</label>
                <input id="app-search-term" type="text" name="term" value="{{ $search['term'] }}">
    
                <label for="app-search-min-price">Min Price</label>
                <input id="app-search-min-price" type="number" name="minPrice" value="{{ $search['minPrice'] }}">
    
                <label for="app-search-max-price">Max Price</label>
                <input id="app-search-max-price" type="number" name="maxPrice" value="{{ $search['maxPrice'] }}">
            </div>
            <div class="app-cmp-action-bar app-st-dense app-ly-column">
                <button type="submit" class="app-cl-primary">Search</button>
                <a href="{{  route('shops.view-products', ['shop' => $shop->code, ]) }}">
                    <button type="button" class="app-cl-warn">Clear</button>
                </a>
        </div>
    </form>

    <main>
        <table class="app-cmp-data-list app-cl-common-links-container">
            <caption>Product of {{ $shop->name }}</caption>
            <colgroup>
                <col style="width: 0px;">
                <col>
                <col style="width: 0px;">
                <col style="width: 0px;">
                <col style="width: 0px;">
                <col style="width: 0px;">
            </colgroup>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @php
                    session()->put('bookmark.products.view', url()->full());
                    session()->put('bookmark.categories.view', url()->full());
                @endphp

                @foreach ($products as $product)
                    <tr>
                        <td>
                            <a href="{{ route('products.view', ['product' => $product->code, ]) }}" class="app-cl-code">{{ $product->code }}</a>
                        </td>
                        <td>
                            <span class="app-cl-name">{{ $product->name }}</span>
                        </td>
                        <td>
                            <a href="{{ route('categories.view', ['category' => $product->category->code, ]) }}" class="app-cl-name">{{ $product->category->name }}</a>
                        </td>
                        <td>
                            <span class="app-cl-number">{{ number_format((float) $product->price, 2) }}</span>
                        </td>
                        <td>
                            @can('delete', \App\Models\Shop::class)
                                <a href="{{ route('shops.remove-product', ['shop' => $shop->code, 'product' => $product->code, ]) }}">
                                    <button type="button" class="app-cl-warn">Remove</button>
                                </a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
    <div>{{ $products->withQueryString()->links() }}</div>
@endsection