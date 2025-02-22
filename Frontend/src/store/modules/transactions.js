import axios from 'axios';

export default {
  state: {
    transactions: [],
    isLoading: false,  // Ajout de l'état de chargement
  },
  mutations: {
    SET_TRANSACTIONS(state, transactions) {
      state.transactions = transactions;
    },
    SET_LOADING(state, isLoading) {
      state.isLoading = isLoading;
    }
  },
  actions: {
    async fetchTransactions({ commit }) {
      commit('SET_LOADING', true); // Début du chargement
      try {
        const token = localStorage.getItem('token'); // Récupérer le token stocké
        const response = await axios.get('http://localhost:8000/api/transactions', {
          headers: {
            Authorization: `Bearer ${token}`
          }
        });
        commit('SET_TRANSACTIONS', response.data);
      } catch (error) {
        console.error('Error retrieving transactions:', error);
      } finally {
        commit('SET_LOADING', false); // Fin du chargement
      }
    }
  },
  getters: {
    transactions: state => state.transactions,
    isLoading: state => state.isLoading // Ajout d'un getter pour l'état de chargement
  }
};
