<template>
  <div class="max-w-3xl mx-auto p-6">
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">My Transactions</h1>

    <!-- Affichage du chargement -->
    <div v-if="isLoading" class="text-center text-gray-500">Loading...</div>

    <ul v-else-if="transactions.length" class="divide-y divide-gray-300">
      <li v-for="transaction in transactions" :key="transaction.transactionDate" class="py-4">
        <div class="flex justify-between items-center">
          <h3 class="text-lg font-semibold text-gray-800">{{ transaction.cryptoName }}</h3>
          <span :class="statusClass(transaction.type)" class=" bg-green-200 px-3 py-1  text-green-700 text-sm font-semibold rounded">
            {{ transaction.type }}
          </span>
        </div>
        <p class="text-gray-700">Quantity : <span class="font-semibold">{{ transaction.quantity }}</span></p>
        <p class="text-gray-700">Unit price : <span class="font-semibold">{{ transaction.unitPrice }} €</span></p>
        <p class="text-gray-700">Total price: <span class="font-semibold">{{ transaction.totalPrice }} €</span></p>
        <p class="text-gray-600 text-sm">Date : {{ new Date(transaction.transactionDate).toLocaleString() }}</p>
       <p class="text-gray-600 text-sm">User ID: {{ transaction.userId }}</p>
       <p class="text-gray-600 text-sm">User Name: {{ transaction.userName }}</p>
      </li>
    </ul>

    <p v-else class="text-center text-gray-500 mt-4">No transaction found.</p>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

export default {
  computed: {
    ...mapGetters(['transactions', 'isLoading'])
  },
  methods: {
    ...mapActions(['fetchTransactions']),
    statusClass(status) {
      return {
        completed: 'bg-green-200 text-green-700',
        pending: 'bg-yellow-200 text-yellow-700',
        failed: 'bg-red-200 text-red-700'
      }[status] || 'bg-gray-200 text-gray-700';
    }
  },
  mounted() {
    this.fetchTransactions();
  }
};
</script>
