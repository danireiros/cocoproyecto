<template>
    <div class="home-container p-4 ">

    </div>
  </template>

  <script>
  import axios from 'axios';

  export default {
    data() {
        return {
            user: {}
        };
    },
    methods: {
        async logout() {
            try {
                await axios.post('/api/auth/logout', { token: localStorage.getItem('token') });
                localStorage.removeItem('token');
                this.$router.push('/login');
            } catch (error) {
                console.error('Logout failed', error);
            }
        },
    async fetchUser() {
        try {
            const response = await axios.get('/api/auth/me', {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
            });

            console.log(response);

            this.user = response.data;
        } catch (error) {
            console.error('Failed to fetch user', error);
            }
        }
    },
    created() {
        this.fetchUser();
    }
};
</script>
