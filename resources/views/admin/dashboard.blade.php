@extends('layout.template')

@section('title', 'Dashboard')

@section('breadcrumb')
    <div class="page-info">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
    </div>
@endsection

@section('main')
    <div>
        <h1>Welcome, {{ Auth::user()->nama }}</h1>
        <p>Here's a summary of your platform's performance today:</p>

        <div class="row stats-row">
            <div class="col-lg-4 col-md-12">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="stats-icon change-success">
                            <i class="material-icons">text_snippet</i>
                        </div>
                        <div class="stats-info">
                            <h5 class="card-title">$3,089.67</h5>
                            <p class="stats-text">Active Request</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="stats-info">
                            <h5 class="card-title">168,047</h5>
                            <p class="stats-text">Registered Bengkel</p>
                        </div>
                        <div class="stats-icon change-success">
                            <i class="material-icons">person_add</i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="stats-info">
                            <h5 class="card-title">47,350</h5>
                            <p class="stats-text">Total Earning</p>
                        </div>
                        <div class="stats-icon change-success">
                            <i class="material-icons">attach_money</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <h3>Recent Service Requests</h3>
            </div>
            <div class="row">
                <h3>Recent Registered Bengkel</h3>
            </div>
        </div>
    </div>
@endsection