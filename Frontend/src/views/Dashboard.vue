<template>
  <!-- Loader : s'affiche tant que les données ne sont pas chargées -->
  <div
    v-if="isLoading"
    class="fixed inset-0 flex items-center justify-center bg-white z-50"
  >
    <Loader />
  </div>
  <div v-else class="flex flex-col md:flex-row min-h-screen bg-gray-100">
    <!-- Sidebar gauche -->
    <aside
      class="w-64 bg-sidebar-bg text-bitchest-black font-bold flex flex-col justify-between min-h-screen md:relative fixed inset-y-0 left-0 transform transition-transform duration-300 ease-in-out"
      :class="{ '-translate-x-full': isSmallScreen && !isSidebarOpen }"
    >
      <div>
        <div class="p-6 flex justify-between items-center">
          <img
            src="@/assets/bitchest_logo.png"
            alt="Logo"
            class="h-12 mx-auto"
          />
          <button
            v-if="isSmallScreen"
            @click="toggleSidebar"
            class="text-bitchest-black text-2xl"
          >
            ✖
          </button>
        </div>
        <nav>
          <ul>
            <li
              v-for="item in menuItems"
              :key="item.label"
              class="py-3 px-6 hover:bg-gray-200 cursor-pointer flex items-center"
              :class="{ 'bg-gray-200': isActive(item.route) }"
              @click="navigateTo(item.route)"
            >
              <img
                :src="item.icon"
                alt="icon"
                class="w-5 h-5 mr-2"
                v-if="item.icon"
              />
              {{ item.label }}
            </li>
          </ul>
        </nav>
        <hr class="border-t border-gray-200 my-4 mx-6" />
        <nav>
          <ul>
            <li
              class="py-3 px-6 hover:bg-gray-200 cursor-pointer flex items-center"
              :class="{ 'bg-gray-200': isActive('/profile') }"
              @click="navigateTo('/profile')"
            >
              <img :src="profileIcon" alt="icon" class="w-5 h-5 mr-2" />
              Profile
            </li>
            <li
              class="py-3 px-6 hover:bg-bitchest-alert cursor-pointer flex items-center"
              @click="logout"
            >
              <img :src="logoutIcon" alt="icon" class="w-7 h-7 mr-2" />
              Logout
            </li>
          </ul>
        </nav>
      </div>
    </aside>

    <!-- Contenu principal -->
    <div class="flex-1 flex flex-col">
      <!-- Barre de navigation supérieure -->
      <header class="bg-white shadow p-4 flex justify-end items-center gap-10">
        <button
          class="md:hidden bg-gray-200 text-white px-3 py-2 rounded"
          @click="toggleSidebar"
        >
          ☰
        </button>
        <h1 class="text-xl font-bold text-gray-700">Dashboard</h1>

        <button class="ml-4 relative" @click="toggleNotifications">
          <span
            class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full px-1"
            >5</span
          >
          <img
            src="@/assets/icons/notification.svg"
            alt="Notifications"
            class="w-6 h-6"
          />
        </button>
        <img
          v-if="isSmallScreen"
          :src="user.photo ? user.photo : '/images/unknown.png'"
          alt="User Profile"
          class="w-8 h-8 rounded-full ml-4"
        />
      </header>

      <main class="flex-1 p-6 bg-white overflow-x-auto">
        <router-view />
      </main>
    </div>

    <!-- Sidebar droite (User Sidebar) -->
    <aside
      v-if="!isLoading"
      :class="[
        'w-64 bg-sidebar-bg p-6 fixed right-0 top-0 h-full transform transition-transform duration-300',
        isUserSidebarOpen ? 'translate-x-0' : 'translate-x-full',
        'md:relative md:translate-x-0',
      ]"
    >
      <button
        class="md:hidden text-gray-600 text-2xl absolute top-4 left-4"
        @click="toggleUserSidebar"
      >
        ✖
      </button>

      <div class="text-center">
        <img
          :src="user.photo ? user.photo : '/images/unknown.png'"
          alt="User Profile"
          class="w-16 h-16 rounded-full mx-auto"
        />
        <h2 class="text-lg font-semibold mt-2">{{ user.name }}</h2>
        <p class="text-sm text-gray-500">{{ user.role }}</p>
      </div>

      <div class="mt-6">
        <h4 class="font-bold text-gray-700 mb-4">Statistiques</h4>
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
import axios from "axios";
import MyStats from "@/components/sections/MyStats.vue";
import RegistrationRequestsList from "@/components/admin/RegistrationRequestsList.vue";
import ProfileManager from "@/components/sections/ProfileManager.vue";
import AdminUserList from "@/components/admin/AdminUserList.vue";
import Loader from "@/components/utils/Loader.vue";

import dashboardIcon from "@/assets/icons/dashboard.png";
import registrationIcon from "@/assets/icons/alerts.png";
import transactionsIcon from "@/assets/icons/transactions.png";
import usersIcon from "@/assets/icons/user.png";
import cryptoIcon from "@/assets/icons/crypto.png";
import walletIcon from "@/assets/icons/wallet.png";
import tradingIcon from "@/assets/icons/trading.png";
import logoutIcon from "@/assets/icons/logout.png";
import profileIcon from "@/assets/icons/settings.png";

export default {
  name: "Dashboard",
  components: {
    MyStats,
    RegistrationRequestsList,
    ProfileManager,
    AdminUserList,
    Loader,
  },
  data() {
    return {
      isLoading: true, // Afficher Loader au début
      isSidebarOpen: false,
      isSmallScreen: window.innerWidth <= 768,
      user: {
        name: "Chargement...",
        role: "client",
        photo: null,
      },
      currentView: "MyStats",
      menuItems: [],
      profileIcon,
      logoutIcon, // Utilisation des icônes importées
    };
  },
  computed: {
    filteredMenuItems() {
      return this.user.role === "admin"
        ? [
            {
              label: "Dashboard",
              route: "/dashboard",
              component: "MyStats",
              icon: dashboardIcon,
            },
            {
              label: "Registration Requests",
              route: "/registration-requests",
              component: "RegistrationRequestsList",
              icon: registrationIcon,
            },
            {
              label: "Transactions",
              route: "/transactions",
              component: "TransactionsList",
              icon: transactionsIcon,
            },
            {
              label: "Manage Users",
              route: "/manage-users",
              component: "AdminUserList",
              icon: usersIcon,
            },
            {
              label: "Manage Crypto",
              route: "/manage-crypto",
              component: "CryptoManagement",
              icon: cryptoIcon,
            },
          ]
        : [
            {
              label: "Dashboard",
              route: "/dashboard",
              component: "MyStats",
              icon: dashboardIcon,
            },
            {
              label: "Transactions",
              route: "/transactions",
              component: "TransactionsList",
              icon: transactionsIcon,
            },
            {
              label: "Wallet",
              route: "/wallet",
              component: "WalletView",
              icon: walletIcon,
            },
            {
              label: "Trading & Market",
              route: "/trading-market",
              component: "TradingMarket",
              icon: tradingIcon,
            },
          ];
    },
  },
  async created() {
    await this.fetchUserProfile();
    this.isLoading = false; // Désactiver le Loader après chargement
    this.menuItems = this.filteredMenuItems;
    window.addEventListener("resize", this.checkScreenSize);
  },
  beforeUnmount() {
    window.removeEventListener("resize", this.checkScreenSize);
  },
  methods: {
    async fetchUserProfile() {
      try {
        const token = localStorage.getItem("token");
        if (!token) {
          this.$router.push({ name: "login" });
          return;
        }

        const response = await axios.get(
          "http://localhost:8000/api/user-profile",
          {
            headers: { Authorization: `Bearer ${token}` },
          }
        );

        this.user = {
          name: response.data.name,
          role: response.data.role,
          photo: response.data.photo
            ? `http://localhost:8000/storage/${response.data.photo}`
            : null,
        };

        this.menuItems = this.filteredMenuItems; // Mettre à jour le menu

        this.$forceUpdate(); // Forcer la mise à jour du DOM pour afficher les nouvelles infos utilisateur
      } catch (error) {
        console.error(
          "Erreur lors de la récupération du profil utilisateur:",
          error
        );
      } finally {
        this.isLoading = false; // Désactiver le Loader après chargement
      }
    },

    navigateTo(route) {
      this.$router.push(route);
      this.menuItems = this.filteredMenuItems; // Mettre à jour le menu après navigation
    },

    isActive(route) {
      return this.$route.path === route;
    },
    toggleSidebar() {
      this.isSidebarOpen = !this.isSidebarOpen;
    },
    checkScreenSize() {
      this.isSmallScreen = window.innerWidth <= 768;
    },
    async logout() {
      localStorage.removeItem("token");
      this.$router.push({ name: "login" });
    },
  },
};
</script>
