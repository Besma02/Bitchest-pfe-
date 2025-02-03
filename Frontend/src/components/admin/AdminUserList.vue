<template>
  <div class="container mx-auto p-6">
    <h1 class="text-3xl font-semibold mb-8 text-center">User Management</h1>
    <router-link
      to="/admin/users/add"
      class="flex items-center bg-bitchest-success text-[1rem] text-black font-bold px-6 py-2 mb-5 rounded-md hover:bg-gray-200 float-right"
    >
      Add User
    </router-link>
    <div v-if="users.length === 0" class="text-center text-gray-500">
      <p>No users found.</p>
    </div>

    <table v-else class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
      <thead>
        <tr class="text-left text-sm font-semibold text-gray-700 bg-gray-100">
          <th class="px-6 py-3">Photo</th>
          <th class="px-6 py-3">Name</th>
          <th class="px-6 py-3">Email</th>
          <th class="px-6 py-3">Role</th>
          <th class="px-6 py-3">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id" class="border-b border-gray-200">
          <td class="px-6 py-4 text-sm text-gray-700">
            <img
              v-if="user.photo"
              :src="user.photo"
              alt="User Photo"
              class="h-12 w-12 rounded-full object-cover"
            />
            <span v-else class="text-gray-500">No Photo</span>
          </td>
          <td class="px-6 py-4 text-sm text-gray-700">{{ user.name }}</td>
          <td class="px-6 py-4 text-sm text-gray-700">{{ user.email }}</td>
          <td class="px-6 py-4 text-sm text-gray-700">{{ user.role }}</td>
          <td class="px-6 py-4 text-sm text-gray-700 flex space-x-4">
            <button @click="editUser(user.id)" class="text-yellow-500 hover:text-yellow-600">
              <i class="fas fa-edit mr-2 text-bitchest-success"></i>
            </button>
            <button @click="confirmDelete(user.id)" class="text-red-500 hover:text-red-700">
              <i class="fas fa-trash-alt mr-2"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import { useToast } from "vue-toastification";

export default {
  name: "AdminUserList",
  setup() {
    const toast = useToast(); // ✅ Initialisation de Vue Toastification
    return { toast };
  },
  computed: {
    ...mapGetters("users", ["allUsers"]), // 🔥 Utilisation du namespace "users"
    users() {
      return this.allUsers;
    },
  },
  created() {
    this.fetchUsers();
  },
  methods: {
    ...mapActions("users", ["fetchUsers", "deleteUser"]), // 🔥 Utilisation des actions du module "users"

    editUser(userId) {
      this.$router.push({ name: "EditUser", params: { id: userId } });
    },

    async confirmDelete(userId) {
      if (confirm("Are you sure you want to delete this user?")) {
        await this.handleDeleteUser(userId);
      }
    },

    async handleDeleteUser(userId) {
  try {
    await this.deleteUser(userId);
    this.toast.error("User deleted successfully! 🗑️", {
      timeout: 3000,
      position: "top-right",
      closeOnClick: true,
      pauseOnHover: true,
      draggable: true,
      draggablePercent: 0.6,
      showCloseButtonOnHover: true,
      hideProgressBar: false,
      closeButton: "button",
      icon: "❌",
      className: "bg-bitchest-alert text-white font-bold px-4 py-3 rounded shadow-md",
    });
  } catch (error) {
    console.error("Erreur lors de la suppression :", error);
    this.toast.error("Failed to delete user. ❌", {
      className: "bg-red-700 text-white font-bold px-4 py-3 rounded shadow-md",
    });
  }
}

  },
};
</script>
