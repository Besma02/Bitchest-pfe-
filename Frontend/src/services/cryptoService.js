// src/services/cryptoService.js
import axios from "axios";

const API_URL = "http://localhost:8000/api/cryptos/current";

export default {
  async fetchCryptos() {
    try {
      const response = await axios.get(API_URL);
      return response.data;
    } catch (error) {
      throw new Error("Failed to fetch cryptos");
    }
  }
};
