@extends('layouts.app')

@section('title', 'Riwayat Kunjungan')

@section('menuAnggotaKunjungan', 'active')

@section('content')
    @livewire('anggota.kunjungan.index')
@endsection
