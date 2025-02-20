<template>
    <div class="container mx-auto p-6">
      <h1 class="text-3xl font-semibold mb-8 text-center">Edit Crypto</h1>
      <form @submit.prevent="updateCrypto">
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
          class="bg-bitchest-success text-black font-bold px-6 py-2 rounded-md hover:bg-gray-200"
        >
          Update Crypto
        </button>
      </form>
    </div>
  </template>
  
  <script>
  import { useToast } from 'vue-toastification';
  
  export default {
    name: "EditCrypto",
    setup() {
      const toast = useToast();
      return { toast };
    },
    data() {
      return {
        crypto: {
          name: "",
          currentPrice: "",
          logo: null,
        },
        errors: {},
      };
    },
    async created() {
    const cryptoId = this.$route.params.id;
    console.log("Crypto ID:", cryptoId);

    if (!cryptoId) {
        console.error("Aucun ID trouvé dans l'URL.");
        return;
    }

    try {
      this.crypto = await this.$store.dispatch("crypto/fetchCrypto", cryptoId);

    } catch (error) {
        console.error("Erreur lors de la récupération des données :", error);
    }
},

    methods: {
      handleLogo(event) {
        this.crypto.logo = event.target.files[0];
      },
      async updateCrypto() {
        try {
          await this.$store.dispatch("crypto/updateCrypto", {
            id: this.$route.params.id,
            cryptoData: this.crypto,
          });
  
          this.toast.success("Crypto updated successfully!");
          this.$router.push({ name: "manage-cryptos" });
        } catch (error) {
          if (error.response && error.response.status === 422) {
            this.errors = error.response.data.errors;
          } else {
            console.error("Error updating crypto:", error);
          }
        }
      },
    },
  };
  </script>
  