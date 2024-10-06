@extends('layouts.main')

@section('title', $title)

@section('content')
    <nav class="app-cmp-link-cat">
        <ul>
            <li>
                <a href="{{ route('products.view-shops', ['product' => $product->code, ]) }}">&lt;Back</a>
            </li>
        </ul>
    </nav>

    <form action="{{ route('products.add-shops-form', ['product' => $product->code, ]) }}" method="get" class="app-cmp-search-form">
        <div class="app-cmp-form-detail-shop">
            <div class="app-cmp-form-detail">
                <label for="app-search-term">Search</label>
                <input id="app-search-term" type="text" name="term" value="{{ $search['term'] }}">
            </div>
            <div class="app-cmp-actions-bar app-st-dense">
                <button type="submit" class="app-cl-primary">Search</button>
                <a href="{{ route('products.add-shops-form', ['product' => $product->code, ]) }}">
                    <button type="button" class="app-cl-warn">Clear</button>
                </a>
            </div>
        </div>
    </form>

    <main>
        <form action="{{ route('products.add-shops-form', ['product' => $product->code, ]) }}" method="post">
            @csrf
            <table class="app-cmp-data-list app-cl-common-link-container">
                <caption>Shops for adding to {{ $product->name }} </caption>
                <colgroup>
                    <col style="width: 0px">
                    <col>
                    <col style="width: 0px">
                    <col style="width: 0px">
                </colgroup>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Owner</th>
                        <th>&nbsp;</th>
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
                                <button type="submit" name="shop" value="{{ $shop->code }}" class="app-cl-primary">Add</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
    </main>

    <div>{{ $shops->withQueryString()->links() }}</div>
@endsection