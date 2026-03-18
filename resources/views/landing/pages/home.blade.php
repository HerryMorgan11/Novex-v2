@extends('landing.layout.app')

@section('content')
    <main>
        @include('landing.sections.home.header')

        @include('landing.sections.home.modulesSection')

        @include('landing.sections.home.choose')

        @include('landing.sections.home.pricing')
    </main>
@endsection
