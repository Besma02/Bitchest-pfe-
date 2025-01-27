<template>
    <div class="login-container">
      <h1 class="text-2xl font-bold mb-4">Login</h1>
      <form @submit.prevent="handleLogin">
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium mb-1">Email</label>
          <input
            type="email"
            id="email"
            v-model="email"
            class="border rounded-lg px-4 py-2 w-full"
            placeholder="Entrez votre email"
          />
        </div>
        <div class="mb-4">
          <label for="password" class="block text-sm font-medium mb-1">Password</label>
          <input
            type="password"
            id="password"
            v-model="password"
            class="border rounded-lg px-4 py-2 w-full"
            placeholder="Entrez votre mot de passe"
          />
        </div>
        <button
          type="submit"
          class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600"
        >
          Se connecter
        </button>
      </form>
      <p v-if="error" class="text-red-500 mt-4">{{ error }}</p>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
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
          alert('Connexion réussie');
          this.$router.push('/dashboard'); // Rediriger vers une autre page
        } catch (err) {
          // Gestion des erreurs
          if (err.response && err.response.status === 401) {
            this.error = 'Email ou mot de passe incorrect.';
          } else {
            this.error = 'Une erreur est survenue. Veuillez réessayer.';
          }
        }
      },
    },
  };
  </script>
  
  <style scoped>
  .login-container {
    max-width: 400px;
    margin: 0 auto;
    padding: 1rem;
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
  }
  </style>
  