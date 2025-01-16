<template>
  <nav class="bg-bitchest-primary text-bitchest-white p-4">
    <div class="container mx-auto flex justify-between items-center">
      <img src="@/assets/bitchest_logo.png" alt="Logo" class="h-12 mr-4" />
      <div class="flex items-center font-bitchest font-normal text-xl">
        <ul class="flex space-x-10">
          <li><a href="#" class="hover:text-bitchest-secondary">Home</a></li>
          <li>
            <a href="#" class="hover:text-bitchest-secondary">About Us</a>
          </li>
          <li><a href="#" class="hover:text-bitchest-secondary">Trade</a></li>
          <li>
            <a href="#" class="hover:text-bitchest-secondary">Services</a>
          </li>
        </ul>
      </div>
      <CustomButton @click="handleSigninClick">Signin</CustomButton>
    </div>
  </nav>
</template>

<script>
import CustomButton from "@/components/CustomButton.vue";
export default {
  name: "Navbar",
  components: {
    CustomButton,
  },
  methods: {
    handleSigninClick() {
      console.log("Signin button clicked");
    },
  },
};
</script>
