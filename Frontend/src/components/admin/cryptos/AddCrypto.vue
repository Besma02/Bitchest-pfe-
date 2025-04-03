<template>
  <div class="max-w-lg mx-auto mt-9 p-6 bg-white shadow-md rounded-lg">
    <h1 class="text-3xl font-semibold mb-8 text-center text-gray-800">
      Add Cryptocurrency
    </h1>

    <form @submit.prevent="submitForm" class="space-y-6">
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700"
          >Name*</label
        >
        <input
          v-model="name"
          id="name"
          type="text"
          required
          class="mt-1 w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
          placeholder="Bitcoin"
        />
        <p v-if="errors.name" class="text-red-500 text-sm mt-1">
          {{ errors.name }}
        </p>
      </div>

      <div>
        <label
          for="currentPrice"
          class="block text-sm font-medium text-gray-700"
          >Current Price*</label
        >
        <input
          v-model="currentPrice"
          id="currentPrice"
          type="number"
          step="0.00000001"
          min="0.00000001"
          required
          class="mt-1 w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
          placeholder="0.00"
        />
        <p v-if="errors.currentPrice" class="text-red-500 text-sm mt-1">
          {{ errors.currentPrice }}
        </p>
      </div>

      <div>
        <label for="inStock" class="block text-sm font-medium text-gray-700"
          >Stock Quantity*</label
        >
        <input
          v-model="inStock"
          id="inStock"
          type="number"
          step="0.00000001"
          min="0"
          required
          class="mt-1 w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
          placeholder="0.00"
        />
        <p v-if="errors.inStock" class="text-red-500 text-sm mt-1">
          {{ errors.inStock }}
        </p>
      </div>

      <div>
        <label for="image" class="block text-sm font-medium text-gray-700"
          >Logo</label
        >
        <input
          id="image"
          type="file"
          @change="handleImageUpload"
          accept="image/*"
          class="mt-1 w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
        />
        <p v-if="errors.image" class="text-red-500 text-sm mt-1">
          {{ errors.image }}
        </p>
      </div>

      <div class="text-center">
        <button
          type="submit"
          :disabled="isSubmitting"
          class="w-full bg-bitchest-success text-black text-[1rem] font-bold px-4 py-2 rounded-lg shadow hover:bg-gray-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none disabled:opacity-50"
        >
          <span v-if="isSubmitting">Adding...</span>
          <span v-else>Add Cryptocurrency</span>
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import { useToast } from "vue-toastification";
import cryptoService from "@/services/cryptoService"; // Importez directement le service

export default {
  setup() {
    const toast = useToast();
    return { toast };
  },
  data() {
    return {
      name: "",
      currentPrice: "",
      inStock: "",
      image: null,
      errors: {},
      isSubmitting: false,
    };
  },
  methods: {
    async submitForm() {
      this.isSubmitting = true;
      this.errors = {};

      try {
        const formData = new FormData();
        formData.append("name", this.name.trim());
        formData.append("currentPrice", this.currentPrice);
        formData.append("inStock", this.inStock);
        if (this.image) formData.append("image", this.image);

        const token = localStorage.getItem("token");
        const response = await cryptoService.addCrypto(formData, token); // Utilisation directe

        this.toast.success(
          response.message || "Cryptocurrency added successfully!"
        );
        this.$router.push({ name: "manage-crypto" });
      } catch (error) {
        console.error("Error details:", error); // Debug
        this.toast.error(error.message || "Failed to add cryptocurrency");

        if (error.response?.data?.error?.includes("already exists")) {
          this.errors.name = "This cryptocurrency already exists.";
        }
      } finally {
        this.isSubmitting = false;
      }
    },
    handleImageUpload(event) {
      this.image = event.target.files[0];
    },
  },
};
</script>
