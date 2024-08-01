import './bootstrap';
import '../scss/app.scss';
import '../css/app.css';

import { createApp } from 'vue';
import router from './router';
import App from './components/App.vue';
import axios from 'axios';

// Configurar Axios
axios.defaults.baseURL = '';
axios.interceptors.request.use(config => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

createApp(App).use(router).mount('#app');
