@extends('layouts.main')

@section('title', $title)

@section('content')
    <main>
        <dl class="app-cmp-data-detail">
            <dt>Code</dt>
            <dd>{{ $shop->code }}</dd>

            <dt>Name</dt>
            <dd>{{ $shop->name }}</dd>

            <dt>Owner</dt>
            <dd>{{ $shop->owner }}</dd>

            <dt>Location</dt>
            <dd>{{ number_format((float) $shop->latitude, 7) }},{{ number_format((float) $shop->longitude, 7) }} </dd>

            <dt>Address</dt>
            <dd>{{ $shop->address }}</dd>
        </dl>
    </main>
@endsection