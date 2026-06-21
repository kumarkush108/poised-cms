@extends('emails.layouts.main')

@section('content')
    <h2 style="margin:0 0 8px; font-size:20px; color:#222222;">Thanks for reaching out, {{ $contactMessage->name }}!</h2>
    <p style="margin:0 0 20px; color:#555555;">
        We've received your message and a member of our team will get back to you as soon as possible.
        For your records, here's a copy of what you sent us:
    </p>

    @include('emails.partials.details-table', ['rows' => [
        'Subject' => $contactMessage->subject,
        'Message' => $contactMessage->message,
    ]])

    <p style="margin:24px 0 0; color:#555555;">
        If you need to add anything, just reply to this email.
    </p>
@endsection
