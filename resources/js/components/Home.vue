<template>
    <div class="home-container p-4">
      <div v-if="statusMessage" class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
        {{ statusMessage }}
      </div>
      <div v-if="errorMessage" class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
        {{ errorMessage }}
      </div>
    </div>
  </template>

  <script>
  export default {
    data() {
      return {
        user: {},
        statusMessage: null,
        errorMessage: null,
      };
    },
    created() {
        this.statusMessage = sessionStorage.getItem('statusMessage') || this.$route.query.status || null;
        if (this.statusMessage) {
            sessionStorage.removeItem('statusMessage');
        }
        this.fetchUser();
    },
    methods: {
      async logout() {
        try {
          const response = await fetch('/api/auth/logout', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({ token: localStorage.getItem('token') })
          });
          if (!response.ok) {
            throw new Error('Logout failed');
          }
          localStorage.removeItem('token');
          this.$router.push('/login');
        } catch (error) {
          console.error('Logout failed', error);
        }
      },
      async fetchUser() {
        try {
          const response = await fetch('/api/auth/me', {
            headers: {
              'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
          });
          if (!response.ok) {
            throw new Error('Failed to fetch user');
          }
          const data = await response.json();
          console.log(data);
          this.user = data;
        } catch (error) {
          console.error('Failed to fetch user', error);
        }
      }
    }
  };
  </script>
