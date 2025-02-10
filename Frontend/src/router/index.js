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
      children: [
        {
          path: "/registration-requests",
          name: "registration-requests",
          component: RegistrationRequestsList,
        },
        {
          path: "/manage-users", // Sous-route pour la gestion des utilisateurs
          name: "manage-users",
          component: AdminUserList, // Composant à afficher
        },
        { path: "/admin/users/add", component: AddUserForm },
        {
          path: "/admin/users/edit/:id",
          name: "EditUser",
          component: EditUser,
        },
        {
          path: "/profile",
          name: "EditProfile",
          component: ProfileManager,
        },
        {
          path: "/manage-crypto", // Sous-route pour la gestion des cryptos
          name: "manage-crypto",
          component: CryptoList, // Composant à afficher
          props: { isClient: false } 
        },

        {
          path: "/trading-market", 
          name: "TradeMarket",
          component:CryptoList ,
          props: { isClient: true }  
        },
      ],
    },
  ],
});

export default router;
