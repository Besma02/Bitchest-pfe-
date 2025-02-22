<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
      <h2 class="text-2xl font-bold text-center mb-4 text-gray-700">Forgot Password</h2>

      <form @submit.prevent="handleForgotPassword" class="space-y-4">
        <!-- Email Input -->
        <div>
          <label class="block text-gray-600 text-sm mb-1">Email</label>
          <input
            v-model="email"
            type="email"
            placeholder="Enter your email"
            autocomplete="email"
            required
            class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
          />
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          class="w-full bg-blue-500 text-white font-bold px-6 py-2 mb-5 rounded-md hover:bg-blue-400"
        >
          Submit
        </button>
      </form>

      <p class="text-center text-sm text-gray-600">
        Remember your password? <router-link to="/login" class="text-blue-500">Login</router-link>
      </p>
    </div>
  </div>
</template>

<script>
import { useToast } from "vue-toastification";

import axios from "axios";

export default {
  data() {
    return {
      email: "",
    };
  },
  setup() {
    const toast = useToast();
    return { toast };
  },
  methods: {
    async handleForgotPassword() {
      try {
        const response = await axios.post('http://127.0.0.1:8000/api/forgot-password', { email: this.email });
        this.toast.success(response.data.message);
      } catch (error) {
        this.toast.error("There was an issue. Please try again.");
      }
    },
  },
};
</script>
