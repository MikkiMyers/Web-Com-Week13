@extends('layouts.main')

@section('title', $title)

@section('content')
<nav class="app-cmp-link-cat">
    <ul>
        <li>
            <a href="{{ route('categories.view', ['category' => $category->code, ]) }}">&lt;Back</a>
        </li>
        </ul>
    </nav>

    <form action="{{ route('categories.add-products-form', ['category' => $category->code, ]) }}" method="get" class="app-cmp-search-form">
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
            <a href="{{ route('categories.add-products-form', ['category' => $category->code, ]) }}">
                <button type="button" class="app-cl-warn">Clear</button>
            </a>
        </div>
    </form>

    <main>
        <form action="{{ route('categories.add-products-form', ['category' => $category->code, ]) }}" method="post">
            @csrf
            <table class="app-cmp-data-list app-cl-common-link-container">
                <caption>Products for adding to {{ $category->name }} </caption>
                <colgroup>
                    <col style="width: 0px">
                    <col>
                    <col style="width: 0px">
                    <col style="width: 0px">
                    <col style="width: 0px">
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
                    @endphp
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                    @foreach ($products as $product)
                        <tr>
                            <td>
                                <a href="{{ route('products.view', ['product' => $product->code, ]) }}" class="app-cl-code">{{ $product->code }}</a>
                            </td>
                            <td>
                                <span class="app-cl-name">{{ $product->name }}</span>
                            </td>
                            <td>
                                <span class="app-cl-name">{{ $product->category->name }}</span>
                            </td>
                            <td>
                                <span class="app-cl-number">{{ number_format((float) $product->price, 2) }}</span>
                            </td>
                            <td>
                                <button type="submit" name="product" value="{{ $product->code }}" class="app-cl-primary">Add</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
    </main>

    <div>{{ $products->withQueryString()->links() }}</div>
@endsection