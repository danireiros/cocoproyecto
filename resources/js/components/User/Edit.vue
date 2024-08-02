<template>
    <div class="edit-user-role max-w-xl mx-auto mt-10 p-6 bg-white border border-gray-200 rounded-lg shadow-md">
      <h2 class="text-2xl font-bold mb-6 text-gray-900">Editar Rol de {{ user.name }}</h2>
      <form @submit.prevent="updateUserRole">
        <div class="mb-4">
          <label for="role" class="block text-sm font-medium text-gray-700">Rol actual: {{ user.role }}</label>
          <select
            id="role"
            v-model="this.role"
            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          >
            <option value="member">Miembro</option>
            <option value="project_manager">Manager</option>
            <option value="admin">Admin</option>
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
      userId: {
        type: Number,
        required: true
      }
    },
    data() {
      return {
        role: '',  // Inicializa `role` con una cadena vacía
        user: {}   // Inicializa `user` como un objeto vacío
      };
    },
    async created() {
      await this.fetchUserDetails();  // Cargar detalles del usuario y su rol cuando se crea el componente
    },
    methods: {
      async fetchUserDetails() {
        await this.fetchUser();        // Obtener detalles del usuario, como el nombre
        await this.fetchUserRole();    // Obtener el rol del usuario
      },
      async fetchUser() {
        try {
          const token = localStorage.getItem('token');
          const response = await fetch(`/api/users/${this.userId}`, {
            headers: {
              'Authorization': `Bearer ${token}`
            }
          });

          if (response.ok) {
            const data = await response.json();
            this.user = data;  // Guardar los detalles del usuario
            this.role = this.user.role || 'member'; // Asegúrate de que `role` esté definido correctamente
          } else {
            console.error('Error al obtener los detalles del usuario', await response.text());
          }
        } catch (error) {
          console.error('Error al obtener los detalles del usuario', error);
        }
      },
      async fetchUserRole() {
        try {
          const token = localStorage.getItem('token');
          const response = await fetch(`/api/users/${this.userId}/role`, {
            headers: {
              'Authorization': `Bearer ${token}`
            }
          });

          if (response.ok) {
            const data = await response.json();
            this.role = data.role;  // Actualizar el valor de `role` con el rol del usuario
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
          const response = await fetch(`/api/users/${this.userId}/role`, {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({ role: this.role })
          });

          if (response.ok) {
            alert('Rol actualizado con éxito');
            this.$router.push(`/users`);
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
