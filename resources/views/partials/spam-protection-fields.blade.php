{{--
    Honeypot + timing-based spam protection for public forms — paired with
    App\Http\Middleware\PreventSpamSubmissions, applied via the
    "spam-protection" route middleware alias. Include this inside every
    public-facing <form> that accepts unauthenticated submissions
    (Contact, Appointment, Product Inquiry).
--}}
<div style="position:absolute; left:-9999px; top:-9999px; height:0; width:0; overflow:hidden;" aria-hidden="true">
    <label for="website">Leave this field blank</label>
    <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
</div>
<input type="hidden" name="form_rendered_at" value="{{ encrypt(time()) }}">
