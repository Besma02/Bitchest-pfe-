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

    <!-- Crypto List -->
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
          
          <!-- View and Edit Links -->
          <router-link
            @click="viewCrypto(crypto.id)"
            class="text-yellow-500 hover:text-yellow-600 transition duration-150 lg:mr-2 sm:mr-0 md:mr-0"
            >View
            <i class="far fa-eye text-bitchest-secondary mr-2 mt-2"></i>
          </router-link>
          
          <!-- Edit (Admin) or Buy (Client) -->
          <router-link
            :to="isClient ? `/buy/${crypto.id}` : `/admin/crypto/edit/${crypto.id}`"
            class="text-yellow-500 hover:text-yellow-600 transition duration-150 mr-2"
          >
            {{ isClient ? 'Buy' : 'Edit' }}
            <i :class="isClient ? 'fas fa-cart-plus text-bitchest-success' : 'fas fa-edit text-bitchest-success'"></i>
          </router-link>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <div v-if="totalPages > 1" class="flex justify-center mt-4">
      <button 
        v-if="currentPage > 1" 
        @click="previousPage" 
        class="text-blue-500 mx-2">Previous</button>

      <button 
        v-for="page in totalPages" 
        :key="page" 
        :class="{'bg-blue-500 text-white': page === currentPage, 'text-blue-500': page !== currentPage}"
        @click="goToPage(page)"
        class="mx-1 px-3 py-1 border rounded-full">
        {{ page }}
      </button>

      <button 
        v-if="currentPage < totalPages" 
        @click="nextPage" 
        class="text-blue-500 mx-2">Next</button>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  props: {
    isClient: Boolean, // Prop to indicate whether the user is a client or admin
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
        this.totalPages = Math.ceil(this.cryptos.length / this.limit); // Calculate the total number of pages
        this.updatePaginatedCryptos(); // Update the list of cryptos for the current page
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

    // Helper method to get the image URL of the crypto
    getImageUrl(imageUrl) {
      return imageUrl ? imageUrl : "default-image.jpg"; // Fallback to default image if no URL is provided
    },

    // Method to handle the view link click (for clients)
    viewCrypto(cryptoId) {
      this.$router.push({ name: "ViewCrypto", params: { id: cryptoId } });
    }
  }
};
</script>

