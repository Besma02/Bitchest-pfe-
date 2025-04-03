<template>
  <div>
    <!-- Heading -->
    <h1
      class="text-2xl sm:text-3xl lg:text-4xl font-bold text-center text-bitchest-black mb-6 lg:mb-8"
    >
      Crypto Management
    </h1>

    <!-- Bouton d'ajout de crypto -->
    <div class="flex justify-center mb-4" v-if="!isClient">
      <router-link
        to="/dashboard/admin/crypto/add"
        class="bg-bitchest-success text-black text-base font-semibold px-6 py-3 rounded-md hover:bg-green-300 transition duration-200 shadow-md w-full sm:w-auto text-center"
      >
        Add crypto
      </router-link>
    </div>

    <!-- Conteneur principal -->
    <div class="flex flex-col lg:flex-row p-4 md:p-6 lg:p-8">
      <div v-if="loading" class="text-center w-full">
        <Loader />
      </div>
      <div v-else-if="error" class="text-center w-full text-red-500">
        <p>{{ error }}</p>
      </div>

      <!-- Liste des cryptos -->
      <div
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 w-full"
      >
        <div
          v-for="crypto in paginatedCryptos"
          :key="crypto.id"
          class="bg-bitchest-white w-full h-auto border border-gray-300 rounded-2xl p-6 text-center shadow-md transition-transform duration-300 hover:scale-105 hover:shadow-lg"
        >
          <img
            :src="getImageUrl(crypto.image_url)"
            :alt="crypto.name"
            class="mx-auto mb-4 w-16 md:w-20 lg:w-24 h-16 md:h-20 lg:h-24 object-contain"
          />
          <h3 class="text-bitchest-success font-bold text-lg">
            {{ crypto.name }}
          </h3>
          <h4 class="text-sm md:text-base lg:text-lg">
            {{ crypto.currentPrice }} €
          </h4>
          <p class="text-sm text-gray-500">In stock: {{ crypto.inStock }}</p>

          <div class="mt-2 space-x-4">
            <router-link
              :to="`/dashboard/crypto/${crypto.id}`"
              class="text-yellow-500 hover:text-yellow-600 transition duration-150"
            >
              View <i class="far fa-eye text-bitchest-secondary"></i>
            </router-link>

            <router-link
              v-if="isClient"
              to="#"
              @click.prevent="openBuyModal(crypto)"
              class="text-yellow-500 hover:text-yellow-600 transition duration-150"
            >
              Buy <i class="fas fa-cart-plus text-bitchest-success"></i>
            </router-link>

            <a
              v-else
              href="#"
              @click.prevent="openEditModal(crypto)"
              class="text-yellow-500 hover:text-yellow-600 transition duration-150"
            >
              Edit <i class="fas fa-edit text-bitchest-success"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <Pagination
      :currentPage="currentPage"
      :totalPages="totalPages"
      @goToPage="goToPage"
    />

    <!-- Modale pour l'achat de crypto -->
    <div
      v-if="showBuyModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div
        class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md mx-4 sm:w-96 relative"
      >
        <button
          @click="showBuyModal = false"
          class="absolute top-3 right-3 text-gray-500 hover:text-gray-700"
        >
          ✖
        </button>

        <h2 class="text-lg font-semibold mb-4 text-center">
          Buy {{ selectedCrypto.name }}
        </h2>

        <label class="block text-gray-700 mb-2">Quantity:</label>
        <input
          v-model="quantity"
          type="number"
          min="0.01"
          step="0.01"
          class="w-full border rounded p-2 mb-4"
        />

        <div class="flex justify-end space-x-2">
          <button
            @click="showBuyModal = false"
            class="bg-gray-400 px-4 py-2 rounded text-white"
          >
            Cancel
          </button>
          <button
            @click="buyCrypto()"
            class="bg-bitchest-success px-4 py-2 rounded text-white"
          >
            Confirm
          </button>
        </div>
      </div>
    </div>

    <!-- Modale pour l'édition de crypto -->
    <div
      v-if="showEditModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div
        class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md mx-4 sm:w-96 relative"
      >
        <button
          @click="showEditModal = false"
          class="absolute top-3 right-3 text-gray-500 hover:text-gray-700"
        >
          ✖
        </button>

        <h2 class="text-lg font-semibold mb-4 text-center">
          Edit {{ editForm.name }}
        </h2>

        <div class="space-y-4">
          <div>
            <label class="block text-gray-700 mb-2">Name:</label>
            <input
              v-model="editForm.name"
              type="text"
              class="w-full border rounded p-2 mb-2"
            />
          </div>

          <div>
            <label class="block text-gray-700 mb-2">Current Price (€):</label>
            <input
              v-model="editForm.currentPrice"
              type="number"
              min="0.01"
              step="0.01"
              class="w-full border rounded p-2 mb-2"
            />
          </div>

          <div>
            <label class="block text-gray-700 mb-2">Stock Quantity:</label>
            <input
              v-model="editForm.inStock"
              type="number"
              min="0"
              step="1"
              class="w-full border rounded p-2 mb-2"
            />
          </div>

          <div>
            <label class="block text-gray-700 mb-2">Image:</label>
            <input
              type="file"
              @change="handleEditImage"
              accept="image/*"
              class="w-full border rounded p-2 mb-2"
            />
            <img
              v-if="editForm.imagePreview"
              :src="editForm.imagePreview"
              class="h-20 mx-auto mt-2"
            />
            <p class="text-xs text-gray-500 mt-1">
              Current: {{ selectedCrypto.image_url.split("/").pop() }}
            </p>
          </div>

          <div class="flex justify-end space-x-2">
            <button
              @click="showEditModal = false"
              class="bg-gray-400 px-4 py-2 rounded text-white"
            >
              Cancel
            </button>
            <button
              @click="updateCrypto"
              :disabled="isUpdating"
              class="bg-bitchest-success px-4 py-2 rounded text-white disabled:opacity-50"
            >
              <span v-if="isUpdating">Saving...</span>
              <span v-else>Save Changes</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Loader from "@/components/utils/Loader.vue";
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

      // Buy modal
      showBuyModal: false,
      selectedCrypto: null,
      quantity: 0,

      // Edit modal
      showEditModal: false,
      isUpdating: false,
      editForm: {
        name: "",
        currentPrice: 0,
        inStock: 0,
        image: null,
        imagePreview: null,
      },

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
        this.toast.error(this.error);
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
      return imagePath.startsWith("http")
        ? imagePath
        : `http://127.0.0.1:8000${imagePath}`;
    },
    openBuyModal(crypto) {
      this.selectedCrypto = crypto;
      this.quantity = 0;
      this.showBuyModal = true;
    },
    async buyCrypto() {
      if (!this.quantity || this.quantity <= 0) {
        this.toast.error("Please enter a valid quantity.");
        return;
      }

      try {
        const token = localStorage.getItem("token");
        await cryptoService.buyCrypto(
          this.selectedCrypto.id,
          this.quantity,
          this.selectedCrypto.currentPrice,
          token
        );

        this.toast.success("Purchase successful!");
        this.showBuyModal = false;
        this.$router.push("/dashboard/wallet");
      } catch (error) {
        this.toast.error(error.message || "Purchase failed!");
      }
    },
    openEditModal(crypto) {
      this.selectedCrypto = { ...crypto };
      this.editForm = {
        name: crypto.name,
        currentPrice: Number(crypto.currentPrice),
        inStock: Number(crypto.inStock),
        image: null,
        imagePreview: this.getImageUrl(crypto.image_url),
      };
      this.showEditModal = true;
    },
    handleEditImage(event) {
      const file = event.target.files[0];
      if (file) {
        this.editForm.image = file;
        this.editForm.imagePreview = URL.createObjectURL(file);
      }
    },

    async updateCrypto() {
      this.isUpdating = true;

      try {
        const formData = new FormData();

        // Ajout explicite des valeurs
        formData.append("name", this.editForm.name);
        formData.append("currentPrice", String(this.editForm.currentPrice));
        formData.append("inStock", String(this.editForm.inStock));

        if (this.editForm.image) {
          formData.append(
            "image",
            this.editForm.image,
            this.editForm.image.name
          );
        }

        const token = localStorage.getItem("token");
        const response = await cryptoService.editCrypto(
          this.selectedCrypto.id,
          formData,
          token
        );

        console.log("Réponse du serveur:", response);

        this.toast.success("Cryptocurrency updated successfully!");
        this.showEditModal = false;
        await this.fetchCryptos();
      } catch (error) {
        console.error("Erreur complète:", {
          message: error.message,
          response: error.response?.data,
        });
        this.toast.error(
          error.response?.data?.message || error.message || "Update failed"
        );
      } finally {
        this.isUpdating = false;
      }
    },
  },
  components: {
    Pagination,
    Loader,
  },
  mounted() {
    this.fetchCryptos();
  },
};
</script>

<style scoped>
.hover-scale {
  transition: transform 0.3s ease;
}
.hover-scale:hover {
  transform: scale(1.05);
}
</style>
