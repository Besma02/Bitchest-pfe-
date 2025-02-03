<template>
  <div class="flex flex-col md:flex-row min-h-screen">
    <!-- Barre horizontale (remplace la sidebar droite en sm/md) -->
    <div v-if="isSmallScreen" class="w-full bg-gray-200 shadow-md p-4 flex justify-between items-center">
      <div class="flex items-center space-x-4 ml-auto">
        <!-- Profil utilisateur -->
        <div class="flex items-center space-x-4">
          <img src="https://via.placeholder.com/40" alt="User Profile" class="rounded-full w-10 h-10" />
          <div>
            <h4 class="text-gray-700 font-bold">{{ user.name }}</h4>
            <p class="text-sm text-gray-500">{{ user.role }}</p>
          </div>
        </div>

        <!-- Notifications -->
        <button class="bg-bitchest-secondary text-white p-2 rounded-full">🔔</button>
      </div>
    </div>

    <div class="flex flex-1">
      <!-- Sidebar gauche -->
      <aside :class="{
          'w-64': !isMenuOpen,
          'w-20': isMenuOpen,
          'md:w-64': true,
          'sm:w-full': isMenuOpen,
          'sm:transform-none': !isMenuOpen,
          'sm:translate-x-0': isMenuOpen,
          'bg-bitchest-primary': !isSmallScreen
        }"
        :style="{ backgroundColor: isSmallScreen ? 'transparent' : '#E5E5E5' }"
        class="text-bitchest-black text-[1rem] pl-9 font-bold flex flex-col justify-between transition-all">
        
        <!-- Logo et Menu -->
        <div>
          <div class="mt-7 hidden sm:block">
            <img src="@/assets/bitchest_logo.png" alt="Logo" class="h-12" />
          </div>

          <!-- Menu Hamburger -->
          <button @click="toggleMenu" class="md:hidden p-2 mt-4 bg-bitchest-white text-black rounded">
            &#9776;
          </button>

          <!-- Menu avec liens -->
          <nav :class="{'block': isMenuOpen, 'hidden': !isMenuOpen}" class="md:block mt-6">
            <ul>
              <li v-for="item in filteredMenuItems" :key="item.label"
                class="py-2 px-6 mr-9 hover:bg-bitchest-white cursor-pointer"
                @click="navigateTo(item.route)">
                {{ item.label }}
              </li>

              <!-- Ajout des liens "Profile" et "Logout" dans le menu mobile -->
              <li class="py-2 px-6 mr-9 hover:bg-bitchest-white cursor-pointer" @click="navigateTo('/profile')">
                Profile
              </li>
              <li class="py-2 px-6 mr-9 hover:bg-bitchest-white cursor-pointer" @click="navigateTo('/logout')">
                Logout
              </li>
            </ul>
          </nav>
        </div>
      </aside>

      <!-- Contenu principal -->
      <main class="flex-1 p-6">
        <router-view />
      </main>

      <!-- Sidebar droite (affichée uniquement sur les grands écrans) -->
      <aside v-if="!isSmallScreen" class="w-72 bg-gray-200 shadow-lg p-6">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <img src="https://via.placeholder.com/40" alt="User Profile" class="rounded-full w-12 h-12" />
            <div>
              <h4 class="text-gray-700 font-bold">{{ user.name }}</h4>
              <p class="text-sm text-gray-500">{{ user.role }}</p>
            </div>
          </div>
          <button class="bg-bitchest-secondary text-white p-2 rounded-full">🔔</button>
        </div>

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
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "Dashboard",
  data() {
    return {
      isMenuOpen: false, // Contrôle l'état du menu hamburger
      isSmallScreen: false, // Détermine si l'écran est petit ou moyen
      user: {
        name: "Chargement...", // Valeur par défaut immédiatement visible
        role: "client", // Valeur par défaut immédiatement visible
      },
      menuItems: [
        { label: "Dashboard", route: "/dashboard" },
        { label: "Transactions", route: "/transactions" },
        { label: "Manage Users", route: "/manage-users" },
        { label: "Manage Crypto", route: "/manage-crypto" },
        { label: "Wallet", route: "/wallet" },
        { label: "Trading & Market", route: "/trading-market" },
      ],
      statistics: [
        { label: "Total Transactions", value: "125" },
        { label: "Portfolio Value", value: "$12,345" },
        { label: "Notifications", value: "5" },
      ],
    };
  },
  computed: {
    // Filtrer les éléments de menu selon le rôle de l'utilisateur
    filteredMenuItems() {
      if (this.user.role === 'admin') {
        return this.menuItems.filter(item => 
          item.label === 'Dashboard' || item.label === 'Transactions' || item.label === 'Manage Users' || item.label === 'Manage Crypto'
        );
      }
      return this.menuItems.filter(item => 
        item.label === 'Dashboard' || item.label === 'Wallet' || item.label === 'Trading & Market' || item.label === 'Transactions'
      );
    }
  },
  mounted() {
    this.fetchUserProfile(); // Appel pour récupérer le profil utilisateur immédiatement après le montage
    this.checkScreenSize(); // Vérifie la taille de l'écran au démarrage
    window.addEventListener("resize", this.checkScreenSize); // Surveille le redimensionnement de la fenêtre
  },
  beforeDestroy() {
    window.removeEventListener("resize", this.checkScreenSize); // Nettoie l'événement lors de la destruction du composant
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
      } catch (error) {
        console.error("Erreur lors de la récupération du profil utilisateur:", error);
      }
    },

    checkScreenSize() {
      // Met à jour la variable isSmallScreen en fonction de la taille de l'écran
      this.isSmallScreen = window.innerWidth <= 768; // Si l'écran est plus petit que 768px (sm/md)
    },

    toggleMenu() {
      this.isMenuOpen = !this.isMenuOpen; // Bascule l'état du menu
    },

    async navigateTo(route) {
      if (route === "/logout") {
        await this.logout();
      } else {
        this.$router.push(route);
        if (this.isMenuOpen) {
          this.toggleMenu(); // Ferme le menu après la navigation
        }
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

<style scoped>
/* Styles responsives pour le dashboard */
@media screen and (max-width: 768px) {
  aside {
    position: fixed;
    z-index: 50;
    top: 0;
    left: 0%;
    height: 100%;
    transform: translateX(0%);
    transition: transform 0.3s ease-in-out;
  }
  aside ul {
    margin-top: -20px;
    margin-left: -20px;
  }
  aside li {
    font-size: 12px;
    line-height: 10px;
    width: 120px;
  }

  aside.w-64 {
    transform: translateX(0);
  }

  main {
    margin-left: 0;
    padding: 1rem;
  }

  .flex-1 {
    display: block;
    margin-top: 60px;
  }

  button.md:hidden {
    display: block;
  }

  /* Cacher la sidebar droite en sm/md */
  aside.w-72 {
    display: none;
  }
}
@media screen and (max-width: 768px) {
  aside.w-72 {
    display: none; /* Cacher la sidebar droite en sm/md */
  }
}
</style>
