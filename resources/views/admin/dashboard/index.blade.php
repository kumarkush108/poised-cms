@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

<div class="page-heading">

    <div class="page-heading-copy">

        <span class="page-icon">
            <i class="bi bi-speedometer2"></i>
        </span>

        <div>

            <p class="eyebrow mb-1">Overview</p>

            <h1 class="h3 mb-1">Dashboard</h1>

            <p class="text-muted mb-0">
                Welcome to Poised Technology CMS Dashboard
            </p>

        </div>

    </div>

</div>

<!-- Stats -->
<section class="row g-3 mt-1">

    <div class="col-md-3">

        <div class="metric-card metric-primary">

            <div class="metric-top">

                <span class="metric-label">
                    Total Pages
                </span>

                <span class="metric-icon">
                    <i class="bi bi-file-earmark"></i>
                </span>

            </div>

            <div class="metric-value">
                12
            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="metric-card metric-success">

            <div class="metric-top">

                <span class="metric-label">
                    Services
                </span>

                <span class="metric-icon">
                    <i class="bi bi-briefcase"></i>
                </span>

            </div>

            <div class="metric-value">
                8
            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="metric-card metric-warning">

            <div class="metric-top">

                <span class="metric-label">
                    Solutions
                </span>

                <span class="metric-icon">
                    <i class="bi bi-box"></i>
                </span>

            </div>

            <div class="metric-value">
                6
            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="metric-card metric-danger">

            <div class="metric-top">

                <span class="metric-label">
                    Messages
                </span>

                <span class="metric-icon">
                    <i class="bi bi-envelope"></i>
                </span>

            </div>

            <div class="metric-value">
                24
            </div>

        </div>

    </div>

</section>

@endsection