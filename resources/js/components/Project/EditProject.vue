<template>
    <div class="project-details max-w-4xl mx-auto mt-10 p-6 bg-white border border-gray-200 rounded-lg shadow-md">
      <div v-if="project" class="edit-project">
        <h2 class="text-2xl font-bold mb-6 text-gray-900">Actualizar Proyecto</h2>
        <form @submit.prevent="updateProject">
          <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
            <input
              v-model="form.name"
              type="text"
              id="name"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              required
            />
          </div>
          <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
            <textarea
              v-model="form.description"
              id="description"
              rows="4"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              required
            ></textarea>
          </div>
          <button
            type="submit"
            class="py-2 px-4 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Actualizar
          </button>
        </form>

        <!-- Delete Button -->
        <button
          @click="deleteProject"
          class="mt-4 py-2 px-4 bg-red-500 text-white font-semibold rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
        >
          Eliminar
        </button>
      </div>
      <p v-else class="text-gray-700">Cargando detalles del proyecto...</p>
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
        project: null,
        form: {
          name: '',
          description: ''
        }
      };
    },
    async created() {
      await this.fetchProject();
    },
    methods: {
      async fetchProject() {
        try {
          const token = localStorage.getItem('token');
          const response = await fetch(`/api/projects/${this.projectId}`, {
            method: 'GET',
            headers: {
              'Content-Type': 'application/json',
              'Authorization': `Bearer ${token}`
            }
          });
          if (response.ok) {
            const data = await response.json();
            this.project = data;
            this.form.name = data.name;
            this.form.description = data.description;
          } else {
            console.error('Error al obtener el proyecto', await response.text());
          }
        } catch (error) {
          console.error('Error al obtener el proyecto', error);
        }
      },
      async updateProject() {
        try {
          const token = localStorage.getItem('token');
          const response = await fetch(`/api/projects/${this.projectId}`, {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(this.form)
          });
          if (response.ok) {
            const updatedProject = await response.json();
            this.project = updatedProject;
            this.$emit('updated', updatedProject);
            this.$router.push(`/projects/show/${this.projectId}`);
          } else {
            console.error('Error al actualizar el proyecto');
          }
        } catch (error) {
          console.error('Error al actualizar el proyecto', error);
        }
      },
      async deleteProject() {
        try {
          const token = localStorage.getItem('token');
          const response = await fetch(`/api/projects/${this.projectId}`, {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json',
              'Authorization': `Bearer ${token}`
            }
          });
          if (response.ok) {
            this.$router.push('/projects');
          } else {
            console.error('Error al eliminar el proyecto');
          }
        } catch (error) {
          console.error('Error al eliminar el proyecto', error);
        }
      }
    }
  }
  </script>
