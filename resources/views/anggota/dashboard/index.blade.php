@extends('layouts.app')

@section('title', 'Dashboard Anggota')

@section('menuAnggotaDashboard', 'active')

@section('content')
    @livewire('anggota.dashboard.index') 
@endsection
