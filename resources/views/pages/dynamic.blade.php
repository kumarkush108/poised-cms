@extends('layouts.app')

@section('title', $page->title)

@section('content')

    @foreach ($page->sections->sortBy('order_column') as $section)
        @includeIf('partials.dynamic-sections.' . $section->section_key, ['section' => $section])
    @endforeach

@endsection
