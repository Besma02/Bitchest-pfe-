<template>
  <div
    class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-70"
  >
    <div class="loader"></div>
  </div>
</template>

<style scoped>
/* Loader Animation */
.loader {
  width: 50px;
  height: 50px;
  border: 5px solid #f3f3f3;
  border-top: 5px solid #38618c;
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
