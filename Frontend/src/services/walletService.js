import api from "@/services/api";

export default {
  async getWalletCryptos(token) {
    try {
      const response = await api.get('/crypto/wallet', {
        headers: { Authorization: `Bearer ${token}` },
      });
      return response.data;
    } catch (error) {
      throw new Error(error.response?.data?.error || "Failed to load wallet.");
    }
  },
 
  
};
