<template>
  <div
    v-if="isLoading"
    class="fixed inset-0 flex items-center justify-center bg-white z-50"
  >
    <Loader />
  </div>
  <div v-else class="container mx-auto p-4 sm:p-6">
    <h1
      class="text-2xl sm:text-3xl font-bold text-center text-gray-800 mb-4 sm:mb-6"
    >
      Registration Requests
    </h1>

    <div
      v-if="requests.length === 0"
      class="text-center text-gray-500 text-sm sm:text-base"
    >
      <p>No registration requests found.</p>
    </div>

    <!-- Modal de confirmation -->
    <div
      v-if="showModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center"
      role="dialog"
      aria-labelledby="modal-title"
      aria-modal="true"
    >
      <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 id="modal-title" class="text-lg font-bold">Confirm Deletion</h2>
        <p class="mt-2">Are you sure you want to delete this request?</p>
        <div class="flex justify-end mt-4">
          <button
            @click="showModal = false"
            class="px-4 py-2 mr-2 bg-gray-300 rounded"
            aria-label="Cancel deletion"
          >
            Cancel
          </button>
          <button
            @click="handleDeleteRequest"
            class="px-4 py-2 bg-red-600 text-white rounded"
            aria-label="Confirm deletion"
          >
            Delete
          </button>
        </div>
      </div>
    </div>

    <!-- Version responsive pour les petits écrans -->
    <div class="lg:hidden">
      <div
        v-for="request in requests"
        :key="request.id"
        class="bg-white p-4 mb-4 rounded-lg shadow-md"
      >
        <div class="text-sm text-gray-700">
          <p><strong>Email:</strong> {{ request.email }}</p>
          <p><strong>Date:</strong> {{ formatDate(request.created_at) }}</p>
        </div>
        <div class="mt-2">
          <button
            @click="openModal(request.id)"
            class="text-red-500 hover:text-red-700 transition duration-150"
            aria-label="Delete request"
          >
            <i class="fas fa-trash-alt"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Version pour les grands écrans -->
    <div class="overflow-hidden hidden lg:block">
      <table
        class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md"
      >
        <thead class="bg-gray-100 text-gray-700 text-sm font-semibold">
          <tr>
            <th class="px-4 py-2">Email</th>
            <th class="px-4 py-2">Date</th>
            <th class="px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="request in requests"
            :key="request.id"
            class="border-b border-gray-200"
          >
            <td class="px-4 py-2 text-sm text-gray-700 text-center">
              {{ request.email }}
            </td>
            <td class="px-4 py-2 text-sm text-gray-700 text-center">
              {{ formatDate(request.created_at) }}
            </td>
            <td class="px-4 py-2 text-sm text-gray-700 text-center">
              <button
                @click="openModal(request.id)"
                class="text-red-500 hover:text-red-700 transition duration-150"
                aria-label="Delete request"
              >
                <i class="fas fa-trash-alt"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import { useToast } from "vue-toastification";
import Loader from "../utils/Loader.vue";

export default {
  name: "RegistrationRequestsList",
  components: {
    Loader,
  },
  setup() {
    const toast = useToast();
    return { toast };
  },
  data() {
    return {
      showModal: false,
      requestIdToDelete: null,
      isLoading: true,
    };
  },
  computed: {
    ...mapGetters("registrationRequests", ["allRequests"]),
    requests() {
      return this.allRequests;
    },
  },
  async created() {
    try {
      await this.fetchRequests();
    } catch (error) {
      console.error("Erreur lors de la récupération des demandes :", error);
      this.toast.error("Failed to fetch registration requests. ❌", {
        className:
          "bg-red-700 text-white font-bold px-4 py-3 rounded shadow-md",
      });
    } finally {
      this.isLoading = false;
    }
  },
  methods: {
    ...mapActions("registrationRequests", ["fetchRequests", "deleteRequest"]),

    openModal(requestId) {
      this.requestIdToDelete = requestId;
      this.showModal = true;
    },

    async handleDeleteRequest() {
      try {
        await this.deleteRequest(this.requestIdToDelete);
        this.toast.error("Request deleted successfully! 🗑️", {
          timeout: 3000,
          position: "top-right",
          closeOnClick: true,
          pauseOnHover: true,
          draggable: true,
          showCloseButtonOnHover: true,
          hideProgressBar: false,
          className:
            "bg-bitchest-alert text-white font-bold px-4 py-3 rounded shadow-md",
        });
      } catch (error) {
        console.error("Erreur lors de la suppression :", error);
        this.toast.error("Failed to delete request. ❌", {
          className:
            "bg-red-700 text-white font-bold px-4 py-3 rounded shadow-md",
        });
      }
      this.showModal = false;
      this.requestIdToDelete = null;
    },

    formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
      });
    },
  },
};
</script>
