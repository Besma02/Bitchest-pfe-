import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store"; // ✅ Import Vuex store
import axios from "axios";
import "./tailwind.css";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css"; // Import styles

// ✅ Configure Axios globally
axios.defaults.baseURL = "http://127.0.0.1:8000"; // Replace with your Laravel backend URL
axios.defaults.withCredentials = true; // Enable cross-origin cookies for Sanctum

const app = createApp(App);

// ✅ Inject Axios globally into Vue
app.config.globalProperties.$axios = axios;

// ✅ Use Vuex store
app.use(store); 

// ✅ Use Vue Toastification
app.use(Toast);
// ✅ Use the router
app.use(router);

// ✅ Mount the app
app.mount("#app");
