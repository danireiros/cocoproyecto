<template>
    <div class="project-details max-w-4xl mx-auto mt-10 p-6 bg-white border border-gray-200 rounded-lg shadow-md">
      <h2 v-if="project" class="text-2xl font-bold mb-6 text-gray-900">Detalles del {{ project.name }}</h2>

      <div v-if="project">
        <div class="mb-6">
          <p class="font-bold">{{ project.name }}</p>
          <p>{{ project.description }}</p>
          <p class="font-bold">Autor:</p>
          <p class="text-gray-600">{{ project.creator.name }}</p>
        </div>

        <div v-if="canEditProject" class="flex gap-4">
          <router-link
                :to="`/projects/edit/${project.id}`"
                class="py-2 px-4 bg-yellow-500 text-dark font-semibold rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500"
            >
                Editar
          </router-link>

          <router-link
                :to="`/projects/add-users/${project.id}`"
                class="py-2 px-4 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
                Usuarios
          </router-link>

          <router-link
            :to="`/projects/${project.id}/tasks`"
            class="py-2 px-4 bg-green-500 text-white font-semibold rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
            >
            Tareas
        </router-link>
        </div>
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
        },
        user: {},
        isAdmin: false,
        isProjectManager: false
      };
    },
    computed: {
      canEditProject() {
        return this.isAdmin || this.isProjectManager || (this.project && this.project.creator.id === this.user.id);
      }
    },
    async created() {
      await this.fetchProject();
      await this.checkUserRole();
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
      async checkUserRole() {
        try {
          const token = localStorage.getItem('token');
          if (token) {
            const response = await fetch('/api/auth/me', {
              headers: {
                'Authorization': `Bearer ${token}`
              }
            });
            if (response.ok) {
              const user = await response.json();
              this.user = user;
              this.isAdmin = user.role === 'admin';
              this.isProjectManager = user.role === 'project_manager';
            } else {
              console.error('Error al verificar el rol del usuario');
            }
          }
        } catch (error) {
          console.error('Error al verificar el rol del usuario', error);
        }
      }
    }
  };
  </script>
