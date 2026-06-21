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
            <p class="text-muted mb-0">Poised Technology CMS</p>
        </div>

    </div>

</div>

{{-- Real metric cards --}}
<section class="row g-3 mt-1">

    <div class="col-md-3">
        <a href="{{ route('admin.pages.index') }}" class="text-decoration-none">
            <div class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Pages</span>
                    <span class="metric-icon"><i class="bi bi-file-earmark"></i></span>
                </div>
                <div class="metric-value">{{ $pageCount }}</div>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('admin.media.index') }}" class="text-decoration-none">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Media Files</span>
                    <span class="metric-icon"><i class="bi bi-images"></i></span>
                </div>
                <div class="metric-value">{{ $mediaCount }}</div>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('admin.menus.index') }}" class="text-decoration-none">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Active Menu Items</span>
                    <span class="metric-icon"><i class="bi bi-list-nested"></i></span>
                </div>
                <div class="metric-value">{{ $menuItemCount }}</div>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('admin.contact-messages.index') }}" class="text-decoration-none">
            <div class="metric-card metric-danger">
                <div class="metric-top">
                    <span class="metric-label">Messages</span>
                    <span class="metric-icon"><i class="bi bi-envelope"></i></span>
                </div>
                <div class="metric-value">{{ $totalMessages }}</div>
                @if ($unreadMessages > 0)
                    <div class="metric-sub text-danger">{{ $unreadMessages }} unread</div>
                @endif
            </div>
        </a>
    </div>

</section>

{{-- Quick actions --}}
<div class="card mt-4">
    <div class="card-body">

        <h2 class="h6 mb-3">Quick Actions</h2>

        <div class="row g-3">

            @if ($homePage)
                <div class="col-md-3">
                    <a href="{{ route('admin.pages.edit', $homePage) }}"
                       class="quick-action d-flex flex-column align-items-center gap-2 p-3 border rounded text-decoration-none text-reset">
                        <i class="bi bi-house-door fs-4 text-primary"></i>
                        <span class="small fw-semibold">Edit Home Page</span>
                    </a>
                </div>
            @endif

            <div class="col-md-3">
                <a href="{{ route('admin.media.index') }}"
                   class="quick-action d-flex flex-column align-items-center gap-2 p-3 border rounded text-decoration-none text-reset">
                    <i class="bi bi-upload fs-4 text-success"></i>
                    <span class="small fw-semibold">Upload Media</span>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('admin.contact-messages.index') }}"
                   class="quick-action d-flex flex-column align-items-center gap-2 p-3 border rounded text-decoration-none text-reset">
                    <i class="bi bi-inbox fs-4 text-warning"></i>
                    <span class="small fw-semibold">View Messages</span>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('admin.settings.index') }}"
                   class="quick-action d-flex flex-column align-items-center gap-2 p-3 border rounded text-decoration-none text-reset">
                    <i class="bi bi-palette fs-4 text-danger"></i>
                    <span class="small fw-semibold">Theme Settings</span>
                </a>
            </div>

        </div>

    </div>
</div>

{{-- Recent messages --}}
@if ($recentMessages->isNotEmpty())
    <div class="card mt-4">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h6 mb-0">Recent Messages</h2>
                <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-sm btn-outline-secondary">
                    View All
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Subject</th>
                            <th scope="col">Received</th>
                            <th scope="col">Status</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentMessages as $message)
                            <tr class="{{ $message->status === 'new' ? 'fw-semibold' : '' }}">
                                <td>{{ $message->name }}</td>
                                <td class="text-muted">{{ $message->subject ?? '—' }}</td>
                                <td class="text-muted text-nowrap">{{ $message->created_at->diffForHumans() }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($message->status) {
                                            'new'      => 'bg-danger',
                                            'read'     => 'bg-secondary',
                                            'archived' => 'bg-light text-dark border',
                                            default    => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ ucfirst($message->status) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.contact-messages.show', $message) }}"
                                       class="btn btn-xs btn-outline-secondary">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endif

@endsection
