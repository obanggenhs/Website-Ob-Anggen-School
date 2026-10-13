{{--
    Layout lebar penuh (tanpa sidebar): galeri, kalender, fasilitas, formulir pendaftaran.
--}}
@extends('layouts.web')

@section('content')
    @include('web.partials.page-header')

    <section class="oa-section oa-section--tight">
        <div class="container oa-container">
            @include('web.partials.intro')
            @include('web.partials.extra-blocks', ['place' => 'atas'])
            @yield('page')
            @include('web.partials.extra-blocks', ['place' => 'bawah'])
        </div>
    </section>

    @yield('after')
@endsection
