<template>
    <div class="assign-members max-w-4xl mx-auto mt-10 p-6 bg-white border border-gray-200 rounded-lg shadow-md">
      <h2 class="text-2xl font-bold mb-6 text-gray-900">Asignar Miembros a la Tarea</h2>

      <div v-if="users.length" class="space-y-4">
        <div v-for="user in users" :key="user.id" class="flex items-center justify-between p-2 bg-gray-100 border rounded-lg">
          <p class="font-semibold">{{ user.name }}</p>

          <button
            v-if="!userAssigned(user.id)"
            @click="assignMemberToTask(user.id)"
            class="py-2 px-4 bg-green-500 text-white font-semibold rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
          >
            Asignar tarea
          </button>
          <span v-else class="py-2 px-4 bg-gray-300 text-gray-700 font-semibold rounded-md">
            Asignado
          </span>
        </div>
      </div>
      <p v-else class="text-gray-700">No hay usuarios disponibles para asignar.</p>
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
        users: [],
        assignedUsers: []
      };
    },
    async created() {
      await this.fetchProjectUsers();
      await this.fetchAssignedUsers();
    },
    methods: {
      async fetchProjectUsers() {
        try {
          const token = localStorage.getItem('token');
          const response = await fetch(`/api/projects/${this.projectId}/users`, {
            headers: {
              'Content-Type': 'application/json',
              'Authorization': `Bearer ${token}`
            }
          });
          if (response.ok) {
            this.users = await response.json();
          } else {
            console.error('Error al obtener usuarios del proyecto', await response.text());
          }
        } catch (error) {
          console.error('Error al obtener usuarios del proyecto', error);
        }
      },
      async fetchAssignedUsers() {
        try {
          const token = localStorage.getItem('token');
          const response = await fetch(`/api/projects/${this.projectId}/tasks/${this.taskId}/users`, {
            headers: {
              'Authorization': `Bearer ${token}`
            }
          });
          console.log(response);
          if (response.ok) {
            const data = await response.json();
            this.assignedUsers = data.map(user => user.id);
          } else {
            console.error('Error al obtener usuarios asignados', await response.text());
          }
        } catch (error) {
          console.error('Error al obtener usuarios asignados', error);
        }
      },
      async assignMemberToTask(userId) {
        try {
          const token = localStorage.getItem('token');
          const response = await fetch(`/api/projects/${this.projectId}/tasks/${this.taskId}/assign`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({ user_id: userId })
          });
          if (response.ok) {
            this.assignedUsers.push(userId);
            alert('Miembro asignado con éxito');
          } else {
            console.error('Error al asignar miembro', await response.text());
          }
        } catch (error) {
          console.error('Error al asignar miembro', error);
        }
      },
      userAssigned(userId) {
        return this.assignedUsers.includes(userId);
      }
    }
  };
  </script>
