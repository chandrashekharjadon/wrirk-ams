@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">Welcome, {{ auth()->user()->name }}</h1>
            <p class="card-text">This is your dashboard using Bootstrap!</p>
        </div>
    </div>
@endsection
