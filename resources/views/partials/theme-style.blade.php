@php
    $themeSettings = $themeSettings ?? collect();

    $primary = \App\Cms\Content::settingValue($themeSettings, 'primary_color', '#0d6efd');
    $secondary = \App\Cms\Content::settingValue($themeSettings, 'secondary_color', '#6c757d');
    $accent = \App\Cms\Content::settingValue($themeSettings, 'accent_color', '#fd7e14');
    $success = \App\Cms\Content::settingValue($themeSettings, 'success_color', '#198754');
    $warning = \App\Cms\Content::settingValue($themeSettings, 'warning_color', '#ffc107');
    $danger = \App\Cms\Content::settingValue($themeSettings, 'danger_color', '#dc3545');
    $dark = \App\Cms\Content::settingValue($themeSettings, 'dark_color', '#212529');
    $light = \App\Cms\Content::settingValue($themeSettings, 'light_color', '#f8f9fa');
    $headerBackground = \App\Cms\Content::settingValue($themeSettings, 'header_background', '#ffffff');
    $footerBackground = \App\Cms\Content::settingValue($themeSettings, 'footer_background', '#212529');
    $buttonColor = \App\Cms\Content::settingValue($themeSettings, 'button_color', '#0d6efd');
    $buttonHoverColor = \App\Cms\Content::settingValue($themeSettings, 'button_hover_color', '#0b5ed7');
    $textColor = \App\Cms\Content::settingValue($themeSettings, 'text_color', '#212529');
    $linkColor = \App\Cms\Content::settingValue($themeSettings, 'link_color', '#0d6efd');
@endphp
<style id="cms-theme-overrides">
    :root {
        --bs-primary: {{ $primary }};
        --bs-secondary: {{ $secondary }};
        --bs-success: {{ $success }};
        --bs-warning: {{ $warning }};
        --bs-danger: {{ $danger }};
        --bs-dark: {{ $dark }};
        --bs-light: {{ $light }};
        --cms-accent-color: {{ $accent }};
    }
    .bg-primary { background-color: {{ $primary }} !important; }
    .text-primary { color: {{ $primary }} !important; }
    .border-primary { border-color: {{ $primary }} !important; }
    .bg-secondary { background-color: {{ $secondary }} !important; }
    .text-secondary { color: {{ $secondary }} !important; }
    .border-secondary { border-color: {{ $secondary }} !important; }
    .bg-success { background-color: {{ $success }} !important; }
    .text-success { color: {{ $success }} !important; }
    .bg-warning { background-color: {{ $warning }} !important; }
    .text-warning { color: {{ $warning }} !important; }
    .bg-danger { background-color: {{ $danger }} !important; }
    .text-danger { color: {{ $danger }} !important; }
    .bg-dark { background-color: {{ $dark }} !important; }
    .text-dark { color: {{ $dark }} !important; }
    .bg-light { background-color: {{ $light }} !important; }
    .text-light { color: {{ $light }} !important; }

    /* Header / navbar background */
    .navbar { background-color: {{ $headerBackground }} !important; }

    /* Footer + copyright bar background */
    .footer, .copyright { background-color: {{ $footerBackground }} !important; }

    /* Buttons — distinct from the general "primary" accent so a site can
       have a brand-blue primary color but a differently-colored CTA button */
    .btn-primary { background-color: {{ $buttonColor }} !important; border-color: {{ $buttonColor }} !important; }
    .btn-primary:hover, .btn-primary:focus { background-color: {{ $buttonHoverColor }} !important; border-color: {{ $buttonHoverColor }} !important; }

    /* Body text + links */
    body { color: {{ $textColor }}; }
    a { color: {{ $linkColor }}; }
</style>
