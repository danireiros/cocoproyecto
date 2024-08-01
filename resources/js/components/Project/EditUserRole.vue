<template>
    <div class="edit-user-role max-w-xl mx-auto mt-10 p-6 bg-white border border-gray-200 rounded-lg shadow-md">
      <h2 class="text-2xl font-bold mb-6 text-gray-900">Editar Rol de Usuario</h2>
      <form @submit.prevent="updateUserRole">
        <div class="mb-4">
          <label for="role" class="block text-sm font-medium text-gray-700">Rol</label>
          <select
            id="role"
            v-model="role"
            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          >
            <option value="member">Miembro</option>
            <option value="project_manager">Manager</option>
          </select>
        </div>
        <div class="flex justify-end">
          <button
            type="submit"
            class="py-2 px-4 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Guardar
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
      userId: {
        type: Number,
        required: true
      }
    },
    data() {
      return {
        role: null
      };
    },
    async created() {
      await this.fetchUserRole();
    },
    methods: {
      async fetchUserRole() {
        try {
          const token = localStorage.getItem('token');
          const response = await fetch(`/api/projects/${this.projectId}/users/${this.userId}/role`, {
            headers: {
              'Authorization': `Bearer ${token}`
            }
          });

          if (response.ok) {
            const data = await response.json();
            this.role = data.role;
          } else {
            console.error('Error al obtener el rol del usuario', await response.text());
          }
        } catch (error) {
          console.error('Error al obtener el rol del usuario', error);
        }
      },
      async updateUserRole() {
        try {
          const token = localStorage.getItem('token');
          const response = await fetch(`/api/projects/${this.projectId}/users/${this.userId}/role`, {
            method: 'PUT', // Cambiado a PUT según las rutas definidas en el backend
            headers: {
              'Content-Type': 'application/json',
              'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({ role: this.role }) // Enviamos el campo 'role'
          });

          if (response.ok) {
            alert('Rol actualizado con éxito');
            this.$router.push(`/projects/show/${this.projectId}`); // Redirige a la página del proyecto
          } else {
            console.error('Error al actualizar el rol', await response.text());
          }
        } catch (error) {
          console.error('Error al actualizar el rol', error);
        }
      }
    }
  };
  </script>
