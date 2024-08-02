<template>
    <div class="flex flex-col min-h-screen">
      <!-- Navigation -->
      <nav class="font-sans flex flex-col text-center sm:flex-row sm:text-left sm:justify-between py-4 px-6 bg-white shadow sm:items-baseline w-full">
        <div class="mb-2 sm:mb-0">
          <a href="/" class="text-2xl font-bold mb-6 text-gray-900">Coco proyectos</a>
        </div>
        <div v-if="user.name" class="flex-gap">
            <router-link
                to="/"
                class="mt-2 py-2 px-4 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                Tareas
              </router-link>
              <router-link
                to="/projects"
                class="mt-2 py-2 px-4 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                Proyectos
              </router-link>
              <router-link v-if="user.role == 'admin'"
                to="/projects"
                class="mt-2 py-2 px-4 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                Usuarios
              </router-link>
            <button
                href="#"
                class="mt-2 py-2 px-4 font-semibold rounded-md"
            >
                {{ user.name }}
            </button>

            <button
                @click="logout"
                class="mt-2 py-2 px-4 font-semibold rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
                Desconectar
            </button>
        </div>
        <div v-else class="flex-gap">
          <router-link
            to="/register"
            class="mt-2 py-2 px-4 font-semibold rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Registrar
          </router-link>
          <router-link
            to="/login"
            class="mt-2 py-2 px-4 font-semibold rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Login
          </router-link>
        </div>
      </nav>

      <!-- Main Content -->
      <div class="flex-grow p-4">
        <router-view></router-view>
      </div>

      <!-- Footer -->
      <footer class="bg-white p-4 mt-auto">
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
        isAuthenticated: false
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
            body: JSON.stringify({ token: token })
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
          console.error('Fallo al obtener usuario', error);
          this.user = {};
          this.isAuthenticated = false;
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
          this.isAuthenticated = false;
        } else {
          this.fetchUser();
        }
      }
    }
  };
  </script>
