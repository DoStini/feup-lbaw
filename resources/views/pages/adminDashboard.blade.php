@extends('layouts.app')

@section('title', $admin->name)

@section('content')
@include('partials.profileOrDashboard', ['admin' => $admin, 'info' => $info, 'page' => $page, 'links' => 'dashboardLinks'])
@endsection
