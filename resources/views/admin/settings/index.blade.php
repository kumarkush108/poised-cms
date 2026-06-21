@extends('admin.layouts.app')

@section('title', 'Website Settings')

@section('content')

<div class="page-heading">

    <div class="page-heading-copy">

        <span class="page-icon">
            <i class="bi bi-gear"></i>
        </span>

        <div>

            <p class="eyebrow mb-1">Configuration</p>

            <h1 class="h3 mb-1">Website Settings</h1>

            <p class="text-muted mb-0">
                Manage your site identity, contact details, social links, SEO defaults, footer text, theme colors, and advanced scripts — all in one place.
            </p>

        </div>

    </div>

</div>

@if (session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger mt-3">
        <p class="mb-2"><strong>Please fix the following before saving:</strong></p>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    $val = fn (string $key, $default = '') => old($key, $settings[$key]->value ?? $default);
@endphp

<form method="POST" action="{{ route('admin.settings.update') }}" class="mt-4">

    @csrf
    @method('PATCH')

    <div class="d-flex align-items-center mb-3">
        <button class="btn btn-primary" type="submit">
            <i class="bi bi-save"></i>
            Save Settings
        </button>
    </div>

    {{-- General & Branding --}}
    <div class="card mb-3">

        <div class="card-body">

            <h2 class="h6 mb-3">General &amp; Branding</h2>

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Site Name</label>
                    <input type="text" name="site_name" class="form-control" value="{{ $val('site_name', 'Poised Technology') }}">
                    @error('site_name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tagline</label>
                    <input type="text" name="site_tagline" class="form-control" value="{{ $val('site_tagline') }}">
                    @error('site_tagline')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">

                    <label class="form-label">Logo</label>

                    <select name="logo_media_id" class="form-select">
                        <option value="">— None —</option>
                        @foreach ($images as $image)
                            <option value="{{ $image->id }}"
                                @selected(old('logo_media_id', $settings['logo']->media_id ?? null) == $image->id)>
                                {{ $image->filename }}
                            </option>
                        @endforeach
                    </select>

                    @error('logo_media_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror

                    @if (($settings['logo']->media ?? null))
                        <img src="{{ $settings['logo']->media->url }}"
                            alt="Current logo" class="img-fluid rounded mt-2" style="max-height: 80px;">
                    @endif

                </div>

                <div class="col-md-6">

                    <label class="form-label">Favicon</label>

                    <select name="favicon_media_id" class="form-select">
                        <option value="">— None —</option>
                        @foreach ($images as $image)
                            <option value="{{ $image->id }}"
                                @selected(old('favicon_media_id', $settings['favicon']->media_id ?? null) == $image->id)>
                                {{ $image->filename }}
                            </option>
                        @endforeach
                    </select>

                    @error('favicon_media_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror

                    @if (($settings['favicon']->media ?? null))
                        <img src="{{ $settings['favicon']->media->url }}"
                            alt="Current favicon" class="img-fluid rounded mt-2" style="max-height: 80px;">
                    @endif

                </div>

            </div>

            <p class="text-muted small mt-2 mb-0">
                Only image files from the Media Library can be selected as logo or favicon.
            </p>

        </div>

    </div>

    {{-- Contact Information --}}
    <div class="card mb-3">

        <div class="card-body">

            <h2 class="h6 mb-3">Contact Information</h2>
            <p class="text-muted small">Used across the site header, footer, and contact page.</p>

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Primary Phone</label>
                    <input type="text" name="contact_phone" class="form-control" value="{{ $val('contact_phone') }}">
                    @error('contact_phone')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Secondary Phone <span class="text-muted">(optional)</span></label>
                    <input type="text" name="contact_phone_secondary" class="form-control" value="{{ $val('contact_phone_secondary') }}">
                    @error('contact_phone_secondary')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Primary Email</label>
                    <input type="email" name="contact_email" class="form-control" value="{{ $val('contact_email') }}">
                    @error('contact_email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Secondary Email <span class="text-muted">(optional)</span></label>
                    <input type="email" name="contact_email_secondary" class="form-control" value="{{ $val('contact_email_secondary') }}">
                    @error('contact_email_secondary')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" value="{{ $val('address') }}">
                    @error('address')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Business Hours</label>
                    <input type="text" name="business_hours" class="form-control" value="{{ $val('business_hours') }}">
                    @error('business_hours')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

            </div>

        </div>

    </div>

    {{-- Social Media Links --}}
    <div class="card mb-3">

        <div class="card-body">

            <h2 class="h6 mb-3">Social Media Links</h2>
            <p class="text-muted small">Leave blank to hide an icon from the footer.</p>

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label"><i class="fab fa-facebook-f me-1"></i> Facebook URL</label>
                    <input type="url" name="facebook_url" class="form-control" placeholder="https://facebook.com/yourpage" value="{{ $val('facebook_url') }}">
                    @error('facebook_url')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label"><i class="fab fa-twitter me-1"></i> Twitter / X URL</label>
                    <input type="url" name="twitter_url" class="form-control" placeholder="https://twitter.com/yourhandle" value="{{ $val('twitter_url') }}">
                    @error('twitter_url')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label"><i class="fab fa-linkedin-in me-1"></i> LinkedIn URL</label>
                    <input type="url" name="linkedin_url" class="form-control" placeholder="https://linkedin.com/company/yourcompany" value="{{ $val('linkedin_url') }}">
                    @error('linkedin_url')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label"><i class="fab fa-instagram me-1"></i> Instagram URL</label>
                    <input type="url" name="instagram_url" class="form-control" placeholder="https://instagram.com/yourhandle" value="{{ $val('instagram_url') }}">
                    @error('instagram_url')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label"><i class="fab fa-youtube me-1"></i> YouTube URL</label>
                    <input type="url" name="youtube_url" class="form-control" placeholder="https://youtube.com/@yourchannel" value="{{ $val('youtube_url') }}">
                    @error('youtube_url')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

            </div>

        </div>

    </div>

    {{-- SEO Defaults --}}
    <div class="card mb-3">

        <div class="card-body">

            <h2 class="h6 mb-3">SEO Defaults</h2>
            <p class="text-muted small">Used as the fallback for any page that doesn't set its own SEO fields.</p>

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Default Meta Title</label>
                    <input type="text" name="default_meta_title" class="form-control" value="{{ $val('default_meta_title') }}">
                    @error('default_meta_title')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Default Meta Keywords</label>
                    <input type="text" name="default_meta_keywords" class="form-control" value="{{ $val('default_meta_keywords') }}">
                    @error('default_meta_keywords')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Default Meta Description</label>
                    <textarea name="default_meta_description" class="form-control" rows="2">{{ $val('default_meta_description') }}</textarea>
                    @error('default_meta_description')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

            </div>

        </div>

    </div>

    {{-- Footer --}}
    <div class="card mb-3">

        <div class="card-body">

            <h2 class="h6 mb-3">Footer</h2>

            <div class="row g-3">

                <div class="col-md-8">
                    <label class="form-label">Copyright Text</label>
                    <div class="input-group">
                        <span class="input-group-text">&copy;</span>
                        <input type="text" name="copyright_text" class="form-control" value="{{ $val('copyright_text') }}">
                    </div>
                    @error('copyright_text')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Rendered as &copy; {{ now()->year }} {{ $val('copyright_text', 'Poised. All Rights Reserved.') }}</div>
                </div>

            </div>

        </div>

    </div>

    {{-- Theme Colors --}}
    <div class="card mb-3">

        <div class="card-body">

            <h2 class="h6 mb-3">Theme Colors</h2>

            <div class="row g-3">

                @foreach ([
                    'primary_color' => 'Primary Color',
                    'secondary_color' => 'Secondary Color',
                    'accent_color' => 'Accent Color',
                    'success_color' => 'Success Color',
                    'warning_color' => 'Warning Color',
                    'danger_color' => 'Danger Color',
                    'dark_color' => 'Dark Color',
                    'light_color' => 'Light Color',
                    'header_background' => 'Header Background',
                    'footer_background' => 'Footer Background',
                    'button_color' => 'Button Color',
                    'button_hover_color' => 'Button Hover Color',
                    'text_color' => 'Body Text Color',
                    'link_color' => 'Link Color',
                ] as $key => $label)

                    <div class="col-md-4">

                        <label class="form-label">{{ $label }}</label>

                        <div class="input-group js-color-field">

                            <input type="color"
                                class="form-control form-control-color flex-grow-0 js-color-swatch"
                                value="{{ $val($key, '#000000') }}"
                                title="{{ $label }}">

                            <input type="text" name="{{ $key }}" class="form-control js-color-hex"
                                value="{{ $val($key) }}"
                                placeholder="#000000" maxlength="7">

                        </div>

                        @error($key)
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror

                    </div>

                @endforeach

            </div>

        </div>

    </div>

    {{-- Advanced: Custom Scripts --}}
    <div class="card mb-3 border-warning-subtle">

        <div class="card-body">

            <h2 class="h6 mb-1">
                <i class="bi bi-exclamation-triangle text-warning me-1"></i>
                Advanced: Custom Scripts
            </h2>

            <p class="text-muted small">
                For analytics, tracking pixels, or chat widgets (e.g. Google Analytics, Meta Pixel). This code runs on
                <strong>every page, for every visitor</strong>, exactly as written — only paste code you trust.
            </p>

            <div class="row g-3">

                <div class="col-12">
                    <label class="form-label">Header Scripts <span class="text-muted">(injected just before <code>&lt;/head&gt;</code>)</span></label>
                    <textarea name="header_scripts" class="form-control font-monospace" rows="4" spellcheck="false">{{ $val('header_scripts') }}</textarea>
                    @error('header_scripts')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Footer Scripts <span class="text-muted">(injected just before <code>&lt;/body&gt;</code>)</span></label>
                    <textarea name="footer_scripts" class="form-control font-monospace" rows="4" spellcheck="false">{{ $val('footer_scripts') }}</textarea>
                    @error('footer_scripts')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

            </div>

        </div>

    </div>

    <button class="btn btn-primary" type="submit">
        <i class="bi bi-save"></i>
        Save Settings
    </button>

</form>

@push('scripts')
<script>
    (function () {
        document.querySelectorAll('.js-color-field').forEach(function (field) {
            const swatch = field.querySelector('.js-color-swatch');
            const hex = field.querySelector('.js-color-hex');

            swatch.addEventListener('input', function () {
                hex.value = swatch.value;
            });

            hex.addEventListener('input', function () {
                if (/^#[0-9A-Fa-f]{6}$/.test(hex.value)) {
                    swatch.value = hex.value;
                }
            });
        });
    })();
</script>
@endpush

@endsection
