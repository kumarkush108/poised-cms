/**
 * Progressive-enhancement AJAX submission for public forms opted in via the
 * "js-ajax-form" class (Contact, Appointment, Product Inquiry). Disables the
 * submit button and shows a loading state immediately, posts via fetch,
 * and renders the result inline in a ".js-form-feedback" container inside
 * the same form — no page reload, so the result is always visible without
 * scrolling regardless of where the form sits on the page.
 *
 * Pairs with ContactMessageController::store() (which returns JSON when the
 * request expects it) and PreventSpamSubmissions (same JSON branch for its
 * silent-drop path). If JS is disabled, these forms still submit as plain
 * full-page POSTs and the existing server-rendered session-flash success
 * message still works — this script only intercepts when it can run.
 */
(function () {
    'use strict';

    function escapeHtml(value) {
        var div = document.createElement('div');
        div.textContent = value;
        return div.innerHTML;
    }

    function showFeedback(container, type, html) {
        if (!container) return;
        container.innerHTML = '<div class="alert alert-' + type + ' mt-3" role="alert">' + html + '</div>';
    }

    function clearFeedback(container) {
        if (container) container.innerHTML = '';
    }

    function setLoading(button, loading) {
        if (!button) return;

        if (loading) {
            button.dataset.originalText = button.dataset.originalText || button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Sending…';
        } else {
            button.disabled = false;
            if (button.dataset.originalText) {
                button.innerHTML = button.dataset.originalText;
            }
        }
    }

    function resetRecaptcha(form) {
        if (form.querySelector('.g-recaptcha') && window.grecaptcha && typeof window.grecaptcha.reset === 'function') {
            try {
                window.grecaptcha.reset();
            } catch (e) {
                // Widget not yet rendered (e.g. script still loading) — nothing to reset.
            }
        }
    }

    function refreshSpamProtectionToken(form, nextToken) {
        var tokenField = form.querySelector('input[name="form_rendered_at"]');
        if (tokenField && nextToken) {
            tokenField.value = nextToken;
        }
    }

    function renderValidationErrors(feedback, errors) {
        var messages = [];
        Object.keys(errors).forEach(function (field) {
            errors[field].forEach(function (message) {
                messages.push(message);
            });
        });

        var list = '<ul class="mb-0">' + messages.map(function (message) {
            return '<li>' + escapeHtml(message) + '</li>';
        }).join('') + '</ul>';

        showFeedback(feedback, 'danger', list);
    }

    function handleSubmit(event) {
        event.preventDefault();

        var form = event.target;
        var button = form.querySelector('button[type="submit"]');
        var feedback = form.querySelector('.js-form-feedback');

        clearFeedback(feedback);
        setLoading(button, true);

        fetch(form.getAttribute('action'), {
            method: 'POST',
            body: new FormData(form),
            headers: { Accept: 'application/json' },
            credentials: 'same-origin',
        })
            .then(function (response) {
                if (response.status === 419) {
                    return { status: response.status, data: null };
                }

                return response.json().then(function (data) {
                    return { status: response.status, data: data };
                });
            })
            .then(function (result) {
                if (result.status >= 200 && result.status < 300 && result.data && result.data.success) {
                    showFeedback(feedback, 'success', escapeHtml(result.data.message || 'Thank you — your message has been sent.'));
                    form.reset();
                    refreshSpamProtectionToken(form, result.data.next_token);
                } else if (result.status === 422 && result.data && result.data.errors) {
                    renderValidationErrors(feedback, result.data.errors);
                } else if (result.status === 429) {
                    showFeedback(feedback, 'warning', 'You are submitting too quickly — please wait a moment and try again.');
                } else if (result.status === 419) {
                    showFeedback(feedback, 'warning', 'Your session has expired — please refresh the page and try again.');
                } else {
                    showFeedback(feedback, 'danger', 'Something went wrong. Please try again in a moment.');
                }
            })
            .catch(function () {
                showFeedback(feedback, 'danger', 'Could not reach the server. Please check your connection and try again.');
            })
            .finally(function () {
                setLoading(button, false);
                resetRecaptcha(form);
            });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('form.js-ajax-form').forEach(function (form) {
            form.addEventListener('submit', handleSubmit);
        });
    });
})();
