<template>
    <div class="max-w-md mx-auto mt-10 p-6 bg-white border border-gray-200 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-gray-900">Actualizar contraseña</h2>
        <form @submit.prevent="resetPassword">
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Nueva contraseña</label>
                <input
                    v-model="password"
                    type="password"
                    id="password"
                    placeholder="Introduce la nueva contraseña"
                    required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                />
            </div>
            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar nueva contraseña</label>
                <input
                    v-model="password_confirmation"
                    type="password"
                    id="password_confirmation"
                    placeholder="Confirma la nueva contraseña"
                    required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                />
            </div>
            <button
                type="submit"
                class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
                Actualizar contraseña
            </button>
            <p v-if="statusMessage" class="mt-4 text-green-600">{{ statusMessage }}</p>
            <p v-if="errors.length" class="mt-4 text-red-600 text-sm">{{ errors.join(', ') }}</p>
        </form>
    </div>
</template>

<script>
export default {
    props: ['token'], // Asegúrate de que el token esté en los props
    data() {
        return {
            password: '',
            password_confirmation: '',
            statusMessage: null,
            errors: []
        };
    },
    methods: {
        async resetPassword() {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch('/password/reset', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        token: this.token,
                        email: this.$route.query.email, // Asegúrate de que email esté en el query string
                        password: this.password,
                        password_confirmation: this.password_confirmation
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    this.statusMessage = data.status;
                    this.errors = [];
                } else {
                    this.statusMessage = null;
                    this.errors = data.errors ? Object.values(data.errors).flat() : [data.error];
                }
            } catch (error) {
                console.error('Error al actualizar contraseña', error);
                this.statusMessage = 'Error al actualizar contraseña.';
            }
        }
    }
};
</script>
