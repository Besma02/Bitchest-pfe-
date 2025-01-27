<template>
  <div class="container mx-auto p-6">
    <h1 class="text-3xl font-semibold mb-8 text-center">User Management</h1>
    <router-link to="/admin/users/add" class="flex items-center bg-bitchest-success text-[1rem] text-black font-bold px-6 py-2 mb-5 rounded-md hover:bg-gray-200 float-right">
      Add User
    </router-link>
    <div v-if="users.length === 0" class="text-center text-gray-500">
      <p>No users found.</p>
    </div>
    <!-- Bouton Ajouter un utilisateur -->

    <!-- Tableau des utilisateurs -->
    <table
      v-else
      class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md"
    >
      <thead>
        <tr class="text-left text-sm font-semibold text-gray-700 bg-gray-100">
          <th class="px-6 py-3">Name</th>
          <th class="px-6 py-3">Email</th>
          <th class="px-6 py-3">Role</th>
          <th class="px-6 py-3">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="user in users"
          :key="user.id"
          class="border-b border-gray-200"
        >
          <td class="px-6 py-4 text-sm text-gray-700">{{ user.name }}</td>
          <td class="px-6 py-4 text-sm text-gray-700">{{ user.email }}</td>
          <td class="px-6 py-4 text-sm text-gray-700">{{ user.role }}</td>
          <td class="px-6 py-4 text-sm text-gray-700 flex space-x-4">
            <!-- Bouton Editer -->
            <button
              @click="editUser(user.id)"
              class="text-yellow-500 hover:text-yellow-600"
            >
              <i class="fas fa-edit mr-2"></i> Edit
            </button>

            <!-- Bouton Supprimer -->
            <button
              @click="deleteUser(user.id)"
              class="text-red-500 hover:text-red-700"
            >
              <i class="fas fa-trash-alt mr-2"></i> Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: "AdminUserList",
  computed: {
    users() {
      return this.$store.getters.allUsers;
    },
  },
  created() {
    this.fetchUsers();
  },
  methods: {
    fetchUsers() {
      this.$store.dispatch("fetchUsers");
    },
    addUser() {
      // Logic for adding a new user
      alert("Add a new user");
    },
    editUser(userId) {
      // Logic for editing a user
      alert(`Edit user with ID: ${userId}`);
    },
    deleteUser(userId) {
      if (confirm("Are you sure you want to delete this user?")) {
        this.$store.dispatch("deleteUser", userId);
      }
    },
  },
};
</script>

<style scoped>
/* Vous pouvez ajouter des styles supplémentaires ici si nécessaire. */
</style>
