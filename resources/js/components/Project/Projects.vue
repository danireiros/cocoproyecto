<template>
    <div class="projects-container max-w-4xl mx-auto mt-10 p-6 bg-white border border-gray-200 rounded-lg shadow-md">
      <h2 class="text-2xl font-bold mb-6 text-gray-900">Gestión de Proyectos</h2>

      <div v-if="isAdminOrProjectManager" class="mb-4">
        <router-link
          to="/projects/create"
          class="py-2 px-4 bg-green-500 text-white font-semibold rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
        >
          Crear Proyecto
        </router-link>
      </div>

      <div v-if="projects.length" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <div v-for="project in projects" :key="project.id" class="p-4 bg-gray-100 border rounded-lg">
          <h3 class="text-xl font-semibold mb-2">{{ project.name }}</h3>
          <p class="mb-4 text-xs">{{ project.description }}</p>

          <router-link
            :to="`/projects/show/${project.id}`"
            class="mt-2 py-2 px-4 bg-cyan-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500"
          >
            Ver
          </router-link>
        </div>
      </div>
      <p v-else class="text-gray-700">No hay proyectos disponibles.</p>
    </div>
  </template>

  <script>
  export default {
    data() {
      return {
        projects: [],
        isAdmin: false,
        isProjectManager: false
      };
    },
    computed: {
      isAdminOrProjectManager() {
        return this.isAdmin || this.isProjectManager;
      }
    },
    created() {
      this.fetchProjects();
      this.checkUserRole();
    },
    methods: {
      async fetchProjects() {
        try {
          const token = localStorage.getItem('token');
          const response = await fetch('/api/projects', {
            headers: {
              'Authorization': `Bearer ${token}`
            }
          });
          if (response.ok) {
            this.projects = await response.json();
          } else {
            console.error('Error al obtener proyectos');
          }
        } catch (error) {
          console.error('Error al obtener proyectos', error);
        }
      },
      async checkUserRole() {
        try {
          const response = await fetch('/api/auth/me', {
            headers: {
              'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
          });
          if (response.ok) {
            const user = await response.json();
            this.isAdmin = user.role === 'admin';
            this.isProjectManager = user.role === 'project_manager';
          } else {
            console.error('Error al verificar el rol del usuario');
          }
        } catch (error) {
          console.error('Error al verificar el rol del usuario', error);
        }
      }
    }
  };
  </script>
