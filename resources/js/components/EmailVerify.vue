<template>
    <div>
      <p v-if="statusMessage">{{ statusMessage }}</p>
    </div>
  </template>

  <script>
  export default {
    props: ['id', 'hash'],
    data() {
      return {
        statusMessage: null,
      };
    },
    created() {
      this.verifyEmail();
    },
    methods: {
      async verifyEmail() {
        const url = `/email/verify/${this.id}/${this.hash}`;
        const params = new URLSearchParams({
          expires: this.$route.query.expires,
          signature: this.$route.query.signature,
        }).toString();

        try {
          const response = await fetch(`${url}?${params}`, {
            method: 'GET',
            headers: {
              'Accept': 'application/json',
            },
          });

          const data = await response.json();

          if (response.ok) {
            this.statusMessage = data.status;
            // Guardar el mensaje de estado en sessionStorage
            sessionStorage.setItem('statusMessage', this.statusMessage);
            // Redirigir al usuario a la página de inicio
            this.$router.push({ path: '/' });
          } else {
            this.statusMessage = data.status || 'There was an error verifying your email.';
          }
        } catch (error) {
          console.error('Error verifying email:', error);
          this.statusMessage = 'There was an error verifying your email.';
        }
      },
    },
  };
  </script>
