import axios from "axios";

export default {
  namespaced: true,
  state: {
    cryptocurrencies: [],
    currentCrypto: null,
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
  },
  actions: {
    async fetchCryptos({ commit }) {
      try {
        const token = localStorage.getItem("token");
        const response = await axios.get("/api/cryptocurrencies", {
          headers: { Authorization: `Bearer ${token}` },
        });
        commit("SET_CRYPTOCURRENCIES", response.data);
      } catch (error) {
        console.error("Error fetching cryptocurrencies:", error.response?.data || error);
        throw error;
      }
    },
    async fetchCrypto({ commit }, cryptoId) {
      try {
        const token = localStorage.getItem("token");
        const response = await axios.get(`/api/admin/cryptocurrencies/${cryptoId}`, {
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

        const response = await axios.post("/api/admin/cryptocurrencies", formData, {
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

        const response = await axios.put(`/api/admin/cryptocurrencies/${id}`, formData, {
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
        await axios.delete(`/api/admin/cryptocurrencies/${cryptoId}`, {
          headers: { Authorization: `Bearer ${token}` },
        });

        commit("REMOVE_CRYPTO", cryptoId);
      } catch (error) {
        console.error("Error deleting cryptocurrency:", error.response?.data || error);
        throw error;
      }
    },
  },
  getters: {
    allCryptos: (state) => state.cryptocurrencies,
    currentCrypto: (state) => state.currentCrypto,
  },
};
