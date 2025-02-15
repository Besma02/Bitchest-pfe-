import api from "@/services/api";

export default {
  async getCryptoData(id, days) {
    try {
      const response = await api.get(`/cryptos/${id}/history?days=${days}`);
      console.log(response.data);
      return response.data;
    } catch (error) {
      console.error("Erreur lors du chargement des données crypto:", error);
      throw error;
    }
  },
};
