{{--
    Layout halaman dalam: header halaman + isi (kiri) + sidebar berita & kontak (kanan).
    View anak mengisi: title, page_title, page_subtitle, breadcrumb, page, after.
--}}
@extends('layouts.web')

@section('content')
    @include('web.partials.page-header')

    @php
        $withSidebar = setting('layout_sidebar') && ($menu->show_sidebar ?? true) && ! View::hasSection('no_sidebar');
    @endphp

    <section class="oa-section oa-section--tight">
        <div class="container oa-container">
            <div class="row g-4 g-xl-5">
                <div class="{{ $withSidebar ? 'col-lg-8' : 'col-12' }}">
                    @include('web.partials.intro')
                    @include('web.partials.extra-blocks', ['place' => 'atas'])
                    @yield('page')
                    @include('web.partials.extra-blocks', ['place' => 'bawah'])
                </div>

                @if ($withSidebar)
                    <aside class="col-lg-4">
                        @include('web.partials.sidebar')
                    </aside>
                @endif
            </div>
        </div>
    </section>

    @yield('after')
@endsection
