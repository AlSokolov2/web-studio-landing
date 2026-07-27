/**
 * Web Studio — navigation.js
 * Mobile hamburger menu toggle.
 *
 * @since 1.0.0
 * @package Web_Studio
 */

document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    var toggle = document.querySelector('.header__toggle');
    var nav = document.getElementById('primary-nav');

    if (!toggle || !nav) {
        return;
    }

    // Explicitly link toggle to nav for assistive technology.
    toggle.setAttribute('aria-controls', 'primary-nav');

    /**
     * Opens or closes the mobile menu.
     *
     * @param {boolean} open Whether to open (true) or close (false).
     */
    function setMenuState(open) {
        if (open) {
            nav.classList.add('header__nav--open');
        } else {
            nav.classList.remove('header__nav--open');
        }
        toggle.setAttribute('aria-expanded', open.toString());
    }

    toggle.addEventListener('click', function () {
        var isOpen = !nav.classList.contains('header__nav--open');
        setMenuState(isOpen);

        // If opening, move focus to the first link.
        if (isOpen) {
            var firstLink = nav.querySelector('a');
            if (firstLink) {
                firstLink.focus();
            }
        }
    });

    // Close menu when a nav link is clicked, return focus to toggle.
    nav.addEventListener('click', function (event) {
        var link = event.target.closest('a');
        if (link) {
            setMenuState(false);
            toggle.focus();
        }
    });

    // Close menu on Escape key press.
    document.addEventListener('keydown', function (event) {
        if ('Escape' === event.key && nav.classList.contains('header__nav--open')) {
            setMenuState(false);
            toggle.focus();
        }
    });

    // Reset menu state when switching from mobile to desktop layout.
    var mql = window.matchMedia('(min-width: 768px)');
    mql.addEventListener('change', function (e) {
        if (e.matches) {
            setMenuState(false);
        }
    });
});
