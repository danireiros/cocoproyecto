<template>
    <nav class="font-sans flex flex-col text-center sm:flex-row sm:text-left sm:justify-between py-4 px-6 bg-white shadow sm:items-baseline w-full">
      <div class="mb-2 sm:mb-0">
        <a href="#" class="text-2xl no-underline text-grey-darkest hover:text-blue-dark">Coco proyectos</a>
      </div>
      <div v-if="user.name">
        <a href="#" class="text-lg no-underline text-grey-darkest hover:text-blue-dark ml-2">
          {{ user.name }}
        </a>
        <a href="#" class="text-lg no-underline text-grey-darkest hover:text-blue-dark ml-2">
          <button @click="logout" class="btn btn-sm rounded-lg bg-gray-800 text-white p-1 text-xs">Desconectar</button>
        </a>
      </div>
      <div v-else>
        <a href="/login" class="text-lg no-underline text-grey-darkest hover:text-blue-dark ml-2">Iniciar sesión</a>
      </div>
    </nav>

    <router-view></router-view>
  </template>

  <script>
  import axios from 'axios';

  export default {
    name: 'App',
    data() {
      return {
        user: {}
      };
    },
    methods: {
      async logout() {
        try {
          await axios.post('/api/auth/logout', { token: localStorage.getItem('token') });
          localStorage.removeItem('token');
          this.user = {};
          this.$router.push('/login');
        } catch (error) {
          console.error('Logout failed', error);
        }
      },
      async fetchUser() {
        try {
          const token = localStorage.getItem('token');
          if (token) {
            const response = await axios.get('/api/auth/me', {
              headers: {
                'Authorization': `Bearer ${token}`
              }
            });
                this.user = response.data;
            } else {
                this.user = {};
            }
        } catch (error) {
            console.error('Failed to fetch user', error);
            this.user = {};
        }
      }
    },
    created() {
        this.fetchUser();
    },
    watch: {
        '$route'(to, from) {
        if (to.path === '/login' || to.path === '/register') {
            this.user = {};
        } else {
            this.fetchUser();
        }
      }
    }
  };
  </script>
