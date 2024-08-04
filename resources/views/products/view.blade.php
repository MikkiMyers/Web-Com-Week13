@extends('layouts.main')

@section('title', $title)

@section('content')
    <main>
        <dl class="app-cmp-data-detail">
            <dt>Code</dt>
            <dd>{{ $product->code }}</dd>

            <dt>Name</dt>
            <dd>{{ $product->name }}</dd>

            <dt>Price</dt>
            <dd>{{ number_format((float) $product->price, 2) }}</dd>
        </dl>
        <br>
        <pre>{{ $product->description }}</pre>
    </main>
@endsection