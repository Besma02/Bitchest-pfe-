import api from "@/services/api";

export default {
  async fetchCryptos() {
    try {
      const response = await api.get("/cryptos/current");
      console.log(response.data);
      return response.data;
    } catch (error) {
      throw new Error("Failed to fetch cryptos");
    }
  },

  async addCrypto(formData, token) {
    try {
      const response = await api.post("/admin/cryptos", formData, {
        headers: {
          Authorization: `Bearer ${token}`,
          "Content-Type": "multipart/form-data",
        },
      });
      return response.data;
    } catch (error) {
      const errorMsg =
        error.response?.data?.error || "Failed to add cryptocurrency";
      throw new Error(errorMsg);
    }
  },

  async editCrypto(cryptoId, formData, token) {
    try {
      console.log("Envoi au serveur - ID:", cryptoId);

      formData.append("_method", "PUT"); // C'est ESSENTIEL pour que Laravel comprenne

      const response = await api.post(`/admin/cryptos/${cryptoId}`, formData, {
        headers: {
          Authorization: `Bearer ${token}`,
          "Content-Type": "multipart/form-data",
        },
      });

      console.log("Réponse serveur:", response.data);
      return response.data;
    } catch (error) {
      console.error("Erreur API:", {
        status: error.response?.status,
        data: error.response?.data,
        message: error.message,
      });
      throw error;
    }
  },

  async buyCrypto(cryptoId, quantity, price, token) {
    try {
      const response = await api.post(
        "/crypto/buy",
        { crypto_id: cryptoId, quantity, price },
        { headers: { Authorization: `Bearer ${token}` } }
      );
      return response.data;
    } catch (error) {
      throw new Error("Failed to buy crypto");
    }
  },

  async sellCrypto(cryptoId, quantity, price, token) {
    try {
      const response = await api.post(
        "/crypto/sell",
        {
          crypto_id: cryptoId,
          quantity,
          price,
        },
        {
          headers: { Authorization: `Bearer ${token}` },
        }
      );
      return response.data;
    } catch (error) {
      const normalizedError = new Error(
        error.response?.data?.message || "Échec de la vente"
      );
      normalizedError.response = error.response;
      throw normalizedError;
    }
  },

  async getCryptoData(id, days) {
    try {
      const response = await api.get(`/cryptos/${id}/history?days=${days}`);
      return response.data;
    } catch (error) {
      console.error("Erreur lors du chargement des données crypto:", error);
      throw error;
    }
  },

  async getUserWallet(token) {
    try {
      const response = await api.get("/user/wallet", {
        headers: { Authorization: `Bearer ${token}` },
      });
      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.error || "Failed to fetch user wallet"
      );
    }
  },
};
