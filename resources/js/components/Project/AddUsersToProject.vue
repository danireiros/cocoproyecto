<template>
    <div class="add-users-to-project max-w-4xl mx-auto mt-10 p-6 bg-white border border-gray-200 rounded-lg shadow-md">
      <h2 class="text-2xl font-bold mb-6 text-gray-900">Añadir Usuarios al Proyecto</h2>

      <!-- User list -->
      <div v-if="users.length" class="space-y-4">
        <div v-for="user in users" :key="user.id" class="flex items-center justify-between p-2 bg-gray-100 border rounded-lg">
          <p class="font-semibold">{{ user.name }}</p>

          <!-- Button state based on user's presence in the project -->
          <button
            v-if="!userInProject(user.id) && !addedUsers.includes(user.id)"
            @click="addUserToProject(user.id)"
            class="py-2 px-4 bg-green-500 text-white font-semibold rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
          >
            Añadir
          </button>
          <span v-else class="flex items-center space-x-2">
            <span class="py-2 px-4 bg-gray-300 text-gray-700 font-semibold rounded-md">Miembro</span>
            <button
              @click="goToEditUserRole(user.id)"
              class="py-2 px-4 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Editar Rol
            </button>
          </span>
        </div>
      </div>
      <p v-else class="text-gray-700">No hay usuarios disponibles para añadir.</p>
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
        users: [],
        addedUsers: []
      };
    },
    async created() {
      await this.fetchUsers();
      await this.fetchAddedUsers();
      this.sortUsers();
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
      async fetchAddedUsers() {
        try {
          const token = localStorage.getItem('token');
          const response = await fetch(`/api/projects/${this.projectId}/users`, {
            headers: {
              'Authorization': `Bearer ${token}`
            }
          });
          if (response.ok) {
            const data = await response.json();
            this.addedUsers = data.map(user => user.id);
          } else {
            console.error('Error al obtener usuarios añadidos', await response.text());
          }
        } catch (error) {
          console.error('Error al obtener usuarios añadidos', error);
        }
      },
      sortUsers() {
        this.users.sort((a, b) => {
          const aInProject = this.userInProject(a.id);
          const bInProject = this.userInProject(b.id);
          if (aInProject && !bInProject) return -1;
          if (!aInProject && bInProject) return 1;
          return 0;
        });
      },
      async addUserToProject(userId) {
        try {
            const token = localStorage.getItem('token');
            const response = await fetch(`/api/projects/${this.projectId}/add-users`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({ user_id: userId })
            });
            if (response.ok) {
            this.addedUsers.push(userId);
            alert('Usuario añadido con éxito');
            this.sortUsers();
            } else {
            console.error('Error al añadir usuario', await response.text());
            }
        } catch (error) {
            console.error('Error al añadir usuario', error);
        }
        },
      userInProject(userId) {
        return this.addedUsers.includes(userId);
      },
      goToEditUserRole(userId) {
        this.$router.push({ path: `/projects/${this.projectId}/users/${userId}/edit-role` });
      }
    }
  };
  </script>
