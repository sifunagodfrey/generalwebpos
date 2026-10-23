import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// ---------------
// CSRF token - read from meta tag set in every layout's <head>
// ---------------
const csrfMeta = document.querySelector('meta[name="csrf-token"]');
if (csrfMeta) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfMeta.content;
}
