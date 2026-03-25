@extends('landing.layout.app')

@section('content')
<main>
    @include('landing.sections.contabilidad.hero')
    @include('landing.sections.contabilidad.funcionalidades')
    @include('landing.sections.contabilidad.dashboard')
    @include('landing.sections.contabilidad.cta')
</main>
@endsection
