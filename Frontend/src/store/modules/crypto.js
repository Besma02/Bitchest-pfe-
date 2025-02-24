import api from "@/services/api";

export default {
  namespaced: true,
  state: {
    cryptocurrencies: [], // List of all cryptocurrencies
    priceHistory: [], // Price history for selected cryptocurrency
    currentCrypto: null, // Selected cryptocurrency details
  },

  mutations: {
    SET_CRYPTOCURRENCIES(state, cryptos) {
      state.cryptocurrencies = cryptos;
    },
    SET_CRYPTO(state, crypto) {
      state.currentCrypto = crypto;
    },
    ADD_CRYPTO(state, crypto) {
      state.cryptocurrencies.push(crypto);
    },
    UPDATE_CRYPTO(state, updatedCrypto) {
      const index = state.cryptocurrencies.findIndex((c) => c.id === updatedCrypto.id);
      if (index !== -1) {
        state.cryptocurrencies.splice(index, 1, updatedCrypto);
      }
    },
    REMOVE_CRYPTO(state, cryptoId) {
      state.cryptocurrencies = state.cryptocurrencies.filter((crypto) => crypto.id !== cryptoId);
    },
    SET_PRICE_HISTORY(state, history) {
      state.priceHistory = history;
    },
  },

  actions: {
    async fetchCrypto({ commit }, cryptoId) {
      try {
        const token = localStorage.getItem("token");
        const response = await api.get(`/admin/cryptocurrencies/${cryptoId}`, {
          headers: { Authorization: `Bearer ${token}` },
        });
        commit("SET_CRYPTO", response.data);
        return response.data;
      } catch (error) {
        console.error("Error fetching cryptocurrency:", error.response?.data || error);
        throw error;
      }
    },

    async addCrypto({ commit }, crypto) {
      try {
        const token = localStorage.getItem("token");
        const formData = new FormData();
        formData.append("name", crypto.name);
        formData.append("currentPrice", crypto.currentPrice);
        if (crypto.logo) {
          formData.append("logo", crypto.logo);
        }

        const response = await api.post("/admin/cryptocurrencies", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
            Authorization: `Bearer ${token}`,
          },
        });

        commit("ADD_CRYPTO", response.data);
        return response.data;
      } catch (error) {
        console.error("Error adding cryptocurrency:", error.response?.data.errors || error);
        throw error;
      }
    },

    async updateCrypto({ commit }, { id, cryptoData }) {
      try {
        const token = localStorage.getItem("token");
        const formData = new FormData();
        formData.append("name", cryptoData.name);
        formData.append("currentPrice", cryptoData.currentPrice);
        if (cryptoData.logo) {
          formData.append("logo", cryptoData.logo);
        }

        const response = await api.put(`/admin/cryptocurrencies/${id}`, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
            Authorization: `Bearer ${token}`,
          },
        });

        commit("UPDATE_CRYPTO", response.data);
        return response.data;
      } catch (error) {
        console.error("Error updating cryptocurrency:", error.response?.data.errors || error);
        throw error;
      }
    },

    async removeCrypto({ commit }, cryptoId) {
      try {
        const token = localStorage.getItem("token");
        await api.delete(`/admin/cryptocurrencies/${cryptoId}`, {
          headers: { Authorization: `Bearer ${token}` },
        });

        commit("REMOVE_CRYPTO", cryptoId);
      } catch (error) {
        console.error("Error deleting cryptocurrency:", error.response?.data || error);
        throw error;
      }
    },

    async loadPriceHistory({ commit }, { cryptoId, days = 30 }) {
      try {
        const response = await api.get(`/cryptos/${cryptoId}/history?days=${days}`);
  
        if (!response.data || !response.data.original) throw new Error("Données invalides");
  
        const { crypto, history } = response.data.original;
        commit("SET_PRICE_HISTORY", history || []);
        commit("SET_CRYPTO", crypto || null);
      } catch (error) {
        console.error("Erreur lors de la récupération de l'historique des prix", error);
        commit("SET_PRICE_HISTORY", []);
      }
    },
  },

  getters: {
    allCryptos: (state) => state.cryptocurrencies,
    currentCrypto: (state) => state.currentCrypto,
    priceHistory: (state) => state.priceHistory,
    getPriceHistory: (state) => state.priceHistory,
  },
};
