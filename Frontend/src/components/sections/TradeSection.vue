<template>
  <section
    id="trade"
    class="text-black flex items-center justify-center bg-lightGray p-6"
  >
    <div class="flex flex-col lg:flex-row p-4 sm:p-5 md:p-6 lg:p-5">
      <!-- Section de texte -->
      <div class="trader w-full lg:w-[413px] lg:h-[326px] lg:ml-[7rem] lg:mr-[7.625rem] mb-8 lg:mb-0">
        <h1 class="text-2xl sm:text-3xl md:text-[2.5rem] lg:text-[3rem] font-bold mt-4 mb-6 lg:mt-7 lg:mb-7">
          CryptoCurrency
        </h1>
        <p class="text-sm sm:text-base md:text-lg lg:text-[1.5rem] leading-6 sm:leading-7 md:leading-[2rem] lg:leading-[2.625rem] font-normal text-[#262626] mb-6 lg:mb-[2.5rem]">
          Explore top cryptos like Bitcoin, Ethereum and Grow your portfolio automatically with daily, weekly, or monthly trades.
        </p>
        <custom-button 
          class="text-bitchest-white font-bold text-base sm:text-lg md:text-xl lg:text-3xl border-0 rounded-lg lg:rounded-[20px] bg-bitchest-success px-4 py-2 sm:w-[150px] md:w-[165px] lg:w-[185px] mx-auto lg:mx-0 shadow-md flex justify-center sm:justify-center md:justify-center
          hover:bg-gray-200 hover:text-bitchest-black transition-colors duration-300"
          @click="goToLogin"
        >
          <span style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2)">See More</span>
        </custom-button>
      </div>

      <!-- Section des cartes -->
      <div v-if="loading" class="text-center w-full">
        <p>Loading cryptos...</p>
      </div>

      <div v-else-if="error" class="text-center w-full text-red-500">
        <p>{{ error }}</p>
      </div>

      <div v-else class="grid grid-cols-2 sm:grid-cols-3 gap-4 sm:gap-6 md:gap-8 lg:gap-[3.75rem] lg:p-[1.25rem]">
        <!-- Carte individuelle dynamique -->
        <div
          v-for="crypto in cryptos.slice(0, 6)"
          :key="crypto.crypto_name"
          class="bg-bitchest-white w-full sm:w-[170px] md:w-[180px] lg:w-[220px] h-[160px] sm:h-[180px] md:h-[190px] border border-gray-300 rounded-[2.0625rem] p-4 sm:p-[2rem] lg:p-[2.5rem] text-center shadow-md transition-transform duration-300 ease-in-out hover:scale-105 hover:shadow-lg"
        >
          <img :src="getImageUrl(crypto.image_url)" :alt="crypto.crypto_name" class="mx-auto mb-4 w-12 h-12 object-contain" />
          <h3 class="text-bitchest-success font-bold text-sm sm:text-base md:text-lg lg:text-xl">
            {{ crypto.name }}
          </h3>
          <h4 class="text-xs sm:text-sm md:text-base lg:text-lg">{{ crypto.currentPrice }} €</h4>
          <!-- Affichage de la date -->
     <!-- <p class="text-xs sm:text-sm md:text-base lg:text-lg text-gray-500">{{ formatDate(crypto.date) }}</p>-->
        </div>
      </div>
      
    </div>
    
  </section>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      cryptos: [],
      loading: true,
      error: null
    };
  },
  methods: {
    goToLogin() {
      this.$router.push({ name: 'login' });
    },
    async fetchCryptos() {
      try {
        const response = await axios.get("http://localhost:8000/api/cryptos/current");
        this.cryptos = response.data;
      } catch (err) {
        this.error = "Failed to load cryptos. Please try again later.";
      } finally {
        this.loading = false;
      }
    },
    // formatDate(dateString) {
    //   const date = new Date(dateString);
    //   return date.toLocaleDateString();
    // },
    getImageUrl(imagePath) {
      // Construire l'URL complet de l'image
      return `http://127.0.0.1:8000${imagePath}`;
    }
  },
  mounted() {
    this.fetchCryptos();
  }
};
</script>
