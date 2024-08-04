@extends('layouts.main')

@section('title', $title)

@section('content')
    <main>
        <table class="app-cmp-data-list">
            <thead>
                <th>Code</th>
                <th>Name</th>
                <th>Owner</th>
            </thead>
            <tbody>
                @foreach ($shops as $shop)
                    <tr>
                        <td><a href="{{ route('shops.view', $shop->code) }}">{{ $shop->code }}</a></td>
                        <td>{{ $shop->name }}</td>
                        <td>{{ $shop->owner }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
@endsection