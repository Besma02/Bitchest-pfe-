<template>
  <div>
    <!-- Add Crypto Button (Visible to Admin) -->
    <div class="flex justify-center sm:justify-center mb-4" v-if="!isClient">
      <router-link
        :to="{ name: 'AddCrypto' }"
        class="bg-bitchest-success text-black text-sm sm:text-base font-semibold px-4 sm:px-6 py-2 sm:py-3 rounded-md hover:bg-green-300 transition duration-200 shadow-md w-full sm:w-auto text-center"
      >
        Add Crypto
      </router-link>
    </div>

    <div class="flex flex-col sm:flex-col md:flex-col lg:flex-row p-4 sm:p-5 md:p-6 lg:p-5">
      <!-- Section displaying cards -->
      <div v-if="loading" class="text-center w-full">
        <p>Loading cryptos...</p>
      </div>

      <div v-else-if="error" class="text-center w-full text-red-500">
        <p>{{ error }}</p>
      </div>

      <!-- Grille des cryptos -->
      <div
        v-else
        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 md:gap-8 lg:gap-10"
      >
        <!-- Carte individuelle -->
        <div
          v-for="crypto in paginatedCryptos"
          :key="crypto.id"
          class="bg-bitchest-white w-full max-w-sm h-auto border border-gray-300 rounded-[2rem] p-4 sm:p-5 md:p-6 text-center shadow-md transition-transform duration-300 ease-in-out hover:scale-105 hover:shadow-lg"
        >
          <img :src="getImageUrl(crypto.image_url)" :alt="crypto.name" class="mx-auto mb-4 w-12 h-12 object-contain" />
          <h3 class="text-bitchest-success font-bold text-sm sm:text-base md:text-lg lg:text-xl">
            {{ crypto.name }}
          </h3>
          <h4 class="text-sm sm:text-base md:text-lg">
            {{ crypto.currentPrice }} €
          </h4>
          <div class="mt-4 flex justify-center space-x-3">
            <router-link
              :to="`/dashboard/crypto/${crypto.id}`"
              class="text-yellow-500 hover:text-yellow-600 transition duration-150"
            >
              View
              <i class="far fa-eye text-bitchest-secondary ml-1"></i>
            </router-link>
            <button
              @click="isClient ? buyCrypto(crypto.id) : editCrypto(crypto.id)"
              class="text-yellow-500 hover:text-yellow-600 transition duration-150"
            >
              {{ isClient ? "Buy" : "Edit" }}
              <i
                :class="
                  isClient
                    ? 'fas fa-cart-plus text-bitchest-success ml-1'
                    : 'fas fa-edit text-bitchest-success ml-1'
                "
              ></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="totalPages > 1" class="flex justify-center mt-6 space-x-2">
        <button
          v-if="currentPage > 1"
          @click="previousPage"
          class="text-blue-500 px-3 py-1 sm:px-4 sm:py-2 border rounded-full hover:bg-blue-50 transition duration-150"
        >
          Previous
        </button>
        <button
          v-for="page in totalPages"
          :key="page"
          :class="{
            'bg-blue-500 text-white': page === currentPage,
            'text-blue-500 hover:bg-blue-50': page !== currentPage,
          }"
          @click="goToPage(page)"
          class="px-3 py-1 sm:px-4 sm:py-2 border rounded-full transition duration-150"
        >
          {{ page }}
        </button>
        <button
          v-if="currentPage < totalPages"
          @click="nextPage"
          class="text-blue-500 px-3 py-1 sm:px-4 sm:py-2 border rounded-full hover:bg-blue-50 transition duration-150"
        >
          Next
        </button>
      </div>
    </div>
  </div>
</template>


<script>
import axios from "axios";

export default {
  props: {
    isClient: Boolean,
    required: true,
  },
  data() {
    return {
      cryptos: [],
      paginatedCryptos: [],
      loading: true,
      error: null,
      limit: 6, // Number of cryptos per page
      currentPage: 1, // Current page of pagination
      totalPages: 0, // Total number of pages
    };
  },
  created() {
    this.fetchCryptos(); // Fetch the list of cryptos when the component is created
  },
  methods: {
    // Fetch the list of cryptos from the API
    async fetchCryptos() {
      try {
        const response = await axios.get("http://127.0.0.1:8000/api/cryptocurrencies");
        this.cryptos = response.data;
        console.log(response.data);
        this.totalPages = Math.ceil(this.cryptos.length / this.limit); // Calculate total pages
        this.updatePaginatedCryptos();
      } catch (err) {
        this.error = "Failed to load cryptos. Please try again later.";
      } finally {
        this.loading = false;
      }
    },

    // Update the list of cryptos displayed on the current page
    updatePaginatedCryptos() {
      const startIndex = (this.currentPage - 1) * this.limit;
      const endIndex = startIndex + this.limit;
      this.paginatedCryptos = this.cryptos.slice(startIndex, endIndex);
    },

    // Go to a specific page
    goToPage(page) {
      this.currentPage = page;
      this.updatePaginatedCryptos(); // Update the displayed cryptos for the new page
    },

    // Navigate to the previous page
    previousPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
        this.updatePaginatedCryptos(); // Update the displayed cryptos
      }
    },

    // Navigate to the next page
    nextPage() {
      if (this.currentPage < this.totalPages) {x
        this.currentPage++;
        this.updatePaginatedCryptos(); // Update the displayed cryptos
      }
    },

    getImageUrl(imagePath) {
      return `http://127.0.0.1:8000${imagePath}`;
    },
    buyCrypto(cryptoId) {
      console.log(`Buying crypto with ID: ${cryptoId}`);
    },
    editCrypto(cryptoId) {
      this.$router.push(`/admin/crypto/edit/${cryptoId}`);
  console.log("Vue Router instance:", this.$router);
  if (this.$router) {
    this.$router.push({ name: 'EditCrypto', params: { id: cryptoId } });
    console.log(`Navigating to edit crypto with ID: ${cryptoId}`);
  } else {
    console.error("Router is not available");
  }
},


    // View details of a crypto
    viewCrypto(cryptoId) {
      console.log(`Viewing crypto with ID: ${cryptoId}`);
    },
  },
  mounted() {
    this.fetchCryptos();
  },
 
};
</script>
