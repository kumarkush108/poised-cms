@extends('admin.layouts.app')

@section('title', 'Contact Message')

@section('content')

<div class="page-heading">

    <div class="page-heading-copy">

        <span class="page-icon">
            <i class="bi bi-envelope-open"></i>
        </span>

        <div>

            <p class="eyebrow mb-1">Inbox</p>

            <h1 class="h3 mb-1">Message from {{ $contactMessage->name }}</h1>

            <p class="text-muted mb-0">
                Submitted {{ $contactMessage->created_at->format('M j, Y H:i') }} via {{ ucfirst($contactMessage->source_page) }} page
            </p>

        </div>

    </div>

    <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Inbox
    </a>

</div>

@if (session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
@endif

<div class="card mt-4">

    <div class="card-body">

        <dl class="row mb-0">

            <dt class="col-sm-2">Status</dt>
            <dd class="col-sm-10">
                <span class="badge {{ match ($contactMessage->status) {
                    'new' => 'bg-danger',
                    'read' => 'bg-secondary',
                    'archived' => 'bg-light text-dark',
                    default => 'bg-secondary',
                } }}">
                    {{ ucfirst($contactMessage->status) }}
                </span>
            </dd>

            <dt class="col-sm-2">Name</dt>
            <dd class="col-sm-10">{{ $contactMessage->name }}</dd>

            <dt class="col-sm-2">Email</dt>
            <dd class="col-sm-10">{{ $contactMessage->email }}</dd>

            <dt class="col-sm-2">Phone</dt>
            <dd class="col-sm-10">{{ $contactMessage->phone ?? '—' }}</dd>

            <dt class="col-sm-2">Subject</dt>
            <dd class="col-sm-10">{{ $contactMessage->subject ?? '—' }}</dd>

            <dt class="col-sm-2">Source</dt>
            <dd class="col-sm-10">{{ ucfirst($contactMessage->source_page) }}</dd>

            <dt class="col-sm-2">IP Address</dt>
            <dd class="col-sm-10">{{ $contactMessage->ip_address ?? '—' }}</dd>

            <dt class="col-sm-2">Message</dt>
            <dd class="col-sm-10" style="white-space: pre-wrap;">{{ $contactMessage->message }}</dd>

        </dl>

    </div>

</div>

@if ($contactMessage->status !== 'archived')
    <form method="POST" action="{{ route('admin.contact-messages.archive', $contactMessage) }}" class="mt-3">
        @csrf
        @method('PATCH')
        <button type="submit" class="btn btn-outline-secondary">
            <i class="bi bi-archive"></i> Archive
        </button>
    </form>
@endif

@endsection
