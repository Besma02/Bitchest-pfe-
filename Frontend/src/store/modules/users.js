import axios from "axios";

export default {
  namespaced: true,
  state: {
    users: [],
  },
  mutations: {
    setUsers(state, users) {
      state.users = users;
    },
    addUser(state, user) {
      state.users.push(user);
    },
    updateUser(state, updatedUser) {
      const index = state.users.findIndex((user) => user.id === updatedUser.id || user.email === updatedUser.email);
      if (index !== -1) {
        state.users.splice(index, 1, updatedUser);
      }
    },
    removeUser(state, userId) {
      state.users = state.users.filter((user) => user.id !== userId);
    },
  },
  actions: {
    async fetchUsers({ commit }) {
      try {
        const token = localStorage.getItem("token");
        const response = await axios.get("http://localhost:8000/api/admin/users", {
          headers: { Authorization: `Bearer ${token}` },
        });

        const usersWithPhotos = response.data.map((user) => ({
          ...user,
          photo: user.photo ? `http://localhost:8000/storage/${user.photo}` : null,
        }));

        commit("setUsers", usersWithPhotos);
      } catch (error) {
        console.error("Erreur lors du chargement des utilisateurs:", error);
      }
    },
    async addUser({ commit }, formData) {
      const token = localStorage.getItem("token");

      // Création d'un utilisateur temporaire pour affichage immédiat
      const tempUser = {
        id: Date.now(), // ID temporaire avant la réponse de l'API
        name: formData.get("name"),
        email: formData.get("email"),
        role: formData.get("role"),
        photo: formData.get("photo") ? URL.createObjectURL(formData.get("photo")) : null, // Affichage direct si image
        isTemp: true, // Marqueur temporaire
      };

      commit("addUser", tempUser); // Ajout immédiat pour réactivité

      try {
        const response = await axios.post("http://localhost:8000/api/admin/users", formData, {
          headers: {
            Authorization: `Bearer ${token}`,
            "Content-Type": "multipart/form-data",
          },
        });

        // Remplacement de l'utilisateur temporaire par celui de l'API
        commit("updateUser", response.data);
      } catch (error) {
        console.error("Erreur lors de l'ajout de l'utilisateur:", error);

        // Suppression du user temporaire si l'ajout échoue
        commit("removeUser", tempUser.id);
        throw error;
      }
    },
    async fetchUser(_, userId) {
      try {
        const token = localStorage.getItem("token");
        const response = await axios.get(`http://localhost:8000/api/admin/users/${userId}`, {
          headers: { Authorization: `Bearer ${token}` },
        });
        return response.data;
      } catch (error) {
        console.error("Erreur lors de la récupération de l'utilisateur:", error);
        throw error;
      }
    },
    async updateUser({ commit }, { id, userData }) {
      try {
        const token = localStorage.getItem("token");
        const formData = new FormData();
        formData.append("name", userData.name);
        formData.append("email", userData.email);
        formData.append("role", userData.role);
        if (userData.photo) {
          formData.append("photo", userData.photo);
        }
        formData.append("_method", "PUT"); // Laravel supporte `PUT` via FormData

        const response = await axios.post(`http://localhost:8000/api/admin/users/${id}`, formData, {
          headers: { Authorization: `Bearer ${token}`, "Content-Type": "multipart/form-data" },
        });

        commit("updateUser", response.data);
      } catch (error) {
        console.error("Erreur lors de la mise à jour de l'utilisateur:", error);
        throw error;
      }
    },
    async deleteUser({ commit }, userId) {
      try {
        const token = localStorage.getItem("token");
        await axios.delete(`http://localhost:8000/api/admin/users/${userId}`, {
          headers: { Authorization: `Bearer ${token}` },
        });

        commit("removeUser", userId);
      } catch (error) {
        console.error("Erreur lors de la suppression de l'utilisateur:", error);
      }
    },
  },
  getters: {
    allUsers: (state) => state.users,
  },
};