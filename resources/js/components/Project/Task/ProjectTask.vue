<template>
    <div class="project-tasks max-w-4xl mx-auto mt-10 p-6 bg-white border border-gray-200 rounded-lg shadow-md">
      <h2 class="text-2xl font-bold mb-6 text-gray-900">Tareas del Proyecto</h2>

      <div class="mb-6">
        <router-link
          :to="`/projects/${projectId}/tasks/create`"
          class="py-2 px-4 bg-green-500 text-white font-semibold rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
        >
          Crear Tarea
        </router-link>
      </div>

      <div v-if="tasks.length > 0">
        <ul class="space-y-4">
          <li v-for="task in tasks" :key="task.id" class="bg-gray-100 p-4 border border-gray-300 rounded-md shadow-sm">
            <h3 class="text-lg font-semibold text-gray-900">{{ task.title }}</h3>
            <p class="text-gray-600">{{ task.description }}</p>
            <p class="text-gray-500">Inicio: {{ task.start_date }}</p>
            <p class="text-gray-500">Vencimiento: {{ task.due_date }}</p>
            <p :class="{'text-green-500': task.is_completed, 'text-red-500': !task.is_completed}">
              Estado: {{ task.is_completed ? 'Completada' : 'Pendiente' }}
            </p>

            <div v-if="canEditOrDelete" class="mt-4 flex gap-4">
              <router-link
                :to="`/projects/${projectId}/tasks/${task.id}/edit`"
                class="py-2 px-4 bg-yellow-500 text-dark font-semibold rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500"
              >
                Editar
              </router-link>
              <router-link
                :to="`/projects/${projectId}/tasks/${task.id}/assign-members`"
                class="py-2 px-4 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                Asignar tarea a miembros
              </router-link>
              <button
                @click="deleteTask(task.id)"
                class="py-2 px-4 bg-red-500 text-white font-semibold rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
              >
                Eliminar
              </button>
            </div>
          </li>
        </ul>
      </div>

      <p v-else class="text-gray-700">Cargando tareas...</p>
    </div>
  </template>

  <script>
  export default {
    props: {
      projectId: {
        type: Number,
        required: true
      }
    },
    data() {
      return {
        tasks: [],
        canEditOrDelete: false,
      };
    },
    async created() {
      await this.fetchTasks();
      await this.fetchUserRole();
    },
    methods: {
      async fetchTasks() {
        try {
          const token = localStorage.getItem('token');
          const response = await fetch(`/api/projects/${this.projectId}/tasks`, {
            headers: {
              'Authorization': `Bearer ${token}`
            }
          });
          if (response.ok) {
            this.tasks = await response.json();
          } else {
            console.error('Error al obtener las tareas', await response.text());
          }
        } catch (error) {
          console.error('Error al obtener las tareas', error);
        }
      },
      async deleteTask(taskId) {
        if (confirm('¿Estás seguro de que quieres eliminar esta tarea?')) {
          try {
            const token = localStorage.getItem('token');
            const response = await fetch(`/api/projects/${this.projectId}/tasks/${taskId}`, {
              method: 'DELETE',
              headers: {
                'Authorization': `Bearer ${token}`
              }
            });
            if (response.ok) {
              this.tasks = this.tasks.filter(task => task.id !== taskId);
              alert('Tarea eliminada con éxito');
            } else {
              console.error('Error al eliminar la tarea', await response.text());
            }
          } catch (error) {
            console.error('Error al eliminar la tarea', error);
          }
        }
      },
      async fetchUserRole() {
        try {
          const response = await fetch('/api/auth/me', {
            headers: {
              'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
          });
          if (response.ok) {
            const user = await response.json();
            this.canEditOrDelete = user.role === 'admin' || user.role === 'project_manager';
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
