import { createStore } from 'vuex';
import axios from 'axios';

export default createStore({
  state: {
    users: [],
  },
  mutations: {
    setUsers(state, users) {
      state.users = users;
    },
    addUser(state, user) {
        state.users.push(user);
      },
    removeUser(state, userId) {
      state.users = state.users.filter(user => user.id !== userId);
    },
  },
  actions: {
    
// trouver les utilisateurs
    async fetchUsers({ commit }) {
      try {
        const token = localStorage.getItem('token'); // Récupération du token du localStorage
        if (!token) {
          throw new Error('Token manquant'); // Si le token est manquant, une erreur est levée
        }

        const response = await axios.get('http://localhost:8000/api/admin/users', {
          headers: {
            Authorization: `Bearer ${token}`, // Ajout du token dans les en-têtes de la requête
          },
        });

        commit('setUsers', response.data); // Commit des utilisateurs récupérés
      } catch (error) {
        console.error('Erreur lors du chargement des utilisateurs:', error);
      }
    },
    // Ajouter un nouvel utilisateur
    async addUser({ commit }, userData) {
        try {
          const token = localStorage.getItem('token'); // Récupérer le token JWT
          const response = await axios.post(
            'http://localhost:8000/api/admin/users',
            userData,
            {
              headers: {
                Authorization: `Bearer ${token}`, // Ajouter le token dans l'en-tête
              },
            }
          );
          commit('addUser', response.data.user); // Ajouter l'utilisateur à la liste
        } catch (error) {
          console.error('Erreur lors de l\'ajout de l\'utilisateur:', error);
          throw error; // Propager l'erreur pour la gérer dans le composant
        }
      },
// supprimer un utilisateur
    async deleteUser({ commit }, userId) {
      try {
        const token = localStorage.getItem('token');
        if (!token) {
          throw new Error('Token manquant');
        }

        await axios.delete(`http://localhost:8000/api/admin/users/${userId}`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        commit('removeUser', userId); // Commit de la suppression de l'utilisateur
      } catch (error) {
        console.error('Erreur lors de la suppression de l\'utilisateur:', error);
      }
    },
  },
  getters: {
    allUsers: (state) => state.users,
  },
});
