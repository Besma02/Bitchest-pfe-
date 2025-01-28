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
    updateUser(state, user) {
      // Trouver l'utilisateur par son ID et le mettre à jour
      const index = state.users.findIndex(u => u.id === user.id);
      if (index !== -1) {
        state.users.splice(index, 1, user); // Remplacer l'utilisateur existant par le mis à jour
      }
    },
    removeUser(state, userId) {
      state.users = state.users.filter(user => user.id !== userId);
    },
  },
  actions: {
    // Trouver les utilisateurs
    async fetchUsers({ commit }) {
      try {
        const token = localStorage.getItem('token');
        if (!token) {
          throw new Error('Token manquant');
        }
    
        const response = await axios.get('http://localhost:8000/api/admin/users', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
    
        // Ajouter le préfixe pour les photos si nécessaire
        const usersWithFullPhotoUrls = response.data.map(user => ({
          ...user,
          photo: user.photo ? `http://localhost:8000/storage/${user.photo}` : null, // Assurez-vous que l'URL de la photo est complète
        }));
    
        commit('setUsers', usersWithFullPhotoUrls);
      } catch (error) {
        console.error('Erreur lors du chargement des utilisateurs:', error);
      }
    },
    
    // Ajouter un nouvel utilisateur
   // Store - Vuex

async addUser({ commit }, userData) {
  try {
    const token = localStorage.getItem('token');
    const response = await axios.post(
      'http://localhost:8000/api/admin/users',
      userData,
      {
        headers: {
          Authorization: `Bearer ${token}`,
          'Content-Type': 'multipart/form-data',
        },
      }
    );

    // Assurez-vous que la réponse contient l'URL de la photo et d'autres données de l'utilisateur
    commit('addUser', response.data.user); // Ajouter l'utilisateur à la liste
    return response.data.user.photoUrl; // Retourner l'URL de la photo
  } catch (error) {
    throw error;
  }
},

    
    // Mettre à jour un utilisateur
    async updateUser({ commit }, { id, userData }) {
      try {
        const token = localStorage.getItem('token');
    
        if (!token) {
          throw new Error('Token is missing. Please login again.');
        }
    
        const response = await axios.put(
          `http://localhost:8000/api/admin/users/${id}`,
          userData,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );
    
        commit('updateUser', response.data.user); // Met à jour le store avec les nouvelles données
      } catch (error) {
        if (error.response && error.response.status === 422) {
          throw error; // Relance l'erreur pour la gérer dans le composant
        } else {
          console.error('Error updating user:', error.response?.data || error.message);
        }
      }
    },
    
    // Supprimer un utilisateur
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
