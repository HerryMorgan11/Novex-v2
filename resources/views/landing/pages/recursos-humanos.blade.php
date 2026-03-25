@extends('landing.layout.app')

@section('content')
<main>
    @include('landing.sections.recursos-humanos.hero')
    @include('landing.sections.recursos-humanos.funcionalidades')
    @include('landing.sections.recursos-humanos.metricas')
    @include('landing.sections.recursos-humanos.cta')
</main>
@endsection
