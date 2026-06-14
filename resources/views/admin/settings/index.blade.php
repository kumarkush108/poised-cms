@extends('admin.layouts.app')

@section('title', 'Theme Settings')

@section('content')

<div class="page-heading">

    <div class="page-heading-copy">

        <span class="page-icon">
            <i class="bi bi-palette"></i>
        </span>

        <div>

            <p class="eyebrow mb-1">Configuration</p>

            <h1 class="h3 mb-1">Theme Settings</h1>

            <p class="text-muted mb-0">
                Manage site colors, logo, and favicon.
            </p>

        </div>

    </div>

</div>

@if (session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
@endif

<form method="POST" action="{{ route('admin.settings.update') }}" class="mt-4">

    @csrf
    @method('PATCH')

    <div class="card mb-3">

        <div class="card-body">

            <h2 class="h6 mb-3">Colors</h2>

            <div class="row g-3">

                @foreach ([
                    'primary_color' => 'Primary Color',
                    'secondary_color' => 'Secondary Color',
                    'accent_color' => 'Accent Color',
                    'header_background' => 'Header Background',
                    'footer_background' => 'Footer Background',
                    'button_color' => 'Button Color',
                    'button_hover_color' => 'Button Hover Color',
                    'text_color' => 'Text Color',
                    'link_color' => 'Link Color',
                ] as $key => $label)

                    <div class="col-md-4">

                        <label class="form-label">{{ $label }}</label>

                        <div class="input-group">

                            <input type="color" name="{{ $key }}"
                                class="form-control form-control-color flex-grow-0"
                                value="{{ old($key, $settings[$key]->value ?? '#000000') }}"
                                title="{{ $label }}">

                            <input type="text" class="form-control"
                                value="{{ old($key, $settings[$key]->value ?? '') }}"
                                placeholder="#000000" readonly>

                        </div>

                        @error($key)
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror

                    </div>

                @endforeach

            </div>

        </div>

    </div>

    <div class="card mb-3">

        <div class="card-body">

            <h2 class="h6 mb-3">Branding</h2>

            <div class="row g-3">

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
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk($settings['logo']->media->disk)->url($settings['logo']->media->path) }}"
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
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk($settings['favicon']->media->disk)->url($settings['favicon']->media->path) }}"
                            alt="Current favicon" class="img-fluid rounded mt-2" style="max-height: 80px;">
                    @endif

                </div>

            </div>

            <p class="text-muted small mt-2 mb-0">
                Only image files from the Media Library can be selected as logo or favicon.
            </p>

        </div>

    </div>

    <button class="btn btn-primary" type="submit">
        <i class="bi bi-save"></i>
        Save Settings
    </button>

</form>

@endsection
