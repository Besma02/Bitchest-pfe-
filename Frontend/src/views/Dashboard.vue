<template>
  <div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar gauche -->
    <aside
      class="w-64 bg-gray-200 text-bitchest-white flex flex-col justify-between"
    >
      <!-- Logo et Menu -->
      <div>
        <div class="p-6">
          <img
            src="@/assets/bitchest_logo.png"
            alt="Logo"
            class="h-12 mx-auto"
          />
        </div>

        <nav class="mt-6">
          <ul>
            <li
              v-for="item in menuItems"
              :key="item.label"
              class="py-3 px-6 hover:bg-bitchest-secondary cursor-pointer"
              @click="navigateTo(item.route)"
            >
              {{ item.label }}
            </li>
          </ul>
        </nav>

        <hr class="border-t border-gray-200 my-4 mx-6" />

        <!-- Profile et Logout -->
        <nav>
          <ul>
            <li
              v-for="item in userActions"
              :key="item.label"
              class="py-3 px-6 hover:bg-bitchest-secondary cursor-pointer"
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
        <!-- Profil utilisateur -->
        <div class="flex items-center space-x-4">
          <img
            src="https://via.placeholder.com/40"
            alt="User Profile"
            class="rounded-full w-12 h-12"
          />
          <div>
            <h4 class="text-gray-700 font-bold">{{ user.name }}</h4>
            <p class="text-sm text-gray-500">{{ user.role }}</p>
          </div>
        </div>

        <!-- Notifications -->
        <button
          class="bg-bitchest-secondary text-white p-2 rounded-full focus:outline-none"
        >
          🔔
        </button>
      </div>

      <!-- Zone de statistiques -->
      <div class="mt-6">
        <h4 class="font-bold text-gray-700 mb-4">Statistics</h4>
        <div class="space-y-4">
          <div
            v-for="stat in statistics"
            :key="stat.label"
            class="flex justify-between items-center"
          >
            <p>{{ stat.label }}</p>
            <span class="font-bold">{{ stat.value }}</span>
          </div>
        </div>
      </div>
    </aside>
  </div>
</template>

<script>
export default {
  name: "Dashboard",
  data() {
    return {
      user: {
        name: "John Doe", // Exemple de nom d'utilisateur
        role: "Client", // Ou 'Admin'
      },
      menuItems: [],
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
    this.setMenuItems();
  },
  methods: {
    setMenuItems() {
      // Définit le menu selon le rôle de l'utilisateur
      if (this.user.role === "Client") {
        this.menuItems = [
          { label: "Dashboard", route: "/dashboard" },
          { label: "Wallet", route: "/wallet" },
          { label: "Trading & Market", route: "/trading" },
          { label: "Transactions", route: "/transactions" },
          { label: "Custom Alerts", route: "/alerts" },
        ];
      } else if (this.user.role === "Admin") {
        this.menuItems = [
          { label: "Dashboard", route: "/dashboard" },
          { label: "Manage Users", route: "/manage-users" },
          { label: "Manage Crypto", route: "/manage-crypto" },
          { label: "Transactions", route: "/transactions" },
        ];
      }
    },
    navigateTo(route) {
      // Redirige vers la page correspondante
      this.$router.push(route);
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
