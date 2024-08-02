<template>
    <div class="edit-user-role max-w-4xl mx-auto mt-10 p-6 bg-white border border-gray-200 rounded-lg shadow-md">
      <h2 class="text-2xl font-bold mb-6 text-gray-900">Editar Rol de Usuario</h2>

      <!-- User list -->
      <div v-if="users.length" class="space-y-4">
        <div v-for="user in users" :key="user.id" class="flex items-center justify-between p-2 bg-gray-100 border rounded-lg">
          <p class="font-semibold">{{ user.name }}</p>

          <span class="flex items-center space-x-2">
            <span class="py-2 px-4 bg-gray-300 text-gray-700 font-semibold rounded-md">{{ user.role }}</span>
            <button
              @click="goToEditUserRole(user.id)"
              class="py-2 px-4 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Editar Rol
            </button>
          </span>
        </div>
      </div>
      <p v-else class="text-gray-700">No hay usuarios disponibles.</p>
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
