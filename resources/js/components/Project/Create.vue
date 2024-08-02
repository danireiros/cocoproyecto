<template>
    <div class="max-w-md mx-auto mt-10 p-6 bg-white border border-gray-200 rounded-lg shadow-md">
      <h2 class="text-2xl font-bold mb-6 text-gray-900">Crear Nuevo Proyecto</h2>
      <form @submit.prevent="createProject">
        <div class="mb-4">
          <label for="name" class="block text-sm font-medium text-gray-700">Nombre del Proyecto</label>
          <input
            v-model="name"
            type="text"
            id="name"
            placeholder="Introduce el nombre del proyecto"
            required
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          />
        </div>
        <div class="mb-6">
          <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
          <textarea
            v-model="description"
            id="description"
            placeholder="Introduce una descripción del proyecto"
            rows="4"
            required
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          ></textarea>
        </div>
        <button
          type="submit"
          class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        >
          Crear Proyecto
        </button>
        <p v-if="statusMessage" class="mt-4 text-green-600">{{ statusMessage }}</p>
        <p v-if="errors.length" class="mt-4 text-red-600 text-sm">{{ errors.join(', ') }}</p>
      </form>
    </div>
  </template>

  <script>
  export default {
    data() {
      return {
        name: '',
        description: '',
        statusMessage: null,
        errors: []
      };
    },
    methods: {
      async createProject() {
        try {
          const response = await fetch('/api/projects', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            body: JSON.stringify({
              name: this.name,
              description: this.description
            })
          });
          const data = await response.json();
          if (response.ok) {
            this.statusMessage = 'Proyecto creado con éxito.';
            this.errors = [];
            this.$router.push('/projects');
          } else {
            this.statusMessage = null;
            this.errors = data.errors ? Object.values(data.errors).flat() : [data.error];
          }
        } catch (error) {
          console.error('Error al crear proyecto', error);
          this.statusMessage = 'Error al crear proyecto.';
        }
      }
    }
  };
  </script>
