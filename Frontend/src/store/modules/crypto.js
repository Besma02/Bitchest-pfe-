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
