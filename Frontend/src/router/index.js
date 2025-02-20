import { createRouter, createWebHistory } from "vue-router";
import Home from "../views/Home.vue";
import Dashboard from "../views/Dashboard.vue";
import Login from "../views/Login.vue";

import AdminUserList from "../components/admin/AdminUserList.vue";
import AddUserForm from "../components/admin/AddUserForm.vue";
import EditUser from "../components/admin/EditUser.vue";
import RegistrationRequestsList from "@/components/admin/RegistrationRequestsList.vue";
import ProfileManager from "@/components/sections/ProfileManager.vue";
import AdmincryptoList from "@/components/admin/cryptos/CryptoList.vue"; 
import AddCryptoForm from "@/components/admin/cryptos/AddCryptoForm.vue";
import EditCryptoForm from "@/components/admin/cryptos/EditCryptoForm.vue"; 

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
          path: "/manage-users", // Manage users route
          name: "manage-users",
          component: AdminUserList, // Component for listing users
        },
        {
          path: "/admin/users/add", 
          name: "add-user", 
          component: AddUserForm, 
        },
        {
          path: "/admin/users/edit/:id",
          name: "edit-user",
          component: EditUser, 
        },
        {
          path: "/profile",
          name: "profile-manager",
          component: ProfileManager,
        },
        {
          path: "/manage-crypto", // Manage cryptocurrencies
          name: "manage-crypto",
          component: AdmincryptoList, 
        },
        {
          path: "/admin/crypto/add", 
          name: "add-crypto", 
          component: AddCryptoForm, 
        },
        {
          path: '/admin/crypto/edit/:id',
          name: 'EditCrypto',
          component: EditCryptoForm,
          props: true,  
        },
        {
          path: "/admin/crypto/add", 
          name: "AddCrypto",   
          component: AddCryptoForm,
        },
      ],
    },
  ],
});

export default router;
