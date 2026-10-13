@extends('layouts.error')

@section('code', '403')
@section('heading', 'Akses ditolak')
@section('message'){{ $exception->getMessage() ?: 'Anda tidak memiliki izin untuk membuka halaman ini.' }}@endsection
