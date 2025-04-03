<template>
  <div class="max-w-4xl mx-auto p-6">
    <WalletInfo />
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-6 mt-12">
      Your Crypto Wallet
    </h1>

    <!-- Affichage du statut de chargement -->
    <div v-if="loading" class="text-center text-gray-500 text-lg">
      Loading wallet...
    </div>

    <!-- Affichage des erreurs -->
    <div v-else-if="error" class="text-center text-red-500 text-lg">
      <p>{{ error }}</p>
    </div>

    <!-- Affichage des cryptos dans le portefeuille -->
    <div
      v-else-if="paginatedCryptos.length"
      class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
    >
      <div
        v-for="crypto in paginatedCryptos"
        :key="crypto.id"
        class="bg-white p-4 rounded-xl shadow-md border border-gray-200"
      >
        <!-- Image de la crypto -->
        <div class="flex justify-center mb-4">
          <img
            :src="crypto.imageUrl"
            alt="crypto image"
            class="w-10 h-10 object-contain"
          />
        </div>
        <h3 class="text-xl font-bold text-bitchest-success mb-2">
          {{ crypto.name }}
        </h3>

        <!-- Quantité -->
        <h4 class="text-[16px] font-bold">
          Quantity:
          <span class="text-gray-600"
            >{{ formatQuantity(crypto.quantity) }} units</span
          >
        </h4>

        <!-- Prix total -->
        <h3 class="text-xl font-bold mt-2">
          Total Price:
          <span class="text-gray-600">{{
            formatCurrency(crypto.totalPrice)
          }}</span>
        </h3>

        <!-- Bouton pour voir les détails d'achat -->
        <div class="mt-2">
          <button
            @click="viewPurchaseDetails(crypto.id)"
            class="text-bitchest-primary text-sm underline sm:text-base mt-2 mr-12 px-2 hover:text-bitchest-secondary"
          >
            Details
          </button>
          <!-- Bouton pour vendre -->
          <button
            @click="openSellModal(crypto)"
            class="bg-bitchest-secondary text-white text-sm sm:text-base mt-2 px-4 py-1 rounded-md hover:bg-blue-500 transition duration-200 shadow-md"
          >
            <i class="fas fa-euro-sign mr-1 text-white"></i> Sell
          </button>
        </div>
      </div>
    </div>

    <!-- Message si aucun crypto en portefeuille -->
    <div v-else class="text-center text-gray-600 text-lg">
      No cryptocurrencies found in your wallet.
    </div>

    <!-- Pagination -->
    <Pagination
      :currentPage="currentPage"
      :totalPages="totalPages"
      @goToPage="goToPage"
    />

    <!-- Modal de confirmation de vente -->
    <div
      v-if="showSellModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-white p-6 rounded-lg max-w-md w-full">
        <h3 class="text-xl font-bold mb-4">Confirm Sale</h3>
        <p class="mb-4">You are about to sell:</p>
        <div class="flex items-center mb-2">
          <img :src="selectedCrypto.imageUrl" class="w-8 h-8 mr-2" />
          <span class="font-bold">{{ selectedCrypto.name }}</span>
        </div>
        <div class="mb-4">
          <label class="block mb-2">Quantity to sell:</label>
          <input
            type="number"
            v-model="sellQuantity"
            :max="selectedCrypto.quantity"
            min="0.00000001"
            step="0.00000001"
            class="w-full p-2 border rounded"
          />
          <p class="text-sm text-gray-500 mt-1">
            Available: {{ formatQuantity(selectedCrypto.quantity) }}
          </p>
        </div>
        <div class="mb-4">
          <p>
            Current Price: {{ formatCurrency(selectedCrypto.currentPrice) }}
          </p>
          <p class="font-bold mt-1">
            Total:
            {{ formatCurrency(sellQuantity * selectedCrypto.currentPrice) }}
          </p>
        </div>
        <div class="flex justify-end space-x-4">
          <button
            @click="showSellModal = false"
            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
          >
            Cancel
          </button>
          <button
            @click="confirmSell"
            class="px-4 py-2 bg-bitchest-secondary text-white rounded hover:bg-blue-600"
          >
            Confirm Sale
          </button>
        </div>
      </div>
    </div>

    <router-link
      to="/dashboard/trading-market"
      class="flex items-center text-black mt-4"
    >
      <i class="fas fa-arrow-left mr-2"></i> Back
    </router-link>
  </div>
</template>

<script>
import WalletInfo from "./WalletInfo.vue";
import walletService from "@/services/walletService";
import cryptoService from "@/services/cryptoService";
import Pagination from "@/components/Pagination.vue";
import { useToast } from "vue-toastification";

export default {
  components: {
    WalletInfo,
    Pagination,
  },
  setup() {
    const toast = useToast();
    return { toast };
  },
  data() {
    return {
      cryptos: [],
      loading: true,
      error: null,
      currentPage: 1,
      limit: 3,
      showSellModal: false,
      selectedCrypto: {
        id: null,
        name: "",
        quantity: 0,
        currentPrice: 0,
        imageUrl: "",
      },
      sellQuantity: 0,
    };
  },
  computed: {
    totalPages() {
      return Math.ceil(this.cryptos.length / this.limit);
    },
    paginatedCryptos() {
      const startIndex = (this.currentPage - 1) * this.limit;
      const endIndex = startIndex + this.limit;
      return this.cryptos.slice(startIndex, endIndex);
    },
  },
  async mounted() {
    await this.loadWalletData();
  },
  methods: {
    async loadWalletData() {
      try {
        const token = localStorage.getItem("token");
        if (!token) {
          throw new Error("Token is missing. Please log in.");
        }

        this.cryptos = await walletService.getWalletCryptos(token);
        this.cryptos = this.cryptos.map((crypto) => ({
          ...crypto,
          imageUrl: `http://127.0.0.1:8000/storage/cryptos/${crypto.image}`,
          currentPrice: crypto.unitPrice, // Utilise le prix unitaire comme prix courant
        }));
      } catch (err) {
        this.error = err.message || "Failed to load wallet.";
      } finally {
        this.loading = false;
      }
    },
    formatCurrency(value) {
      return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "EUR",
      }).format(value);
    },
    formatQuantity(quantity) {
      return parseFloat(quantity).toFixed(8);
    },
    viewPurchaseDetails(cryptoId) {
      if (!cryptoId) {
        console.error("Error: cryptoId is missing");
        return;
      }
      this.$router.push({
        name: "cryptoPurchaseDetails",
        params: { id: cryptoId },
      });
    },
    goToPage(page) {
      this.currentPage = page;
    },
    openSellModal(crypto) {
      this.selectedCrypto = {
        id: crypto.id,
        name: crypto.name,
        quantity: parseFloat(crypto.quantity),
        currentPrice: parseFloat(crypto.unitPrice),
        imageUrl: crypto.imageUrl,
      };
      this.sellQuantity = parseFloat(crypto.quantity);
      this.showSellModal = true;
    },
    async confirmSell() {
      try {
        // Validation
        if (
          this.sellQuantity <= 0 ||
          this.sellQuantity > this.selectedCrypto.quantity
        ) {
          throw new Error("Quantité invalide");
        }

        const token = localStorage.getItem("token");
        const response = await cryptoService.sellCrypto(
          this.selectedCrypto.id,
          this.sellQuantity,
          this.selectedCrypto.currentPrice,
          token
        );
        console.log(response.message);
        // Gestion réussite
        this.showSellModal = false;
        this.toast.success(response.message || "Crypto sale successful!");
        await this.loadWalletData();
      } catch (error) {
        console.error("Détails de l'erreur:", error);
      }
    },
  },
};
</script>
