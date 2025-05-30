import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Authorization'] = `Bearer ${import.meta.env.VITE_ADMIN_TOKEN}`;
