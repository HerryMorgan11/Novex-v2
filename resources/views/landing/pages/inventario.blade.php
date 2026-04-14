@extends('landing.layout.app')

@section('content')
<main>
    @include('landing.sections.inventario.hero')
    @include('landing.sections.inventario.funcionalidades')
    @include('landing.sections.inventario.metricas')
    @include('landing.sections.inventario.cta')
</main>
@endsection
