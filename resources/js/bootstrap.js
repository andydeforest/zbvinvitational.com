import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.withCredentials = true;

const csrfToken = document.head.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

if (csrfToken) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}

const adminToken = import.meta.env.VITE_ADMIN_TOKEN;

if (adminToken) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${adminToken}`;
}
