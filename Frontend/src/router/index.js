import { createRouter, createWebHistory } from "vue-router";
import Home from "../views/Home.vue";
import Dashboard from "../views/Dashboard.vue";
import Login from "../views/Login.vue";

import AdminUserList from "../components/admin/AdminUserList.vue";
import AddUserForm from "../components/admin/AddUserForm.vue";
import EditUser from "../components/admin/EditUser.vue";
import RegistrationRequestsList from "@/components/admin/RegistrationRequestsList.vue";
import ProfileManager from "@/components/sections/ProfileManager.vue";
import CryptoList from "@/components/admin/cryptos/CryptoList.vue";
import CryptoDetails from "@/components/sections/CryptoDetails.vue";
import MyStats from "@/components/sections/MyStats.vue";
import CryptoWallet from "@/components/wallet/CryptoWallet.vue";
import Transactions from "@/components/Transactions.vue";
import CryptoPurchaseDetails from "@/components/wallet/CryptoPurchaseDetails.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: Home,
    },
    {
      path: "/login",
      name: "login",
      component: Login,
    },
    {
      path: "/dashboard",
      name: "dashboard",
      component: Dashboard,
      meta: { requiresAuth: true }, // Route protégée
      children: [
        {
          path: "",
          name: "my-stats",
          component: MyStats,
        },
        {
          path: "registration-requests", // Pas de "/" au début
          name: "registration-requests",
          component: RegistrationRequestsList,
        },
        {
          path: "manage-users", // Pas de "/" au début
          name: "manage-users",
          component: AdminUserList,
        },
        {
          path: "/admin/users/add", // Pas de "/" au début
          name: "add-user",
          component: AddUserForm,
        },
        {
          path: "/admin/users/edit/:id",
          name: "EditUser",
          component: EditUser,
        },
        {
          path: "profile",
          name: "profile",
          component: ProfileManager,
        },
        {
          path: "manage-crypto", // Pas de "/" au début
          name: "manage-crypto",
          component: CryptoList,
          props: { isClient: false }, // Passage de props
        },
        {
          path: "crypto/:id", // Pas de "/" au début
          name: "crypto-detail",
          component: CryptoDetails,
          props: true, // Permet de passer les paramètres de route comme props
        },
        {
          path: "trading-market", // Pas de "/" au début
          name: "trade-market",
          component: CryptoList,
          props: { isClient: true }, // Passage de props
        },
      //affiche des achats
        {
          path: "wallet", 
          name: "crypto-wallet",
          component: CryptoWallet,
        },
        //cryptoPurchaseDetails
        {
          path: '/crypto/:id/purchases',
          name: 'cryptoPurchaseDetails',
          component: CryptoPurchaseDetails, // Le composant où vous affichez l'historique des achats
           props: true
        },
        //affiche des transactions
        {
          path: "transactions", 
          name: "transactions",
          component: Transactions,
        }
      ],
    },
  ],
});

// Garde de navigation pour vérifier l'authentification
router.beforeEach((to, from, next) => {
  const isAuthenticated = localStorage.getItem("token"); // Vérifiez si l'utilisateur est authentifié
  if (to.meta.requiresAuth && !isAuthenticated) {
    next("/login"); // Redirigez vers la page de connexion
  } else {
    next(); // Continuez la navigation
  }
});

export default router;
