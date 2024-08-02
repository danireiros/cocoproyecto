<template>
    <div class="home-container p-4">
      <div v-if="statusMessage" class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
        {{ statusMessage }}
      </div>
      <div v-if="errorMessage" class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
        {{ errorMessage }}
      </div>

      <div class="max-w-md mx-auto mt-10 p-6 bg-white border border-gray-200 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6 text-gray-900">Tareas</h1>
        <div v-if="tasks.length">
            <h2 class="text-2xl font-bold mb-6 text-gray-900">Tareas Asignadas</h2>
            <ul class="space-y-4">
            <li v-for="task in tasks" :key="task.id" class="bg-gray-100 p-4 border border-gray-300 rounded-md shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900">{{ task.title }}</h3>
                <p class="text-gray-600">{{ task.description }}</p>
                <p class="text-gray-500">Inicio: {{ task.start_date }}</p>
                <p class="text-gray-500">Vencimiento: {{ task.due_date }}</p>
                <p :class="{'text-green-500': task.is_completed, 'text-red-500': !task.is_completed}">
                Estado: {{ task.is_completed ? 'Completada' : 'Pendiente' }}
                </p>
                <div v-if="!task.is_completed" class="mt-3">
                    <router-link
                        v-if="!task.is_completed"
                        :to="`/projects/tasks/${task.id}/submit`"
                        class="py-2 px-4 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        Entregar
                    </router-link>
                </div>
            </li>
            </ul>
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
      tasks: [], // Nuevo campo para almacenar las tareas asignadas
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
