<template>

  <div>
    <NavBar />
  

  <div class="flex items-center justify-center min-h-screen bg-gray-100">
   
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
      <h2 class="text-2xl font-bold text-center mb-4 text-gray-700">Login</h2>
      
      <form @submit.prevent="handleLogin" class="space-y-4">
        <!-- Email Input -->
        <div>
          <label class="block text-gray-600 text-sm mb-1">Email</label>
          <input 
            v-model="email" 
            type="email" 
            placeholder="Enter your email" 
            autocomplete="email" 
            required 
            class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
          />
        </div>

        <!-- Password Input -->
        <div>
          <label class="block text-gray-600 text-sm mb-1">Password</label>
          <input 
            v-model="password" 
            type="password" 
            placeholder="Enter your password" 
            autocomplete="current-password" 
            required 
            class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
          />
        </div>

        <!-- Login Button -->
        <button 
          type="submit" 
          class="w-full  bg-bitchest-success text-[1rem] text-black font-bold px-6 py-2 mb-5 rounded-md hover:bg-gray-200 "
        >
          Login
        </button>
      </form>
    </div>
  </div>
</div>
</template>

<script>
import { mapActions } from "vuex";
import { useToast } from "vue-toastification"; // ✅ Import Vue Toastification
import NavBar from "../components/NavBar.vue";

export default {
  components:{
   NavBar
  },
  data() {
    return {
      email: "",
      password: "",
    };
  },
  setup() {
    const toast = useToast(); // ✅ Initialize toast
    return { toast };
  },
  methods: {
    ...mapActions("auth", ["login"]),
    async handleLogin() {
      try {
        const response = await this.login({ email: this.email, password: this.password });

        // ✅ Check if login was successful
        if (response && response.token) {
          this.toast.success("✅ Login successful! ");
          setTimeout(() => this.$router.push("/dashboard"), 100);
        } else {
          throw new Error("Invalid login credentials"); // ❌ Invalid response
        }
      } catch (error) {
        this.toast.error("❌ Login failed. Please check your credentials.");
      }
    },
  },
};
</script>