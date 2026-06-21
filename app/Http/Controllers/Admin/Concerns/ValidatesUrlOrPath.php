<?php

namespace App\Http\Controllers\Admin\Concerns;

trait ValidatesUrlOrPath
{
    /**
     * Accepts an absolute URL, a site-relative path ("/contact"), or a
     * mailto:/tel: link. Laravel's built-in "url" rule rejects the latter
     * two, which would block saving a link to an internal page/resource by
     * path rather than a fully-qualified domain.
     */
    protected function urlOrPathRule(): \Closure
    {
        return function (string $attribute, $value, \Closure $fail) {
            if (filter_var($value, FILTER_VALIDATE_URL) !== false) {
                return;
            }

            if (preg_match('#^(/[^\s]*|mailto:.+|tel:.+)$#', $value)) {
                return;
            }

            $fail('The :attribute must be a valid URL, a site-relative path, or a mailto:/tel: link.');
        };
    }
}
