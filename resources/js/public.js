import Alpine from 'alpinejs';
import lightGallery from 'lightgallery';
import lgZoom from 'lightgallery/plugins/zoom';
import lgThumbnail from 'lightgallery/plugins/thumbnail';
import 'lightgallery/css/lightgallery.css';
import 'lightgallery/css/lg-zoom.css';
import 'lightgallery/css/lg-thumbnail.css';

/*
| Public-site JavaScript entry.
| Alpine for light interactivity + lightGallery for project image galleries.
*/

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-lightgallery]').forEach((el) => {
        lightGallery(el, {
            selector: 'a',
            plugins: [lgZoom, lgThumbnail],
            speed: 400,
            download: false,
            licenseKey: '0000-0000-0000-0000',
        });
    });
});
