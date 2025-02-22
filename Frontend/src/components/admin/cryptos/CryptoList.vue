<template>
  <div>
    <h1 class="text-2xl sm:text-3xl font-bold text-center text-gray-800 mb-4 sm:mb-6">
      Crypto Management
    </h1>

    <div class="flex justify-center sm:justify-center mb-4" v-if="!isClient">
      <router-link
        to="/admin/crypto/add"
        class="bg-bitchest-success text-black text-sm sm:text-base font-semibold px-4 sm:px-6 py-2 sm:py-3 rounded-md hover:bg-green-300 transition duration-200 shadow-md w-full sm:w-auto text-center"
      >
        Add crypto
      </router-link>
    </div>

    <div class="flex flex-col sm:flex-col md:flex-col lg:flex-row p-4 sm:p-5 md:p-6 lg:p-5">
      <div v-if="loading" class="text-center w-full">
        <p>Loading cryptos...</p>
      </div>
      <div v-else-if="error" class="text-center w-full text-red-500">
        <p>{{ error }}</p>
      </div>

      <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 md:gap-8 lg:gap-[3.75rem] lg:p-[1.25rem]">
        <div
          v-for="crypto in paginatedCryptos"
          :key="crypto.id"
          class="bg-bitchest-white w-full sm:w-[160px] md:w-[200px] lg:w-[240px] h-[190px] sm:h-[200px] md:h-[210px] border border-gray-300 rounded-[2.0625rem] p-4 sm:p-[1.5rem] lg:p-[2.5rem] text-center shadow-md transition-transform duration-300 ease-in-out hover:scale-105 hover:shadow-lg"
        >
          <img :src="getImageUrl(crypto.image_url)" :alt="crypto.name" class="mx-auto mb-4 w-12 h-12 object-contain" />
          <h3 class="text-bitchest-success font-bold text-sm sm:text-base md:text-lg lg:text-xl">
            {{ crypto.name }}
          </h3>
          <h4 class="text-xs sm:text-sm md:text-base lg:text-lg">{{ crypto.currentPrice }} €</h4>

          <router-link
            :to="`/dashboard/crypto/${crypto.id}`"
            class="text-yellow-500 hover:text-yellow-600 transition duration-150 lg:mr-2"
          >
            View <i class="far fa-eye text-bitchest-secondary mr-2 mt-2"></i>
          </router-link>

          <router-link
            v-if="isClient"
            to="#"
            @click.prevent="openBuyModal(crypto)"
            class="text-yellow-500 hover:text-yellow-600 transition duration-150"
          >
            Buy <i class="fas fa-cart-plus text-bitchest-success"></i>
          </router-link>

          <router-link
            v-else
            :to="`/admin/crypto/${crypto.id}/edit`"
            class="text-yellow-500 hover:text-yellow-600 transition duration-150"
          >
            Edit <i class="fas fa-edit text-bitchest-success"></i>
          </router-link>
        </div>
      </div>
    </div>

    <Pagination :currentPage="currentPage" :totalPages="totalPages" @goToPage="goToPage" />

    <!-- Modale pour l'achat de crypto -->
    <div v-if="showBuyModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
      <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-semibold mb-4">Buy {{ selectedCrypto.name }}</h2>
        <label class="block text-gray-700 mb-2">Quantity:</label>
        <input
          v-model="quantity"
          type="number"
          min="0.01"
          step="0.01"
          class="w-full border rounded p-2 mb-4"
        />
        <div class="flex justify-end space-x-2">
          <button @click="showBuyModal = false" class="bg-gray-400 px-4 py-2 rounded text-white">Cancel</button>
          <button @click="buyCrypto()" class="bg-bitchest-success px-4 py-2 rounded text-white">Confirm</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import cryptoService from "../../../services/cryptoService";
import Pagination from "../../Pagination.vue";
import { useToast } from "vue-toastification";

export default {
  props: {
    isClient: Boolean,
  },
  data() {
    return {
      cryptos: [],
      paginatedCryptos: [],
      loading: true,
      error: null,
      limit: 6,
      currentPage: 1,
      totalPages: 0,
      showBuyModal: false,
      selectedCrypto: null,
      quantity: 0,
      toast: useToast(),
    };
  },
  methods: {
    async fetchCryptos() {
      try {
        this.cryptos = await cryptoService.fetchCryptos();
        this.totalPages = Math.ceil(this.cryptos.length / this.limit);
        this.updatePaginatedCryptos();
      } catch (err) {
        this.error = "Failed to load cryptos.";
      } finally {
        this.loading = false;
      }
    },
    updatePaginatedCryptos() {
      const startIndex = (this.currentPage - 1) * this.limit;
      const endIndex = startIndex + this.limit;
      this.paginatedCryptos = this.cryptos.slice(startIndex, endIndex);
    },
    goToPage(page) {
      this.currentPage = page;
      this.updatePaginatedCryptos();
    },
    getImageUrl(imagePath) {
      return `http://127.0.0.1:8000${imagePath}`;
    },
    openBuyModal(crypto) {
      this.selectedCrypto = crypto;
      this.showBuyModal = true;
    },
    async buyCrypto() {
      if (!this.quantity || this.quantity <= 0) {
        this.toast.error("Invalid quantity.");
        return;
      }

      try {
        const token = localStorage.getItem("token");
        if (!token) {
          this.toast.error("Token is missing. Please log in.");
          return;
        }

        await cryptoService.buyCrypto(this.selectedCrypto.id, this.quantity, this.selectedCrypto.currentPrice, token);

        this.toast.success("Purchase successful!");
        this.showBuyModal = false;
        this.$router.push("/dashboard/wallet");
      } catch (error) {
        this.toast.error(error.message || "Purchase failed!");
      }
    },
  },
  components: {
    Pagination,
  },
  mounted() {
    this.fetchCryptos();
  },
};
</script>