<template>
  <div class="container mx-auto p-6">
    <h1 class="text-3xl font-semibold mb-8 text-center">Edit User</h1>
    <form @submit.prevent="updateUser">
      <!-- Champ Name -->
      <div class="mb-4">
        <label for="name" class="block text-sm font-semibold text-gray-700">Name</label>
        <input
          v-model="user.name"
          id="name"
          type="text"
          class="w-full px-4 py-2 border rounded-lg"
          :class="{ 'border-red-500': errors.name }"
          required
        />
        <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name[0] }}</p>
      </div>

      <!-- Champ Email -->
      <div class="mb-4">
        <label for="email" class="block text-sm font-semibold text-gray-700">Email</label>
        <input
          v-model="user.email"
          id="email"
          type="email"
          class="w-full px-4 py-2 border rounded-lg"
          :class="{ 'border-red-500': errors.email }"
          required
        />
        <p v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email[0] }}</p>
      </div>

      <!-- Champ Photo -->
      <div class="mb-4">
        <label for="photo" class="block text-sm font-semibold text-gray-700">Photo</label>
        <input id="photo" type="file" @change="handlePhoto" class="w-full" />
        <p v-if="errors.photo" class="text-red-500 text-sm mt-1">{{ errors.photo[0] }}</p>
      </div>

      <!-- Champ Role -->
      <div class="mb-4">
        <label for="role" class="block text-sm font-semibold text-gray-700">Role</label>
        <select
          v-model="user.role"
          id="role"
          class="w-full px-4 py-2 border rounded-lg"
          :class="{ 'border-red-500': errors.role }"
          required
        >
          <option value="admin">Admin</option>
          <option value="client">Client</option>
        </select>
        <p v-if="errors.role" class="text-red-500 text-sm mt-1">{{ errors.role[0] }}</p>
      </div>

      <!-- Bouton -->
      <button
        type="submit"
        class="bg-bitchest-success text-black font-bold px-6 py-2 rounded-md hover:bg-gray-200"
      >
        Update User
      </button>
    </form>
  </div>
</template>

<script>
export default {
  name: "EditUser",
  data() {
    return {
      user: {
        name: "",
        email: "",
        role: "", // Rôle admin ou client
      },
      photo: null,
      errors: {}, // Pour stocker les erreurs de validation
    };
  },
  created() {
    const userId = this.$route.params.id;
    this.fetchUser(userId);
  },
  methods: {
    async fetchUser(userId) {
      try {
        const response = await this.$store.dispatch("fetchUser", userId);
        this.user = response.data; // Pré-remplir les champs avec les données de l'utilisateur
      } catch (error) {
        console.error("Erreur lors de la récupération de l'utilisateur :", error);
      }
    },
    handlePhoto(event) {
      this.photo = event.target.files[0];
    },
    async updateUser() {
      const data = {
        name: this.user.name,
        email: this.user.email,
        role: this.user.role,
        photo: this.photo ? await this.convertPhotoToBase64() : null,
      };

      try {
        await this.$store.dispatch("updateUser", { id: this.$route.params.id, userData: data });
        alert("User updated successfully!");
        this.$router.push({ name: "manage-users" });
      } catch (error) {
        if (error.response && error.response.status === 422) {
          this.errors = error.response.data.errors; // Stocker les erreurs
        } else {
          console.error("Erreur lors de la mise à jour :", error);
        }
      }
    },
    async convertPhotoToBase64() {
      return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onloadend = () => resolve(reader.result);
        reader.onerror = reject;
        reader.readAsDataURL(this.photo);
      });
    },
  },
};
</script>
