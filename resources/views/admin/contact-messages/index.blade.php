@extends('admin.layouts.app')

@section('title', 'Contact Messages')

@section('content')

<div class="page-heading">

    <div class="page-heading-copy">

        <span class="page-icon">
            <i class="bi bi-envelope"></i>
        </span>

        <div>

            <p class="eyebrow mb-1">Inbox</p>

            <h1 class="h3 mb-1">Contact Messages</h1>

            <p class="text-muted mb-0">
                Review messages submitted through the website's contact and appointment forms.
                @if ($unreadCount > 0)
                    <span class="badge bg-danger">{{ $unreadCount }} unread</span>
                @endif
            </p>

        </div>

    </div>

</div>

@if (session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
@endif

<div class="card mt-4">

    <div class="card-body">

        <table class="table align-middle mb-0">

            <thead>
                <tr>
                    <th>Status</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Source</th>
                    <th>Received</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($messages as $message)
                    <tr>
                        <td>
                            <span class="badge {{ match ($message->status) {
                                'new' => 'bg-danger',
                                'read' => 'bg-secondary',
                                'archived' => 'bg-light text-dark',
                                default => 'bg-secondary',
                            } }}">
                                {{ ucfirst($message->status) }}
                            </span>
                        </td>
                        <td>{{ $message->name }}</td>
                        <td>{{ $message->email }}</td>
                        <td>{{ $message->subject ?? '—' }}</td>
                        <td>{{ ucfirst($message->source_page) }}</td>
                        <td>{{ $message->created_at->format('M j, Y H:i') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.contact-messages.show', $message) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No messages have been received yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

    </div>

</div>

<div class="mt-3">
    {{ $messages->links() }}
</div>

@endsection
