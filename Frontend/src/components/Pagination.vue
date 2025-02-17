<template>
    <div v-if="totalPages > 1" class="flex justify-center mt-4">
      <button v-if="currentPage > 1" @click="previousPage" class="text-blue-500 mx-2">Previous</button>
  
      <button
        v-for="page in totalPages"
        :key="page"
        :class="{
          'bg-blue-500 text-white': page === currentPage,
          'text-blue-500': page !== currentPage,
        }"
        @click="$emit('goToPage', page)"
        class="mx-1 px-3 py-1 border rounded-full"
      >
        {{ page }}
      </button>
  
      <button v-if="currentPage < totalPages" @click="nextPage" class="text-blue-500 mx-2">Next</button>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      currentPage: Number,
      totalPages: Number,
    },
    methods: {
      previousPage() {
        if (this.currentPage > 1) {
          this.$emit("goToPage", this.currentPage - 1);
        }
      },
      nextPage() {
        if (this.currentPage < this.totalPages) {
          this.$emit("goToPage", this.currentPage + 1);
        }
      },
    },
  };
  </script>
  