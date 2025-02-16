<template>
  <div>
    <h1 class="text-2xl sm:text-3xl font-bold text-center text-gray-800 mb-4 sm:mb-6">
      Crypto Management
    </h1>
    <div class="flex justify-center sm:justify-center mb-4" v-if="!isClient">
      <router-link
        to="/admin/crypto/add"
        class="bg-bitchest-success text-black text-sm sm:text-base font-semibold px-4 sm:px-6 py-2 sm:py-3 rounded-md hover:bg-green-300 transition duration-200 shadow-md w-full sm:w-auto text-center "
      >
        Add crypto
      </router-link>
    </div>

    <div class="flex flex-col sm:flex-col md:flex-col lg:flex-row p-4 sm:p-5 md:p-6 lg:p-5">
      <!-- Section des cartes -->
      <div v-if="loading" class="text-center w-full">
        <p>Loading cryptos...</p>
      </div>

      <div v-else-if="error" class="text-center w-full text-red-500">
        <p>{{ error }}</p>
      </div>

      <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 md:gap-8 lg:gap-[3.75rem] lg:p-[1.25rem]">
        <!-- Carte individuelle dynamique -->
        <div
          v-for="crypto in paginatedCryptos"
          :key="crypto.name"
          class="bg-bitchest-white w-full sm:w-[160px] md:w-[200px] lg:w-[240px] h-[190px] sm:h-[200px] md:h-[210px] border border-gray-300 rounded-[2.0625rem] p-4 sm:p-[1.5rem] lg:p-[2.5rem] text-center shadow-md transition-transform duration-300 ease-in-out hover:scale-105 hover:shadow-lg"
        >
          <img :src="getImageUrl(crypto.image_url)" :alt="crypto.name" class="mx-auto mb-4 w-12 h-12 object-contain" />
          <h3 class="text-bitchest-success font-bold text-sm sm:text-base md:text-lg lg:text-xl">
            {{ crypto.name }}
          </h3>
          <h4 class="text-xs sm:text-sm md:text-base lg:text-lg">{{ crypto.currentPrice }} €</h4>
          <router-link
           :to="`/dashboard/crypto/${crypto.id}`"
          
            class="text-yellow-500 hover:text-yellow-600 transition duration-150 lg:mr-2 sm:mr-0 md:mr-0"
            >view
            <i class="far fa-eye text-bitchest-secondary mr-2 mt-2"></i>
          </router-link>
          <router-link
            @click="isClient ? buyCrypto(crypto.id) : editCrypto(crypto.id)"
            class="text-yellow-500 hover:text-yellow-600 transition duration-150 mr-2"
          >
            {{ isClient ? 'Buy' : 'Edit' }}
            <i :class="isClient ? 'fas fa-cart-plus text-bitchest-success' : 'fas fa-edit text-bitchest-success'"></i>
          </router-link>
        </div>
      </div>
    </div>

    <!-- Utiliser le composant Pagination -->
    <Pagination 
      :currentPage="currentPage" 
      :totalPages="totalPages" 
      @goToPage="goToPage" />
  </div>
</template>
<script>
import cryptoService from "../../../services/cryptoService";
import Pagination from "../../Pagination.vue";

export default {
  props: {
    isClient: Boolean,
    required: true
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
    };
  },
  methods: {
    async fetchCryptos() {
      try {
        this.cryptos = await cryptoService.fetchCryptos();
        this.totalPages = Math.ceil(this.cryptos.length / this.limit);
        this.updatePaginatedCryptos();
      } catch (err) {
        this.error = "Failed to load cryptos. Please try again later.";
      } finally {
        this.loading = false;
      }
    },
    updatePaginatedCryptos() {
      const startIndex = (this.currentPage - 1) * this.limit;
      const endIndex = startIndex + this.limit;
      this.paginatedCryptos = this.cryptos.slice(startIndex, endIndex);
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
        this.updatePaginatedCryptos();
      }
    },
    previousPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
        this.updatePaginatedCryptos();
      }
    },
    goToPage(page) {
      this.currentPage = page;
      this.updatePaginatedCryptos();
    },
    getImageUrl(imagePath) {
      return `http://127.0.0.1:8000${imagePath}`;
    },
    buyCrypto(cryptoId) {
      console.log(`Buying crypto with ID: ${cryptoId}`);
    },
    editCrypto(cryptoId) {
      console.log(`Editing crypto with ID: ${cryptoId}`);
    }
  },
  components: {
    Pagination
  },
  mounted() {
    console.log('isClient:', this.isClient);
    this.fetchCryptos();
  }
};
</script>