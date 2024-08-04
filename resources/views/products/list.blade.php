@extends('layouts.main')

@section('title', $title)

@section('content')
    <main>
        <table class="app-cmp-data-list">
            <thead>
                <th>Code</th>
                <th>Name</th>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td><a href="{{ route('products.view', ['product'=>$product->code]) }}">{{ $product->code }}</a></td>
                        <td>{{ $product->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
@endsection