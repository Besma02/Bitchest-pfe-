

import axios from "axios";

export default {
  namespaced: true,
  state: {
    cryptocurrencies: [],
  },
  mutations: {
    SET_CRYPTO(state, crypto) {
        state.cryptocurrencies = [crypto]; 
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
  },
  actions: {
    async fetchCrypto({ commit }, cryptoId) {
      try {
        const token = localStorage.getItem("token");
        const response = await axios.get(`http://127.0.0.1:8000/api/cryptocurrencies/${cryptoId}`, {
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
    
        // Initialize FormData
        const formData = new FormData();
    
        // Append fields to FormData
        formData.append("name", crypto.name);
        formData.append("currentPrice", crypto.currentPrice); // Ensure this is a single value, not an array
    
        // Append the logo file if it exists
        if (crypto.logo && crypto.logo instanceof File) {
          formData.append("logo", crypto.logo);
        }
    
        // Log FormData for debugging
        for (let [key, value] of formData.entries()) {
          console.log(key, value);
        }
    
        // Send the request
        const response = await axios.post(
          "http://127.0.0.1:8000/api/admin/cryptocurrencies",
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data",
              Authorization: `Bearer ${token}`, // Attach token
            },
          }
        );
    
        // Commit the mutation and return the response
        commit("ADD_CRYPTO", response.data);
        return response.data;
      } catch (error) {
        // Handle errors
        if (error.response) {
          console.error("Error adding cryptocurrency:", error.response.data.errors);
        } else {
          console.error("Error adding cryptocurrency:", error);
        }
        throw error; // Re-throw the error to handle it in the component
      }
    },
    
  },
  getters: {
    allCryptos: (state) => state.cryptocurrencies,
  },
};
