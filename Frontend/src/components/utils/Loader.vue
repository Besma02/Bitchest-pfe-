<template>
  <div class="fixed inset-0 flex items-center justify-center bg-white z-50">
    <div class="loader"></div>
  </div>
</template>

<style scoped>
/* Loader Animation */
.loader {
  width: 60px;
  height: 60px;
  border: 6px solid #f3f3f3;
  border-top: 6px solid #38618c;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}
@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>
