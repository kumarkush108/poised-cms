@extends('emails.layouts.main')

@section('content')
    <h2 style="margin:0 0 8px; font-size:20px; color:#222222;">New Contact Form Submission</h2>
    <p style="margin:0 0 20px; color:#666666;">
        Someone submitted the contact form on {{ $siteName }}. Reply directly to this email to respond to them —
        it's already addressed to {{ $contactMessage->email }}.
    </p>

    @include('emails.partials.details-table', ['rows' => [
        'Name' => $contactMessage->name,
        'Email' => $contactMessage->email,
        'Phone' => $contactMessage->phone,
        'Subject' => $contactMessage->subject,
        'Message' => $contactMessage->message,
        'Submitted' => $contactMessage->created_at->format('M j, Y \a\t g:ia'),
    ]])

    <p style="margin:28px 0 0;">
        <a href="{{ route('admin.contact-messages.show', $contactMessage) }}"
            style="display:inline-block; background-color:{{ $primaryColor }}; color:#ffffff; padding:11px 22px; border-radius:6px; text-decoration:none; font-weight:600; font-size:14px;">
            View in Admin Panel
        </a>
    </p>
@endsection
