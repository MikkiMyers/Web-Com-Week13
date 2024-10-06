@extends('layouts.main')

@section('title', $title)

@section('content')
    <nav class="app-cmp-link-cat">
        <ul>
            <li>
                <a href="{{ session()->get('bookmark.shops.view', route('shops.list')) }}">&lt;Back</a>
            </li>
            <li class="app-cl-primary">
                <a href="{{ route('shops.view-products', ['shop' => $shop->code, ]) }}">Show Products</a>
            </li>
            @can('update', \App\Models\Shop::class)
                <li class="app-cl-primary app-cl-cmd-cat">
                    <a href="{{ route('shops.update-form', ['shop' => $shop->code, ]) }}">Update</a>
                </li>
            @endcan
            @can('delete', \App\Models\Shop::class)
            <li class="app-cl-warn app-cl-cmd-cat">
                <a href="{{ route('shops.delete', ['shop' => $shop->code, ]) }}">Delete</a>
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
                <span class="app-cl-code">{{ $shop->code }}</span>
            </dd>

            <dt>Name</dt>
            <dd>
                <span class="app-al-name">{{ $shop->name }}</span>
            </dd>

            <dt>Owner</dt>
            <dd>{{ $shop->owner }}</dd>

            <dt>Location</dt>
            <dd>
                <span class="app-al-number">
                    {{ number_format((float) $shop->latitude, 7) }}
                    {{ number_format((float) $shop->longtitude, 7) }}
                </span>
            </dd>

            <dt>Address</dt>
            <dd>
                <pre>{{ $shop->address }}</pre>
            </dd>
        </dl>
    </main>
@endsection