import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Dashboard from '../views/Dashboard.vue';
import Login from '../views/Login.vue';

import AdminUserList from '../components/admin/AdminUserList.vue';
import AddUserForm from '../components/admin/AddUserForm.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home
      ,
    },
    {
      path: '/login',
      name: 'login',
      component:Login
      
    },
    
    {
      path: "/dashboard",
      name: "dashboard",
      component: Dashboard,
      children: [
        {
          path: '/manage-users',// Sous-route pour la gestion des utilisateurs
          name: 'manage-users',
          component: AdminUserList, // Composant à afficher
        },
        { path: '/admin/users/add', component: AddUserForm },
      ],
    },
    
  ],
});

export default router;
