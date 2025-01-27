<template>
    <div class="max-w-lg mx-auto mt-9 p-6 bg-white shadow-md rounded-lg">
      <h1 class="text-3xl font-semibold mb-8 text-center text-gray-800">Add User</h1>
  
      <form @submit.prevent="submitForm" class="space-y-6">
        <!-- Champ Name -->
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
          <input
            v-model="name"
            id="name"
            type="text"
            required
            class="mt-1 w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            placeholder="Enter the user's name"
          />
        </div>
  
        <!-- Champ Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input
            v-model="email"
            id="email"
            type="email"
            required
            class="mt-1 w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            placeholder="Enter the user's email"
          />
        </div>
  
        <!-- Champ Role -->
        <div>
          <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
          <select
            v-model="role"
            id="role"
            class="mt-1 w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
          >
            <option value="client">Client</option>
            <option value="admin">Admin</option>
          </select>
        </div>
  
        <!-- Bouton Ajouter -->
        <div class="text-center">
          <button
            type="submit"
            class="w-full bg-bitchest-success text-balck text-[1rem] font-bold px-4 py-2 rounded-lg shadow hover:bg-gray-200 text-black focus:ring-2 focus:ring-indigo-500 focus:outline-none"
          >
            Add User
          </button>
        </div>
      </form>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        name: '', // Nom de l'utilisateur
        email: '', // Email de l'utilisateur
        role: 'client', // Rôle par défaut (Client)
      };
    },
    methods: {
      async submitForm() {
        // Données du formulaire
        const userData = {
          name: this.name,
          email: this.email,
          role: this.role,
        };
  
        try {
          // Appeler l'action Vuex pour ajouter l'utilisateur
          await this.$store.dispatch('addUser', userData);
  
          // Rediriger vers la liste des utilisateurs après succès
          this.$router.push({ name: 'manage-users' });
        } catch (error) {
          console.error('Erreur lors de l\'ajout de l\'utilisateur :', error);
        }
      },
    },
  };
  </script>
  