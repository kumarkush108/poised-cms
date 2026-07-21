@extends('emails.layouts.main')

@section('content')
    <h2 style="margin:0 0 8px; font-size:20px; color:#222222;">Thanks, {{ $contactMessage->name }} — we've got your request!</h2>
    <p style="margin:0 0 20px; color:#555555;">
        We've received your online appointment request and a member of our team will reach out shortly to confirm
        a time. Here's a copy of what you sent us:
    </p>

    @include('emails.partials.details-table', ['rows' => [
        'Service Requested' => $contactMessage->subject,
        'Message' => $contactMessage->message,
    ]])

    <p style="margin:24px 0 0; color:#555555;">
        Need to change anything? Just reply to this email.
    </p>
@endsection
