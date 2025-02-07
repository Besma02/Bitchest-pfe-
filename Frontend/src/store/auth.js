import axios from "axios";

export default {
  namespaced: true,
  state: {
    user: null,
    token: localStorage.getItem("token") || null,
    isAuthenticated: !!localStorage.getItem("token"),
  },
  mutations: {
    SET_USER(state, { user, token }) {
      state.user = user;
      state.token = token;
      state.isAuthenticated = !!token;
      localStorage.setItem("token", token);
    },
    LOGOUT(state) {
      state.user = null;
      state.token = null;
      state.isAuthenticated = false;
      localStorage.removeItem("token");
    },
  },
  actions: {
    // 🔹 Connexion utilisateur
    async login({ commit }, { email, password }) {
      try {
        const response = await axios.post("http://localhost:8000/api/login", {
          email,
          password,
        });

        if (response.data && response.data.token && response.data.user) {
          commit("SET_USER", {
            user: response.data.user,
            token: response.data.token,
          });
          return response.data; // ✅ Succès
        } else {
          throw new Error("Invalid login response");
        }
      } catch (error) {
        console.error("Échec de connexion:", error);
        throw error; // ❌ Gère l'erreur côté composant Vue
      }
    },

    // 🔹 Récupérer le profil utilisateur
    async fetchProfile({ commit }) {
      try {
        const token = localStorage.getItem("token");
        const response = await axios.get(
          "http://localhost:8000/api/profile",
          {
            headers: { Authorization: `Bearer ${token}` },
          },
          { withCredentials: true }
        );

        commit("SET_USER", { user: response.data, token });
        return response.data;
      } catch (error) {
        console.error("Erreur lors du chargement du profil:", error);
        throw error;
      }
    },

    // 🔹 Mise à jour du profil utilisateur
    async updateProfile(_, formData) {
      try {
        const token = localStorage.getItem("token");

        console.log("Sending API request with:", formData);

        const response = await axios.post(
          "http://localhost:8000/api/profile/update",
          formData,
          {
            headers: {
              Authorization: `Bearer ${token}`,
              "Content-Type": "multipart/form-data", // Indiquer que c'est une requête avec fichier
            },
          },
          { withCredentials: true }
        );

        console.log("Profile update successful:", response.data);
        return response.data;
      } catch (error) {
        console.error("Erreur lors de la mise à jour du profil:", error);
        throw error;
      }
    },
    async changePassword(_, passwordData) {
      try {
        const token = localStorage.getItem("token");

        console.log("Sending API request with:", passwordData);

        const response = await axios.post(
          "http://localhost:8000/api/profile/change-password",
          {
            old_password: passwordData.current_password, // Vérifie que c'est bien "old_password"
            new_password: passwordData.new_password,
            new_password_confirmation: passwordData.new_password_confirmation, // Correcte
          },
          {
            headers: {
              Authorization: `Bearer ${token}`,
              "Content-Type": "application/json",
            },
          }
        );

        console.log("Password change successful:", response.data);
        return response.data;
      } catch (error) {
        console.error("Erreur lors du changement de mot de passe:", error);
        throw error;
      }
    },

    // 🔹 Déconnexion de l'utilisateur
    async logout({ commit }) {
      try {
        const token = localStorage.getItem("token");
        await axios.post(
          "http://localhost:8000/api/logout",
          {},
          { headers: { Authorization: `Bearer ${token}` } }
        );

        commit("LOGOUT");
      } catch (error) {
        console.error("Erreur lors de la déconnexion:", error);
      }
    },
  },
};
