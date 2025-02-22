<template>
  <div class="container mx-auto p-6">
    <h1 class="text-3xl font-semibold mb-8 text-center">Edit Crypto</h1>
    <form @submit.prevent="updateCrypto">
      <!-- Name -->
      <div class="mb-4">
        <label class="block text-sm font-semibold text-gray-700">Crypto Name</label>
        <input v-model="crypto.name" type="text" class="w-full px-4 py-2 border rounded-lg" required />
      </div>

      <!-- Current Price -->
      <div class="mb-4">
        <label class="block text-sm font-semibold text-gray-700">Current Price (€)</label>
        <input v-model="crypto.currentPrice" type="number" class="w-full px-4 py-2 border rounded-lg" required />
      </div>

      <!-- Logo -->
      <div class="mb-4">
        <label class="block text-sm font-semibold text-gray-700">Logo</label>
        <input type="file" @change="handleLogo" class="w-full" />
      </div>

      <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded-md">Update Crypto</button>
    </form>
  </div>
</template>

<script>
import { useToast } from "vue-toastification";
import { mapActions, mapState } from "vuex";

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
    };
  },
  computed: {
    ...mapState("crypto", ["currentCrypto"]),
  },
  async created() {
    const cryptoId = this.$route.params.id;
    if (!cryptoId) {
      console.error("No crypto ID found in the URL.");
      return;
    }

    try {
      await this.fetchCrypto(cryptoId);
      this.crypto = { ...this.currentCrypto };
    } catch (error) {
      console.error("Error fetching cryptocurrency data:", error);
    }
  },
  methods: {
    ...mapActions("crypto", ["fetchCrypto", "updateCrypto"]),
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
        this.toast.error("Failed to update crypto.");
      }
    },
  },
};
</script>
