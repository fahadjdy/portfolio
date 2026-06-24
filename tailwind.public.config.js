import base from './tailwind.config.js';

/**
 * Public-site Tailwind config.
 *
 * Same theme as the admin build, but `content` is limited to Blade templates
 * (and compiled views) so the public stylesheet never generates utility
 * classes that only the admin/Vue bundle uses. Pulled in via a `@config`
 * directive at the top of resources/css/public.css; the admin bundle keeps
 * using the default tailwind.config.js.
 *
 * @type {import('tailwindcss').Config}
 */
export default {
    ...base,
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
};
