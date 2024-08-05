<template>
    <div class="submit-task-container max-w-md mx-auto mt-10 p-6 bg-white border border-gray-200 rounded-lg shadow-md">
      <h2 class="text-2xl font-bold mb-6 text-gray-900">Entregar Tarea</h2>

      <form @submit.prevent="submitFile">
        <label for="file" class="block text-sm font-medium text-gray-700">Seleccionar archivo</label>
        <input
          id="file"
          type="file"
          @change="handleFileUpload"
          class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          required
        />
        <button
          type="submit"
          class="mt-2 py-2 px-4 bg-green-500 text-white font-semibold rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
        >
          Subir Archivo
        </button>
      </form>
    </div>
  </template>

  <script>
  export default {
    props: ['taskId'],
    data() {
      return {
        selectedFile: null,
      };
    },
    methods: {
      handleFileUpload(event) {
        this.selectedFile = event.target.files[0];
      },
      async submitFile() {
        if (!this.selectedFile) {
          return;
        }

        const formData = new FormData();
        formData.append('file', this.selectedFile);

        try {
          const token = localStorage.getItem('token');
          const response = await fetch(`/api/tasks/${this.taskId}/submit`, {
            method: 'POST',
            headers: {
              'Authorization': `Bearer ${token}`
            },
            body: formData
          });

          if (response.ok) {
            this.selectedFile = null;
            alert('Archivo subido con exito');
          } else {
            console.error('Error al subir archivo', await response.text());
          }
        } catch (error) {
          alert('Error al subir archivo.');
          console.error('Error al subir archivo', error);
        }
      },

    }
  };
  </script>
