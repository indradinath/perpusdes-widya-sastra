@extends('layouts.app') 

@section('title', 'Katalog Buku')

@section('menuAnggotaBuku', 'active')

@section('content')
    @livewire('anggota.buku.index')
@endsection
