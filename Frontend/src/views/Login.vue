<template>
  <div>
  <NavBar/>
  <div class="max-w-md mx-auto p-6 bg-white border border-gray-200 rounded-lg shadow-md mt-[150px]">
    <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Login</h1>
    <form @submit.prevent="handleLogin">
      <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input
          type="email"
          id="email"
          v-model="email"
          class="border rounded-lg px-4 py-2 w-full focus:ring-blue-500 focus:border-blue-500"
          placeholder="Entrez votre email"
        />
      </div>
      <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input
          type="password"
          id="password"
          v-model="password"
          class="border rounded-lg px-4 py-2 w-full focus:ring-blue-500 focus:border-blue-500"
          placeholder="Entrez votre mot de passe"
        />
      </div>
      <button
        type="submit"
        class="bg-bitchest-success text-black px-4 py-2 font-bold text-[1rem] rounded-lg w-full hover:bg-gray-200 focus:outline-none focus:ring-2  focus:ring-opacity-50"
      >
        Login
      </button>
    </form>
    <p v-if="error" class="text-red-500 mt-4 text-center">{{ error }}</p>
  </div>
</div>
</template>

<script>
import NavBar from '../components/NavBar.vue';
import axios from 'axios';

export default {
  components:{
    NavBar
  },
  data() {
    return {
      email: '',
      password: '',
      error: null,
    };
  },
  methods: {
    async handleLogin() {
      try {
        this.error = null; // Réinitialiser les erreurs
        const response = await axios.post('http://localhost:8000/api/login', {
          email: this.email,
          password: this.password,
        });

        // Sauvegarde du token dans le localStorage
        localStorage.setItem('token', response.data.token);

        // Redirection ou affichage d'un message de succès
        alert('Successful Connection ');
        this.$router.push('/dashboard'); // Rediriger vers une autre page
      } catch (err) {
        // Gestion des erreurs
        if (err.response && err.response.status === 401) {
          this.error = 'Incorrect email or password.';
        } else {
          this.error = 'An error has occurred. Please try again.';
        }
      }
    },
  },
};
</script>

<style scoped>
/* Optionnel : Personnalisation des styles de base pour le conteneur */
</style>
