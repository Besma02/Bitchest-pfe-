<template>
  <div class="flex min-h-screen bg-gray-50  ">
    <!-- Sidebar gauche -->
    <aside class="w-64 bg-gray-200 text-bitchest-black text-[1rem] pl-9  font-bold flex flex-col justify-between">
      <!-- Logo et Menu -->
      <div >
        <div class="mt-7">
          <img src="@/assets/bitchest_logo.png" alt="Logo" class="h-12 " />
        </div>

        <nav class="mt-6">
          <!-- Tous les liens du menu sont affichés dès le début -->
          <ul>
            <li
              v-for="item in menuItems"
              :key="item.label"
              class="py-3 px-6 mr-9 hover:bg-bitchest-white cursor-pointer"
              @click="navigateTo(item.route)"
            >
              {{ item.label }}
            </li>
          </ul>
        </nav>

        <hr class="border-t border-gray-200 my-4 mx-6" />

        <!-- Actions utilisateur affichées tout de suite -->
        <nav>
          <ul>
            <li
              v-for="item in userActions"
              :key="item.label"
              class="py-3 px-6 mr-9 hover:bg-bitchest-white cursor-pointer "
              @click="navigateTo(item.route)"
            >
              {{ item.label }}
            </li>
          </ul>
        </nav>
      </div>
    </aside>

    <!-- Contenu principal -->
    <main class="flex-1 p-6">
      <router-view />
    </main>

    <!-- Sidebar droite -->
    <aside class="w-72 bg-gray-200 shadow-lg p-6">
      <div class="flex items-center justify-between">
        <!-- Profil utilisateur affiché dès le début -->
        <div class="flex items-center space-x-4">
          <img src="https://via.placeholder.com/40" alt="User Profile" class="rounded-full w-12 h-12" />
          <div>
            <h4 class="text-gray-700 font-bold">{{ user.name }}</h4>
            <p class="text-sm text-gray-500">{{ user.role }}</p>
          </div>
        </div>

        <!-- Notifications -->
        <button class="bg-bitchest-secondary text-white p-2 rounded-full focus:outline-none">🔔</button>
      </div>

      <!-- Zone de statistiques -->
      <div class="mt-6">
        <h4 class="font-bold text-gray-700 mb-4">Statistiques</h4>
        <div class="space-y-4">
          <div v-for="stat in statistics" :key="stat.label" class="flex justify-between items-center">
            <p>{{ stat.label }}</p>
            <span class="font-bold">{{ stat.value }}</span>
          </div>
        </div>
      </div>
    </aside>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "Dashboard",
  data() {
    return {
      user: {
        name: "Chargement...", // Valeur par défaut immédiatement visible
        role: "client", // Valeur par défaut immédiatement visible
      },
      menuItems: [
        { label: "Dashboard", route: "/dashboard" }, // Menu générique par défaut
        { label: "Transactions", route: "/transactions" }, // Menu générique par défaut
      ],
      userActions: [
        { label: "Profile", route: "/profile" },
        { label: "Logout", route: "/logout" },
      ],
      statistics: [
        { label: "Total Transactions", value: "125" },
        { label: "Portfolio Value", value: "$12,345" },
        { label: "Notifications", value: "5" },
      ],
    };
  },
  mounted() {
    this.fetchUserProfile(); // Appel pour récupérer le profil utilisateur immédiatement après le montage
  },
  methods: {
    async fetchUserProfile() {
      try {
        const token = localStorage.getItem("token");
        if (!token) {
          this.$router.push({ name: "login" });
          return;
        }

        const response = await axios.get("http://localhost:8000/api/user-profile", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        // Mise à jour des données utilisateur sans délai visible
        this.user.name = response.data.name;
        this.user.role = response.data.role;

        // Mise à jour dynamique du menu en fonction du rôle de l'utilisateur
        this.setMenuItems();
      } catch (error) {
        console.error("Erreur lors de la récupération du profil utilisateur:", error);
      }
    },

    setMenuItems() {
      // Mise à jour du menu en fonction du rôle utilisateur
      if (this.user.role === "admin") {
        this.menuItems = [
          { label: "Dashboard", route: "/dashboard" },
          { label: "Manage Users", route: "/manage-users" },
          { label: "Manage Crypto", route: "/manage-crypto" },
          { label: "Transactions", route: "/transactions" },
        ];
      } else if (this.user.role === "client") {
        this.menuItems = [
          { label: "Dashboard", route: "/dashboard" },
          { label: "Wallet", route: "/wallet" },
          { label: "Trading & Market", route: "/trading-market" },
          { label: "Transactions", route: "/transactions" },
        ];
      }
    },

    async navigateTo(route) {
      if (route === "/logout") {
        await this.logout();
      } else {
        this.$router.push(route);
      }
    },

    async logout() {
      try {
        const token = localStorage.getItem("token");
        if (!token) {
          console.error("Token manquant");
          return;
        }

        await axios.post(
          "http://localhost:8000/api/logout",
          {},
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        localStorage.removeItem("token");
        this.$router.push({ name: "login" });
      } catch (error) {
        console.error("Échec de la déconnexion:", error);
      }
    },
  },
};
</script>

<style>
/* Ajout de styles responsives pour le dashboard */
@media screen and (max-width: 768px) {
  aside {
    display: none; /* Cache les sidebars pour les petits écrans */
  }

  main {
    padding: 1rem; /* Réduction des marges */
  }
}
</style>
