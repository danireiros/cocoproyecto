<template>
    <div class="home-container p-4">
      <div v-if="statusMessage" class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
        {{ statusMessage }}
      </div>
      <div v-if="errorMessage" class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
        {{ errorMessage }}
      </div>

      <div class="max-w-4xl mx-auto mt-2 p-6 bg-white border border-gray-200 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6 text-gray-900">Tareas</h1>

        <div v-if="Object.keys(tasks).length">
          <table class="min-w-full divide-y divide-gray-200 bg-white rounded-lg overflow-hidden shadow-md">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyecto</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plazos</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entrega</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <template v-for="(tasksByProject, projectName) in tasks" :key="projectName">
                <tr v-for="task in tasksByProject" :key="task.id">
                  <td class="px-4 py-2 text-sm text-gray-500">{{ task.project_name }}</td>
                  <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ task.title }}</td>
                  <td class="px-4 py-2 text-sm text-gray-500">{{ task.description }}</td>
                  <td class="px-4 py-2 text-sm text-gray-500">{{ task.start_date }} - {{ task.due_date }}</td>
                  <td class="px-4 py-2 text-sm" :class="{'text-green-500': task.completed, 'text-red-500': !task.completed}">
                    {{ task.completed ? 'Cerrada' : 'En curso' }}
                  </td>
                  <td class="px-4 py-2 text-sm" :class="{'text-green-500': task.delivered, 'text-red-500': !task.delivered}">
                    {{ task.delivered ? 'Entregada' : 'Pendiente de entregar' }}
                  </td>
                  <td v-if="!task.completed" class="px-4 py-2 text-sm text-gray-500">
                    <router-link
                      :to="`/projects/tasks/${task.id}/submit`"
                      class="py-2 px-4 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                      Entregar
                    </router-link>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>

        <p v-else class="text-gray-700 mt-4">No tienes tareas asignadas.</p>
      </div>
    </div>
  </template>

  <script>
  export default {
    data() {
      return {
        user: {},
        tasks: {}, // Cambiado a un objeto para almacenar tareas agrupadas por proyecto
        statusMessage: null,
        errorMessage: null
      };
    },
    created() {
      this.statusMessage = sessionStorage.getItem('statusMessage') || this.$route.query.status || null;
      if (this.statusMessage) {
        sessionStorage.removeItem('statusMessage');
      }
      this.fetchUser();
    },
    methods: {
      async logout() {
        try {
          const response = await fetch('/api/auth/logout', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({ token: localStorage.getItem('token') })
          });
          if (!response.ok) {
            throw new Error('Logout failed');
          }
          localStorage.removeItem('token');
          this.$router.push('/login');
        } catch (error) {
          console.error('Logout failed', error);
        }
      },
      async fetchUser() {
        try {
          const response = await fetch('/api/auth/me', {
            headers: {
              'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
          });
          if (!response.ok) {
            throw new Error('Failed to fetch user');
          }
          const data = await response.json();
          this.user = data;
          await this.fetchAssignedTasks();
        } catch (error) {
          console.error('Failed to fetch user', error);
        }
      },
      async fetchAssignedTasks() {
        try {
          const token = localStorage.getItem('token');
          const response = await fetch(`/api/users/${this.user.id}/assigned-tasks`, {
            headers: {
              'Authorization': `Bearer ${token}`
            }
          });
          if (response.ok) {
            this.tasks = await response.json();
          } else {
            console.error('Error al obtener tareas asignadas', await response.text());
          }
        } catch (error) {
          console.error('Error al obtener tareas asignadas', error);
        }
      }
    }
  };
  </script>

  <style scoped>
  .table-container {
    overflow-x: auto;
  }
  .table-container table {
    min-width: 600px;
  }
  </style>
