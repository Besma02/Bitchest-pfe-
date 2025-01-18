<template>
  <section
    id="home"
    class="container min-h-screen mx-auto flex flex-col-reverse md:flex-row justify-center p-4"
  >
    <!-- Text Section -->
    <div class="w-full md:w-1/2 text-center md:text-left">
      <h1 class="text-4xl lg:text-7xl font-bitchest font-bold mt-28 mb-12">
        The largest Crypto Marketplace
      </h1>
      <p class="text-xl w-3/4 mx-auto md:mx-0 mb-16">
        Buy, sell, and store hundreds of cryptocurrencies and protect your
        wallet in an easy way.
      </p>
      <p class="text-xl w-3/4 mx-auto md:mx-0 mb-3">
        Join us and sign up to explore more
      </p>
      <CustomInput placeholder="enter your email" class="mr-8 CustomInput" />
      <CustomButton variant="primary">Registration request</CustomButton>
    </div>

    <!-- Image Section -->
    <div class="w-full md:w-1/2 md:flex md:items-center mb-4 md:mb-0">
      <img
        src="@/assets/homeImage.png"
        alt="Description de l'image"
        class="w-full h-auto"
      />
    </div>
  </section>
</template>

<script>
import CustomButton from "@/components/CustomButton.vue";
import CustomInput from "@/components/CustomInput.vue";
export default {
  name: "HomeSection",
  components: {
    CustomButton,
    CustomInput,
  },
};
</script>
<style>
.CustomInput {
  width: 50%;
}
@media screen and (max-width: 768px) {
  .CustomInput {
    width: 100%;
  }
}
</style>
