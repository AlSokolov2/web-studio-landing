/**
 * Web Studio — main.js
 * AJAX feedback form, smooth scroll, scroll animations.
 *
 * Dependencies: none (vanilla JS)
 * Requires window.wsData injected by PHP:
 *   window.wsData = { ajaxUrl: '...', nonce: '...' };
 *
 * @since 1.0.0
 * @package Web_Studio
 */

document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    // Read header height from the CSS custom property so it stays in sync.
    var style = getComputedStyle(document.documentElement);
    var HEADER_HEIGHT = parseInt(style.getPropertyValue('--header-height').trim(), 10) || 64;
    var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // -------------------------------------------------------------------------
    // 1. Smooth scroll for anchor links
    // -------------------------------------------------------------------------
    var anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(function (link) {
        link.addEventListener('click', function (event) {
            var href = link.getAttribute('href');
            if (href === '#' || !href) {
                return;
            }

            // Use getElementById for safe ID matching (avoids CSS selector injection).
            var target = document.getElementById(href.substring(1));
            if (!target) {
                return;
            }

            event.preventDefault();

            // Account for fixed header AND admin bar if visible.
            var adminBar = document.getElementById('wpadminbar');
            var adminBarHeight = (adminBar && adminBar.getBoundingClientRect().bottom > 0)
                ? adminBar.offsetHeight
                : 0;
            var top = target.getBoundingClientRect().top + window.pageYOffset
                - HEADER_HEIGHT - adminBarHeight;

            window.scrollTo({
                top: top,
                behavior: prefersReducedMotion ? 'auto' : 'smooth',
            });

            // Move focus to the target for keyboard users.
            target.setAttribute('tabindex', '-1');
            target.focus({ preventScroll: true });
            target.addEventListener('blur', function () {
                target.removeAttribute('tabindex');
            }, { once: true });
        });
    });

    // -------------------------------------------------------------------------
    // 2. AJAX feedback form
    // -------------------------------------------------------------------------
    var form = document.getElementById('feedback-form');

    // Only set up form handling if the form exists on this page.
    if (form) {
        setupForm(form);
    }

    /**
     * Sets up all form-related event listeners and handlers.
     *
     * @param {HTMLFormElement} formEl The form element.
     */
    function setupForm(formEl) {
        // Guard against missing wsData global (e.g., inline script blocked by CSP).
        if (typeof window.wsData === 'undefined') {
            if (window.console && window.console.warn) {
                window.console.warn('Web Studio: window.wsData is missing. Form will not work.');
            }
            return;
        }

        var statusContainer = document.getElementById('form-status');
        var submitButton = formEl.querySelector('button[type="submit"]');

        // Guard against missing DOM elements.
        if (!statusContainer || !submitButton) {
            return;
        }

        /**
         * Validates a single form field.
         *
         * NOTE: Email regex is a client-side hint only; server-side validation
         * via is_email() is authoritative.
         *
         * @param {HTMLElement} field - The input or textarea to validate.
         * @return {boolean} True if valid, false otherwise.
         */
        function validateField(field) {
            var group = field.closest('.form__group');
            var errorEl = group ? group.querySelector('.form__error') : null;
            var message = '';

            if (field.hasAttribute('required') && !field.value.trim()) {
                message = (wsData.i18n && wsData.i18n.required) || 'Это поле обязательно.';
            } else if (field.type === 'email' && field.value.trim()) {
                // Simple client-side hint — server is authoritative.
                var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(field.value.trim())) {
                    message = (wsData.i18n && wsData.i18n.emailInvalid) || 'Введите корректный email.';
                }
            }

            field.setAttribute('aria-invalid', message ? 'true' : 'false');
            if (errorEl) {
                errorEl.textContent = message;
                errorEl.style.display = message ? 'block' : 'none';
            }
            return !message;
        }

        // Real-time validation on blur.
        var fields = formEl.querySelectorAll('input, textarea, select');
        fields.forEach(function (field) {
            field.addEventListener('blur', function () {
                validateField(field);
            });

            // Clear error on input.
            field.addEventListener('input', function () {
                if ('true' === field.getAttribute('aria-invalid')) {
                    validateField(field);
                }
            });
        });

        // Form submission.
        formEl.addEventListener('submit', function (event) {
            event.preventDefault();

            // Run all validations.
            var isValid = true;
            fields.forEach(function (field) {
                if (!validateField(field)) {
                    isValid = false;
                }
            });

            if (!isValid) {
                return;
            }

            // Disable button during request.
            submitButton.disabled = true;
            submitButton.textContent = (wsData.i18n && wsData.i18n.sending) || 'Отправка...';
            statusContainer.textContent = '';
            statusContainer.className = 'form__status';

            // Build FormData.
            var data = new FormData(formEl);
            data.append('nonce', wsData.nonce);
            data.append('action', 'submit_feedback');

            fetch(wsData.ajaxUrl, {
                method: 'POST',
                body: data,
            })
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error('Server error: ' + response.status);
                    }
                    return response.json();
                })
                .then(function (result) {
                    if (result.success) {
                        statusContainer.textContent = result.data.message;
                        statusContainer.className = 'form__status form__status--success';
                        formEl.reset();

                        // Reset aria-invalid on all fields after successful submission.
                        fields.forEach(function (f) {
                            f.setAttribute('aria-invalid', 'false');
                        });
                    } else {
                        // Handle per-field errors with type guard.
                        if (result.data
                            && result.data.errors
                            && typeof result.data.errors === 'object'
                            && !Array.isArray(result.data.errors)
                        ) {
                            Object.keys(result.data.errors).forEach(function (fieldName) {
                                var field = formEl.elements[fieldName];
                                if (field) {
                                    var group = field.closest('.form__group');
                                    var errorEl = group ? group.querySelector('.form__error') : null;
                                    field.setAttribute('aria-invalid', 'true');
                                    if (errorEl) {
                                        errorEl.textContent = result.data.errors[fieldName];
                                        errorEl.style.display = 'block';
                                    }
                                }
                            });
                        }
                        statusContainer.textContent = result.data.message || 'Ошибка отправки.';
                        statusContainer.className = 'form__status form__status--error';
                    }
                })
                .catch(function () {
                    statusContainer.textContent = wsData.i18n
                        ? wsData.i18n.networkError
                        : 'Ошибка соединения. Попробуйте позже.';
                    statusContainer.className = 'form__status form__status--error';
                })
                .finally(function () {
                    submitButton.disabled = false;
                    submitButton.textContent = wsData.i18n
                        ? wsData.i18n.submit
                        : 'Отправить';
                });
        });
    }

    // -------------------------------------------------------------------------
    // 3. Scroll animations (Intersection Observer)
    // -------------------------------------------------------------------------
    var animatedElements = document.querySelectorAll(
        '.portfolio__card, .about__feature, .contacts__item, .section-title'
    );

    if ('IntersectionObserver' in window) {
        // Add animate class after DOM is ready.
        animatedElements.forEach(function (el) {
            el.classList.add('animate');
        });

        var observer = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate--visible');
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.15, rootMargin: '0px 0px -24px 0px' }
        );

        animatedElements.forEach(function (el) {
            observer.observe(el);
        });
    }
});
