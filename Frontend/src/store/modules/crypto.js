<<<<<<< HEAD
import { createStore } from "vuex";
import axios from "axios";

export default createStore({
  state: {
    cryptocurrencies: [],
  },
  mutations: {
    SET_CRYPTOS(state, cryptos) {
      state.cryptocurrencies = cryptos;
    },
    ADD_CRYPTO(state, crypto) {
      state.cryptocurrencies.push(crypto);
    },
  },
  actions: {
    async fetchCryptos({ commit }) {
      try {
        const response = await axios.get("http://127.0.0.1:8000/api/cryptocurrencies");
        commit("SET_CRYPTOS", response.data);
      } catch (error) {
        console.error("Error fetching cryptocurrencies:", error);
      }
    },
    async addCrypto({ commit }, crypto) {
      try {
        const response = await axios.post("http://127.0.0.1:8000/api/cryptocurrencies", crypto);
        commit("ADD_CRYPTO", response.data);
      } catch (error) {
        console.error("Error adding cryptocurrency:", error);
      }
    },
  },
  getters: {
    allCryptos: (state) => state.cryptocurrencies,
  },
});
=======
import cryptoService from "@/services/cryptoService";
import api from "@/services/api";

const state = {
  cryptos: [], // Liste des cryptos
  priceHistory: [], // Historique des prix
  crypto: null, // Détails de la crypto sélectionnée
};

const mutations = {
  SET_PRICE_HISTORY(state, history) {
    state.priceHistory = history;
  },
  SET_CRYPTO(state, crypto) {
    state.crypto = crypto;
  },
};

const actions = {
  async loadPriceHistory({ commit }, { cryptoId, days = 30 }) {
    try {
      const response = await api.get(
        `/cryptos/${cryptoId}/history?days=${days}`
      );

      if (!response.data || !response.data.original)
        throw new Error("Données invalides");

      const { crypto, history } = response.data.original;
      commit("SET_PRICE_HISTORY", history || []);
      commit("SET_CRYPTO", crypto || null);
    } catch (error) {
      console.error(
        "Erreur lors de la récupération de l'historique des prix",
        error
      );
      commit("SET_PRICE_HISTORY", []);
    }
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
>>>>>>> 60f904f3255718ad6d1e83ec566a300b62ba032c
