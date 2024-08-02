<template>
    <div class="edit-task max-w-4xl mx-auto mt-10 p-6 bg-white border border-gray-200 rounded-lg shadow-md">
      <h2 class="text-2xl font-bold mb-6 text-gray-900">Editar Tarea</h2>
      <form @submit.prevent="updateTask">
        <div class="mb-4">
          <label for="title" class="block text-sm font-medium text-gray-700">Título</label>
          <input
            id="title"
            v-model="title"
            type="text"
            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            required
          />
        </div>
        <div class="mb-4">
          <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
          <textarea
            id="description"
            v-model="description"
            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          ></textarea>
        </div>
        <div class="mb-4">
          <label for="start_date" class="block text-sm font-medium text-gray-700">Fecha de Inicio</label>
          <input
            id="start_date"
            v-model="startDate"
            type="date"
            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          />
        </div>
        <div class="mb-4">
          <label for="due_date" class="block text-sm font-medium text-gray-700">Fecha de Vencimiento</label>
          <input
            id="due_date"
            v-model="dueDate"
            type="date"
            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          />
        </div>
        <div class="mb-4">
          <label for="is_completed" class="block text-sm font-medium text-gray-700">Completada</label>
          <input
            id="is_completed"
            v-model="isCompleted"
            type="checkbox"
            class="mt-1"
          />
        </div>
        <div class="flex justify-end">
          <button
            type="submit"
            class="py-2 px-4 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Actualizar Tarea
          </button>
        </div>
      </form>
    </div>
  </template>

  <script>
  export default {
    props: {
      projectId: {
        type: Number,
        required: true
      },
      taskId: {
        type: Number,
        required: true
      }
    },
    data() {
      return {
        title: '',
        description: '',
        startDate: '',
        dueDate: '',
        isCompleted: false
      };
    },
    async created() {
      await this.fetchTask();
    },
    methods: {
      async fetchTask() {
        try {
          const token = localStorage.getItem('token');
          const response = await fetch(`/api/projects/${this.projectId}/tasks/${this.taskId}`, {
            headers: {
              'Authorization': `Bearer ${token}`
            }
          });
          if (response.ok) {
            const task = await response.json();

            this.title = task.title;
            this.description = task.description;
            this.startDate = task.start_date;
            this.dueDate = task.due_date;
            this.isCompleted = task.is_completed;
          } else {
            console.error('Error al obtener la tarea', await response.text());
          }
        } catch (error) {
          console.error('Error al obtener la tarea', error);
        }
      },
      async updateTask() {
        try {
          const token = localStorage.getItem('token');
          const response = await fetch(`/api/projects/${this.projectId}/tasks/${this.taskId}`, {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({
              title: this.title,
              description: this.description,
              start_date: this.startDate,
              due_date: this.dueDate,
              is_completed: this.isCompleted
            })
          });
          if (response.ok) {
            alert('Tarea actualizada con éxito');
            this.$router.push(`/projects/${this.projectId}/tasks`); // Redirige a la página de tareas del proyecto
          } else {
            console.error('Error al actualizar la tarea', await response.text());
          }
        } catch (error) {
          console.error('Error al actualizar la tarea', error);
        }
      }
    }
  };
  </script>
