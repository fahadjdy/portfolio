import Alpine from 'alpinejs';

/*
| Public-site JavaScript entry.
|
| Alpine powers light interactivity on every page (nav, flash, forms), so it
| ships in the main bundle. lightGallery (+ its plugins and CSS) is heavy and
| only needed on project detail pages, so it is loaded lazily via dynamic
| import() — Vite splits it into its own chunk that is fetched only when a
| [data-lightgallery] element is actually present. This keeps unused JS/CSS
| off the home page, blog, services, etc.
*/

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const galleries = document.querySelectorAll('[data-lightgallery]');
    if (!galleries.length) return;

    Promise.all([
        import('lightgallery'),
        import('lightgallery/plugins/zoom'),
        import('lightgallery/plugins/thumbnail'),
        import('lightgallery/css/lightgallery.css'),
        import('lightgallery/css/lg-zoom.css'),
        import('lightgallery/css/lg-thumbnail.css'),
    ]).then(([{ default: lightGallery }, { default: lgZoom }, { default: lgThumbnail }]) => {
        galleries.forEach((el) => {
            lightGallery(el, {
                selector: 'a',
                plugins: [lgZoom, lgThumbnail],
                speed: 400,
                download: false,
                licenseKey: '0000-0000-0000-0000',
            });
        });
    });
});
