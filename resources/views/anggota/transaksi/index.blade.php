@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('menuAnggotaTransaksi', 'active')

@section('content')
    @livewire('anggota.transaksi.index')
@endsection
