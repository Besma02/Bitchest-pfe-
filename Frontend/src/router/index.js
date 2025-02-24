import { createRouter, createWebHistory } from "vue-router";
import Home from "../views/Home.vue";
import Dashboard from "../views/Dashboard.vue";
import Login from "../views/Login.vue";
import ForgotPassword from "../views/ResetPassword.vue";

import AdminUserList from "../components/admin/AdminUserList.vue";
import AddUserForm from "../components/admin/AddUserForm.vue";
import EditUser from "../components/admin/EditUser.vue";
import RegistrationRequestsList from "@/components/admin/RegistrationRequestsList.vue";
import ProfileManager from "@/components/sections/ProfileManager.vue";
import AdmincryptoList from "@/components/admin/cryptos/CryptoList.vue"; 
import AddCryptoForm from "@/components/admin/cryptos/AddCryptoForm.vue";
import EditCryptoForm from "@/components/admin/cryptos/EditCryptoForm.vue"; 
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
      path:"/forgot-password",
      name:"forgot-password",
      component: ForgotPassword,
    },
    {
      path: "/dashboard",
      name: "dashboard",
      component: Dashboard,
      meta: { requiresAuth: true }, 
      children: [
        {
          path: "",
          name: "my-stats",
          component: MyStats,
        },
        {
          path: "registration-requests", 
          name: "registration-requests",
          component: RegistrationRequestsList,
        },
        {
          path: "/manage-users", // Manage users route
          name: "manage-users",
          component: AdminUserList, // Component for listing users
        },
        {
          path: "/admin/users/add", // Pas de "/" au début
          name: "add-user",
          component: AddUserForm,
          path: "/admin/users/add", 
          name: "add-user", 
          component: AddUserForm, 
        },
        {
          path: "/admin/users/edit/:id",
          name: "EditUser",
          component: EditUser,
          path: "admin/users/edit/:id",
          name: "admin-edit-user",
          component: EditUser, 
          props: true, 
        },
        {
          path: "profile",
          name: "profile-manager",
          component: ProfileManager,
        },
        {
          path: "manage-crypto", 
          name: "admin-crypto-list",
          component: AdmincryptoList, 
        },
        {
          path: "admin/crypto/add", 
          name: "admin-add-crypto",   
          component: AddCryptoForm,
        },
        {
          path: "admin/crypto/edit/:id",
          name: "admin-edit-crypto",
          component: EditCryptoForm,
          props: true,  
        },
        {
          path: "crypto/:id", 
          name: "crypto-detail",
          component: CryptoDetails,
          props: true,
        },
        {
          path: "trading-market", 
          name: "client-trade-market",
          component: CryptoList,
          props: { isClient: true },
          path: "/admin/crypto/add", 
          name: "AddCrypto",   
          component: AddCryptoForm,
        },
      ],
    },
  ],
});

// Garde de navigation pour vérifier l'authentification
router.beforeEach((to, from, next) => {
  const isAuthenticated = !!localStorage.getItem("token"); 
  if (to.meta.requiresAuth && !isAuthenticated) {
    next("/login"); 
  } else {
    next(); 
  }
});

export default router;
