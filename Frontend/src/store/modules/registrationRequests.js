import axios from "axios";

export default {
  namespaced: true,
  state: {
    requests: [],
  },
  mutations: {
    setRequests(state, requests) {
      state.requests = requests;
    },
    removeRequest(state, requestId) {
      state.requests = state.requests.filter((req) => req.id !== requestId);
    },
  },
  actions: {
    async fetchRequests({ commit }) {
      try {
        const token = localStorage.getItem("token"); // 🔹 Récupérer le token du localStorage
        if (!token)
          throw new Error("Aucun token trouvé, utilisateur non authentifié.");

        const response = await axios.get(
          "http://localhost:8000/api/admin/registration-requests",
          {
            headers: {
              Authorization: `Bearer ${token}`, // ✅ Ajouter le token
              Accept: "application/json",
            },
          }
        );

        commit("setRequests", response.data);
      } catch (error) {
        console.error("Erreur lors du chargement des demandes :", error);
        throw error;
      }
    },

    async deleteRequest({ commit }, requestId) {
      try {
        const token = localStorage.getItem("token"); // 🔹 Récupérer le token
        if (!token)
          throw new Error("Aucun token trouvé, utilisateur non authentifié.");

        await axios.delete(
          `http://localhost:8000/api/admin/registration-requests/${requestId}`,
          {
            headers: {
              Authorization: `Bearer ${token}`, // ✅ Ajouter le token
              Accept: "application/json",
            },
          }
        );

        commit("removeRequest", requestId);
      } catch (error) {
        console.error("Erreur lors de la suppression de la demande :", error);
        throw error;
      }
    },
  },
  getters: {
    allRequests: (state) => state.requests,
  },
};
