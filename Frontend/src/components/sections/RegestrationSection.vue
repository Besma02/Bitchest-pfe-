<template>
    <div class="flex flex-col items-center justify-center h-screen bg-gray-100">
      <div class="bg-white shadow-md rounded-lg p-6 w-full max-w-md">
       
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Regestration</h2>
  
       
        <CustomInput
          placeholder="Email"
          type="email"
          v-model="email"
        />
  
        
        <CustomInput
          placeholder="Password"
          type="password"
          v-model="password"
        />
  
       
        <CustomInput
          placeholder="Confirm Password"
          type="password"
          v-model="confirmPassword"
        />
  
        
        <div class="text-right mb-4">
          <a href="#" class="text-bitchest-success hover:underline">Forgot Password?</a>
        </div>
  
        <CustomButton @click="handleSubmit">
          Login
        </CustomButton>
      </div>
    </div>
  </template>
  
  <script>
  import CustomInput from "../CustomInput.vue";
  import CustomButton from "../CustomButton.vue";
  
  export default {
    name: "Register",
    components: {
      CustomInput,
      CustomButton,
    },
    data() {
      return {
        email: "",
        password: "",
        confirmPassword: "",
      };
    },
    methods: {
      handleSubmit() {
        if (this.password !== this.confirmPassword) {
          alert("Passwords do not match!");
          return;
        }
       
        console.log("Email:", this.email);
        console.log("Password:", this.password);
      },
    },
  };
  </script>