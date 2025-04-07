<template>
  <section
    id="not-found"
    class="p-6 text-black flex items-center justify-center min-h-[700px] bg-lightGray"
  >
    <div
      class="flex flex-col md:flex-row items-center justify-between md:space-x-6 w-full max-w-6xl"
    >
      <!-- Illustration 404 -->
      <img
        src="../assets/404.png"
        alt="404 Not Found"
        class="w-[60%] sm:w-[50%] md:w-[40%] lg:w-[35%] max-w-[500px] h-auto mb-8 md:mb-0"
      />

      <!-- Texte d'erreur -->
      <div class="text-center md:text-left w-[90%] md:w-[790px] md:ml-12">
        <h1
          class="text-2xl sm:text-3xl md:text-[2.5rem] lg:text-[3rem] font-bold mt-4 mb-6 lg:mt-7 lg:mb-7 text-bitchest-error"
        >
          Oops! Page Not Found
        </h1>
        <p class="text-xl w-3/4 mx-auto md:mx-0 mb-10 leading-[1.8]">
          The page you're looking for doesn't exist or has been moved. <br />
          Check the URL or return to the home page.
        </p>
        <custom-button
          class="text-white font-bold text-lg sm:text-xl md:text-2xl lg:text-3xl border-0 rounded-lg bg-bitchest-success px-6 py-3 shadow-md hover:bg-gray-200 hover:text-bitchest-black transition-colors duration-300"
          @click="goHome"
        >
          Go Home
        </custom-button>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  methods: {
    goHome() {
      this.$router.push({ name: "home" });
    },
  },
};
</script>
