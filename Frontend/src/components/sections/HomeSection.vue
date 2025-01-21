<template>
  <section
    id="home"
    class="container min-h-screen mx-auto flex flex-col-reverse md:flex-row justify-center p-4"
  >
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
      <CustomInput
        @inputData="setInputData"
        placeholder="enter your email"
        class="mr-8 CustomInput"
      />
      <CustomButton variant="primary" @click="submitEmail">
        Registration request
      </CustomButton>

      <div
        v-if="showModal"
        class="fixed top-0 left-0 w-full h-full bg-gray-900 bg-opacity-50 flex items-center justify-center"
      >
        <div class="bg-white p-6 rounded shadow-lg text-center">
          <p>{{ modalMessage }}</p>
          <button
            @click="handleModalClose"
            class="bg-blue-500 text-white px-4 py-2 rounded mt-4"
          >
            {{ modalRedirect ? "Go to Sign In" : "Close" }}
          </button>
        </div>
      </div>
    </div>

    <div class="w-full md:w-1/2 md:flex md:items-center mb-4 md:mb-0">
      <img
        src="@/assets/homeImage.png"
        alt="Crypto Marketplace"
        class="w-full h-auto"
      />
    </div>
  </section>
</template>

<script>
import CustomButton from "@/components/CustomButton.vue";
import CustomInput from "@/components/CustomInput.vue";
import axios from "axios";

export default {
  name: "HomeSection",
  components: {
    CustomButton,
    CustomInput,
  },
  data() {
    return {
      email: "", // La variable email est liée via v-model à CustomInput
      showModal: false,
      modalMessage: "",
      modalRedirect: false,
    };
  },
  methods: {
    setInputData(value) {
      this.email = value; // Met à jour la variable email
    },
    async submitEmail() {
      console.log(this.email); // Affiche l'email dans la console
      try {
        const response = await axios.post(
          "http://localhost:8000/registration-request",
          {
            email: this.email,
          }
        );
        this.modalMessage =
          "Your request has been received. You will be notified soon.";
        this.modalRedirect = false;
      } catch (error) {
        if (error.response.status === 409) {
          this.modalMessage =
            "You are already registered. Redirecting to Sign In.";
          this.modalRedirect = true;
        } else {
          this.modalMessage = "An error occurred. Please try again later.";
        }
      }
      this.showModal = true;
    },
    handleModalClose() {
      this.showModal = false;
      if (this.modalRedirect) {
        window.location.href = "/login";
      }
    },
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
