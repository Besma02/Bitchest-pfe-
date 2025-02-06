<template>
  <div class="container mx-auto p-4 sm:p-6 mr-4 sm:mr-6">
    <h1
      class="text-2xl sm:text-3xl font-bold text-center text-gray-800 mb-4 sm:mb-6"
    >
      User Management
    </h1>

    <div class="flex justify-center sm:justify-end mb-4">
      <router-link
        to="/admin/users/add"
        class="bg-bitchest-success text-black text-sm sm:text-base font-semibold px-4 sm:px-6 py-2 sm:py-3 rounded-md hover:bg-green-300 transition duration-200 shadow-md"
      >
        Add User
      </router-link>
    </div>

    <div
      v-if="users.length === 0"
      class="text-center text-gray-500 text-sm sm:text-base"
    >
      <p>No users found.</p>
    </div>

    <!-- Modal de confirmation -->
    <div
      v-if="showModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center"
    >
      <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-lg font-bold">Confirm Deletion</h2>
        <p class="mt-2">Are you sure you want to delete this user?</p>
        <div class="flex justify-end mt-4">
          <button
            @click="showModal = false"
            class="px-4 py-2 mr-2 bg-gray-300 rounded"
          >
            Cancel
          </button>
          <button
            @click="handleDeleteUser"
            class="px-4 py-2 bg-red-600 text-white rounded"
          >
            Delete
          </button>
        </div>
      </div>
    </div>

    <!-- SECTION : Tableau classique pour lg+ -->
    <div class="overflow-hidden hidden lg:block">
      <table
        class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md"
      >
        <thead class="bg-gray-100 text-gray-700 text-sm font-semibold">
          <tr>
            <th class="px-4 py-2">Photo</th>
            <th class="px-4 py-2">Name</th>
            <th class="px-4 py-2">Email</th>
            <th class="px-4 py-2">Role</th>
            <th class="px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="user in users"
            :key="user.id"
            class="border-b border-gray-200"
          >
            <td class="px-4 py-2 text-sm text-gray-700 text-center">
              <img
                v-if="user.photo"
                :src="user.photo"
                alt="User Photo"
                class="h-12 w-12 rounded-full object-cover mx-auto"
              />
              <span v-else class="text-gray-500">No Photo</span>
            </td>
            <td class="px-4 py-2 text-sm text-gray-700 text-center">
              {{ user.name }}
            </td>
            <td class="px-4 py-2 text-sm text-gray-700 text-center">
              {{ user.email }}
            </td>
            <td class="px-4 py-2 text-sm text-gray-700 text-center">
              {{ user.role }}
            </td>
            <td class="px-4 py-2 text-sm text-gray-700 text-center">
              <button
                @click="editUser(user.id)"
                class="text-yellow-500 hover:text-yellow-600 transition duration-150 mr-2"
              >
                <i class="fas fa-edit text-bitchest-success"></i>
              </button>
              <button
                @click="openModal(user.id)"
                class="text-red-500 hover:text-red-700 transition duration-150"
              >
                <i class="fas fa-trash-alt"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import { useToast } from "vue-toastification";

export default {
  name: "AdminUserList",
  setup() {
    const toast = useToast();
    return { toast };
  },
  data() {
    return {
      showModal: false,
      userIdToDelete: null,
    };
  },
  computed: {
    ...mapGetters("users", ["allUsers"]),
    users() {
      return this.allUsers;
    },
  },
  created() {
    this.fetchUsers();
  },
  methods: {
    ...mapActions("users", ["fetchUsers", "deleteUser"]),

    editUser(userId) {
      this.$router.push({ name: "EditUser", params: { id: userId } });
    },

    openModal(userId) {
      this.userIdToDelete = userId;
      this.showModal = true;
    },

    async handleDeleteUser() {
      try {
        await this.deleteUser(this.userIdToDelete);
        this.toast.error("User deleted successfully! 🗑️", {
          timeout: 3000,
          position: "top-right",
          closeOnClick: true,
          pauseOnHover: true,
          draggable: true,
          showCloseButtonOnHover: true,
          hideProgressBar: false,
          className:
            "bg-bitchest-alert text-white font-bold px-4 py-3 rounded shadow-md",
        });
      } catch (error) {
        console.error("Erreur lors de la suppression :", error);
        this.toast.error("Failed to delete user. ❌", {
          className:
            "bg-red-700 text-white font-bold px-4 py-3 rounded shadow-md",
        });
      }
      this.showModal = false;
      this.userIdToDelete = null;
    },
  },
};
</script>

<style></style>
