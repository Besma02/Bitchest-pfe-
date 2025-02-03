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
  },
  actions: {
    async login({ commit }, { email, password }) {
      try {
        await axios.get("/sanctum/csrf-cookie"); // Ensure CSRF token is set
        const response = await axios.post("http://localhost:8000/api/login", { email, password });

        if (response.data && response.data.token && response.data.user) {
          commit("SET_USER", { user: response.data.user, token: response.data.token });
          return response.data; // ✅ Return the response to `handleLogin`
        } else {
          throw new Error("Invalid login response");
        }
      } catch (error) {
        console.error("Login failed:", error);
        throw error; // ❌ Make sure error is properly handled
      }
    },
  },
};