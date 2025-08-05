@extends('layouts.app')

@section('title','Dashboard Admin') 
@section('menuAdminDashboard','active')

@section('content')
    @livewire('admin.dashboard.index')
@endsection
