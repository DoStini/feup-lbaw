@extends('layouts.app')

@section('title', $admin->name)

@section('content')
@include('partials.profileOrDashboard', ['admin' => $admin, 'page' => $page, 'links' => 'dashboardLinks'])
@endsection
