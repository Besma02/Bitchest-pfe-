<template>
    <div class="flex flex-col space-y-4">
      <h2 class="text-3xl font-bold  text-gray-800 mb-2">My Wallet</h2>
      <div v-if="loading" class="text-gray-600">Loading...</div>
      <div v-else-if="error" class="text-red-500">{{ error }}</div>
      <div v-else class="text-gray-700">
        <p><strong>Balance :</strong> <span class="font-medium">{{ balance }} €</span></p>
       <!-- <p><strong> Public Adress :</strong> <span class="break-all text-blue-600">{{ publicAddress }}</span></p>-->
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        balance: 0,
        publicAddress: '',
        error: null,
        loading: false,
      };
    },
    mounted() {
      this.fetchWalletInfo();
    },
    methods: {
      async fetchWalletInfo() {
        this.loading = true;
        try {
          const response = await axios.get('http://localhost:8000/api/wallet', {
            headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
          });
          this.balance = response.data.balance;
          this.publicAddress = response.data.public_address;
        } catch (err) {
          this.error = err.response?.data?.error || "error happned.";
        } finally {
          this.loading = false;
        }
      },
    },
  };
  </script>
  