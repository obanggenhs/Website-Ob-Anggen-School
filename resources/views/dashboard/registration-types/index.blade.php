@extends('layouts.dashboard')

@section('title', 'Jalur & Formulir')
@section('page_title', 'Jalur Pendaftaran & Formulir')
@section('page_subtitle', 'Buka/tutup pendaftaran, atur kuota, persyaratan, dan isian formulir setiap jalur.')
@section('actions')
    <a href="{{ route('dashboard.registration-types.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Jalur baru</a>
@endsection

@section('content')
    <div class="row g-3">
        @forelse ($types as $t)
            <div class="col-md-6 col-xl-4">
                <div class="card dash-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                            <div>
                                <span class="badge badge-soft">{{ \App\Models\RegistrationType::AUDIENCES[$t->audience] }}{{ $t->level_label ? ' · '.$t->level_label : '' }}</span>
                                <h3 class="h6 mt-2 mb-0">{{ $t->name }}</h3>
                            </div>
                            <form method="POST" action="{{ route('dashboard.registration-types.toggle', $t) }}">
                                @csrf @method('PATCH')
                                <div class="form-check form-switch m-0" title="Buka / tutup pendaftaran">
                                    <input class="form-check-input fs-5" type="checkbox" role="switch" onchange="this.form.submit()" @checked($t->is_open)>
                                </div>
                            </form>
                        </div>
                        <div class="small text-muted-d mb-3">
                            {{ $t->status_label }}{{ $t->period_label ? ' · '.$t->period_label : '' }}{{ $t->quota ? ' · kuota '.$t->quota : '' }}
                        </div>
                        <div class="d-flex gap-3 small">
                            <span><strong class="fs-5 d-block">{{ $t->registrations_count }}</strong>pendaftar</span>
                            <span><strong class="fs-5 d-block text-warning">{{ $t->new_count }}</strong>baru</span>
                            <span><strong class="fs-5 d-block">{{ $t->fields_count }}</strong>isian form</span>
                        </div>
                    </div>
                    <div class="card-footer d-flex flex-wrap gap-1 justify-content-end">
                        <a href="{{ route('dashboard.applicants.index', ['jalur' => $t->id]) }}" class="btn btn-sm btn-light">Pendaftar</a>
                        <a href="{{ route('dashboard.registration-types.edit', [$t, 'tab' => 'formulir']) }}" class="btn btn-sm btn-light"><i class="bi bi-ui-checks"></i> Formulir</a>
                        <a href="{{ route('dashboard.registration-types.edit', $t) }}" class="btn btn-sm btn-soft"><i class="bi bi-pencil"></i> Info</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12"><div class="card dash-card">@include('dashboard.partials.empty', ['text' => 'Belum ada jalur pendaftaran.', 'action' => route('dashboard.registration-types.create'), 'actionLabel' => 'Buat jalur'])</div></div>
        @endforelse
    </div>
@endsection
