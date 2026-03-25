@extends('landing.layout.app')

@section('content')
<main>
    @include('landing.sections.crm.hero')
    @include('landing.sections.crm.funcionalidades')
    @include('landing.sections.crm.metricas')
    @include('landing.sections.crm.cta')
</main>
@endsection
