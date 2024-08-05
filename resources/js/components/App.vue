<template>
    <div class="flex flex-col min-h-screen">
      <!-- Navigation -->
      <nav class="font-sans flex flex-col sm:flex-row text-center sm:text-left sm:justify-between py-4 px-6 bg-white shadow-md w-full">
        <!-- Logo and Title -->
        <div class="flex items-center justify-between mb-4 sm:mb-0">
          <a href="/" class="text-2xl font-bold text-gray-900">Coco Proyectos</a>
          <button @click="toggleMenu" class="sm:hidden text-gray-900 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
          </button>
        </div>

        <!-- Navigation Links -->
        <div :class="{'block': isMenuOpen, 'hidden': !isMenuOpen, 'sm:flex': true, 'space-y-4 sm:space-y-0': true}">
          <div v-if="user.name" class="flex flex-col sm:flex-row sm:space-x-4">
            <router-link
              to="/"
              class="py-2 px-4 text-dark font-semibold rounded-md hover:bg-blue-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Inicio
            </router-link>
            <router-link
              to="/myTasks"
              class="py-2 px-4 text-dark font-semibold rounded-md hover:bg-blue-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Tareas
            </router-link>
            <router-link
              to="/projects"
              class="py-2 px-4 text-dark font-semibold rounded-md hover:bg-blue-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Proyectos
            </router-link>
            <router-link v-if="user.role === 'admin'"
              to="/users"
              class="py-2 px-4 text-dark font-semibold rounded-md hover:bg-blue-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Usuarios
            </router-link>
            <button
              @click="logout"
              class="py-2 px-4 bg-red-100 text-dark font-semibold rounded-md hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
            >
              Desconectar {{ user.name }}
            </button>
          </div>
          <div v-else class="flex flex-col sm:flex-row sm:space-x-4">
            <router-link
              to="/register"
              class="py-2 px-4 border border-gray-300 text-gray-900 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Registrar
            </router-link>
            <router-link
              to="/login"
              class="py-2 px-4 border border-gray-300 text-gray-900 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Login
            </router-link>
          </div>
        </div>
      </nav>

      <!-- Main Content -->
      <div class="flex-grow p-4">

        <div v-if="message" class="p-4 mt-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            {{ message }}
        </div>

        <router-view></router-view>
      </div>

      <!-- Footer -->
      <footer class="bg-white p-4 mt-auto text-center text-gray-600">
        Daniel Barreiros
      </footer>
    </div>
  </template>

  <script>
  export default {
    name: 'App',
    data() {
      return {
        user: {},
        isAuthenticated: false,
        isMenuOpen: false // For handling mobile menu toggle
      };
    },
    methods: {
      async logout() {
        try {
          const token = localStorage.getItem('token');
          const response = await fetch('/api/auth/logout', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({ token })
          });
          if (!response.ok) {
            throw new Error('Logout failed');
          }
          localStorage.removeItem('token');
          this.user = {};
          this.isAuthenticated = false;
          this.$router.push('/login');
        } catch (error) {
          console.error('Logout failed', error);
        }
      },
      async fetchUser() {
        try {
          const token = localStorage.getItem('token');
          if (token) {
            const response = await fetch('/api/auth/me', {
              headers: {
                'Authorization': `Bearer ${token}`
              }
            });
            if (!response.ok) {
              throw new Error('Failed to fetch user');
            }
            const data = await response.json();
            this.user = data;
            this.isAuthenticated = true;
          } else {
            this.user = {};
            this.isAuthenticated = false;
          }
        } catch (error) {
          console.error('Failed to fetch user', error);
          this.user = {};
          this.isAuthenticated = false;
        }
      },
      toggleMenu() {
        this.isMenuOpen = !this.isMenuOpen;
      }
    },
    created() {
      this.fetchUser();
    },
    watch: {
      '$route'(to) {
        if (to.path === '/login' || to.path === '/register') {
          this.user = {};
          this.isAuthenticated = false;
        } else {
          this.fetchUser();
        }
      }
    }
  };
  </script>
