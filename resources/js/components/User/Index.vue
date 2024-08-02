<template>
    <div class="edit-user-role max-w-4xl mx-auto mt-2 p-6 bg-white border border-gray-200 rounded-lg shadow-md">
      <h2 class="text-2xl font-bold mb-6 text-gray-900">Editar rol de usuario</h2>

      <div class="table-container">
        <table class="w-full divide-y divide-gray-200 bg-white rounded-lg shadow-md">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol Actual</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="user in users" :key="user.id">
              <td class="px-4 py-2 text-sm text-gray-500">{{ user.name }}</td>
              <td class="px-4 py-2 text-sm">
                <span class="py-1 px-2 bg-gray-300 text-gray-700 font-semibold rounded-md">{{ user.role }}</span>
              </td>
              <td class="px-4 py-2 text-sm">
                <button
                  @click="goToEditUserRole(user.id)"
                  class="py-2 px-4 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  Editar Rol
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <p v-if="!users.length" class="text-gray-700 mt-4">No hay usuarios disponibles.</p>
    </div>
  </template>

  <script>
  export default {
    data() {
      return {
        users: []
      };
    },
    async created() {
      await this.fetchUsers();
    },
    methods: {
      async fetchUsers() {
        try {
          const token = localStorage.getItem('token');
          const response = await fetch('/api/users', {
            headers: {
              'Content-Type': 'application/json',
              'Authorization': `Bearer ${token}`
            }
          });
          if (response.ok) {
            this.users = await response.json();
          } else {
            console.error('Error al obtener usuarios', await response.text());
          }
        } catch (error) {
          console.error('Error al obtener usuarios', error);
        }
      },
      goToEditUserRole(userId) {
        this.$router.push({ path: `/users/${userId}/edit-role` });
      }
    }
  };
  </script>

  <style scoped>
  .table-container {
    overflow-x: auto;
  }

  .table-container table {
    width: 100%;
  }
  </style>
