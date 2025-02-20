<template>
  <div class="container mx-auto p-6">
    <h1 class="text-3xl font-semibold mb-8 text-center">Add New Crypto</h1>
    <form @submit.prevent="addCrypto">
      <!-- Name -->
      <div class="mb-4">
        <label for="name" class="block text-sm font-semibold text-gray-700">Crypto Name</label>
        <input
          v-model="crypto.name"
          id="name"
          type="text"
          class="w-full px-4 py-2 border rounded-lg"
          :class="{ 'border-red-500': errors.name }"
          required
        />
        <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name[0] }}</p>
      </div>

      <!-- Current Price -->
      <div class="mb-4">
        <label for="currentPrice" class="block text-sm font-semibold text-gray-700">Current Price (€)</label>
        <input
          v-model="crypto.currentPrice"
          id="currentPrice"
          type="number"
          class="w-full px-4 py-2 border rounded-lg"
          :class="{ 'border-red-500': errors.currentPrice }"
          required
        />
        <p v-if="errors.currentPrice" class="text-red-500 text-sm mt-1">{{ errors.currentPrice[0] }}</p>
      </div>

      <!-- Logo -->
      <div class="mb-4">
        <label for="logo" class="block text-sm font-semibold text-gray-700">Logo</label>
        <input id="logo" type="file" @change="handleLogo" class="w-full" />
        <p v-if="errors.logo" class="text-red-500 text-sm mt-1">{{ errors.logo[0] }}</p>
      </div>

      <button
        type="submit"
        class="bg-green-500 text-white font-bold px-6 py-2 rounded-md hover:bg-green-600"
      >
        Add Crypto
      </button>
    </form>
  </div>
</template>

<script>
export default {
  name: "AddCrypto",
  data() {
    return {
      crypto: {
        name: "",
        currentPrice: 0,
        logo: null,
      },
      errors: {},
    };
  },
  methods: {
    handleLogo(event) {
      this.crypto.logo = event.target.files[0];
    },
    async addCrypto() {
      try {
        let formData = new FormData();
        formData.append("name", this.crypto.name);
        formData.append("currentPrice", this.crypto.currentPrice);
        formData.append("logo", this.crypto.logo);

        // Log the form data to the console
        console.log("Form Data Submitted:", this.crypto);
        
        // You can also inspect the formData by converting it to an object:
        formData.forEach((value, key) => {
          console.log(key, value);
        });
        console.log(formData);

        // Send the form data to the server
        await this.$store.dispatch("crypto/addCrypto", this.crypto);

        this.$router.push({ name: "manage-cryptos" });
      } catch (error) {
        if (error.response && error.response.status === 422) {
          this.errors = error.response.data.errors;
        }
      }
    },
  },
};
</script>


