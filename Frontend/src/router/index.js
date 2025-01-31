import { createRouter, createWebHistory } from "vue-router";
import Home from "../views/Home.vue";
import Login from "../views/Login.vue";
import Dashboard from "../components/Dashboard.vue";
import store from "../store"; // Import Vuex store

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: "/", name: "home", component: Home },
    { path: "/login", name: "login", component: Login },
    { path: "/dashboard", name: "dashboard", component: Dashboard, meta: { requiresAuth: true } }, // 🚀 Protected
  ],
});

// 🚀 Secure Routes: Check Authentication Before Navigating
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem("token"); // ✅ Check localStorage for authentication

  if (to.meta.requiresAuth && !token) {
    next("/login"); // Redirect to login if not authenticated
  } else {
    next();
  }
});

export default router;
