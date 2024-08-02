<template>
    <div class="projects-container max-w-4xl mx-auto mt-2 p-6 bg-white border border-gray-200 rounded-lg shadow-md">
      <h2 class="text-2xl font-bold mb-6 text-gray-900">Gestión de Proyectos</h2>

      <!-- Button to create new project, visible to admin or project manager -->
      <div v-if="isAdminOrProjectManager" class="mb-4">
        <router-link
          to="/projects/create"
          class="py-2 px-4 bg-green-500 text-white font-semibold rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
        >
          Crear Proyecto
        </router-link>
      </div>

      <!-- Table for displaying projects -->
      <div v-if="projects.length" class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Nombre
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Descripción
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Acción
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="project in projects" :key="project.id">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ project.name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ project.description }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <router-link
                  :to="`/projects/show/${project.id}`"
                  class="py-2 px-4 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  Ver
                </router-link>
              </td>
            </tr>
          </tbody>
        </table>
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

  <style scoped>
  /* Add any additional styles here if needed */
  table {
    width: 100%;
    border-collapse: collapse;
  }

  thead {
    background-color: #f9fafb;
  }

  th, td {
    padding: 0.75rem;
    text-align: left;
  }

  tr:nth-child(even) {
    background-color: #f9fafb;
  }
  </style>
