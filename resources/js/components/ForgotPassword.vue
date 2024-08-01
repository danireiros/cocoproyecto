<template>
    <div class="max-w-md mx-auto mt-10 p-6 bg-white border border-gray-200 rounded-lg shadow-md">
      <h2 class="text-2xl font-bold mb-6 text-gray-900">Recuperar contraseña</h2>
      <form @submit.prevent="sendResetLink">
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input
            v-model="email"
            type="email"
            id="email"
            placeholder="Introduce tu email"
            required
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          />
        </div>
        <button
          type="submit"
          class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        >
          Enviar enlace de recuperación a mi email
        </button>
        <p v-if="statusMessage" class="mt-4">{{ statusMessage }}</p>
        <p v-if="errors.length" class="mt-4 text-red-600 text-sm">{{ errors.join(', ') }}</p>
      </form>
    </div>
  </template>

  <script>
  export default {
    data() {
      return {
        email: '',
        statusMessage: null,
        errors: []
      };
    },
    methods: {
      async sendResetLink() {
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const response = await fetch('/password/email', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ email: this.email })
          });
          const data = await response.json();
          if (response.ok) {
            this.statusMessage = data.status;
            this.errors = [];
          } else {
            this.statusMessage = null;
            this.errors = data.errors ? Object.values(data.errors).flat() : [data.error];
          }
        } catch (error) {
          console.error('Error al enviar enlace', error);
          this.statusMessage = 'Error al enviar enlace.';
        }
      }
    }
  };
  </script>
