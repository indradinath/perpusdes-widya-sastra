@extends('layouts.app')

@section('title', 'Profile')

@section('menuAnggotaProfile', 'active')

@section('content')
    @livewire('anggota.profile.index')
@endsection
