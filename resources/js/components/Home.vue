<template>
    <div class="max-w-4xl mx-auto mt-10 p-6 bg-white border border-gray-200 rounded-lg shadow-md">
      <h1 class="text-3xl font-bold mb-4 text-gray-900">Bienvenido {{ username }}</h1>

      <div v-if="isAdmin">
        <h2 class="text-2xl font-bold mb-6 text-gray-900">Tareas y Proyectos</h2>
      </div>
      <div v-else>
        <h2 class="text-2xl font-bold mb-6 text-gray-900">Mis Tareas y Mis Proyectos</h2>
      </div>

      <div class="space-y-4">
        <div class="p-4 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
            <h3 class="text-xl font-semibold text-gray-900 mb-5">{{ isAdmin ? 'Tareas' : 'Mis Tareas' }}</h3>
            <router-link
                to="/myTasks"
                class="mt-2 py-2 px-4 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                Tareas
            </router-link>
        </div>
        <div class="p-4 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
            <h3 class="text-xl font-semibold text-gray-900 mb-5">{{ isAdmin ? 'Proyectos' : 'Mis Proyectos' }}</h3>
            <router-link
                to="/projects"
                class="mt-2 py-2 px-4 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                Proyectos
            </router-link>
        </div>
        <div v-if="isAdmin" class="p-4 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
            <h3 class="text-xl font-semibold text-gray-900 mb-5">Usuarios</h3>
            <router-link
                to="/users"
                class="mt-2 py-2 px-4 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
              Usuarios
            </router-link>
        </div>
      </div>
    </div>
  </template>

  <script>
  export default {
    data() {
      return {
        isAdmin: false,
        username: '',
        message: '',
      };
    },
    created() {
      this.checkUserRole();
    },
    methods: {
      async checkUserRole() {
        try {
          const token = localStorage.getItem('token');
          const response = await fetch('/api/auth/me', {
            headers: {
              'Authorization': `Bearer ${token}`
            }
          });
          if (response.ok) {
            const data = await response.json();
            this.isAdmin = data.role === 'admin';
            this.username = data.name;
          } else {
            console.error('Error al obtener información del usuario', await response.text());
          }
        } catch (error) {
          console.error('Error al obtener información del usuario', error);
        }
      }
    }
  };
  </script>
