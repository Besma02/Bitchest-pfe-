import "./tailwind.css";

import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

const app = createApp(App);
app.use(store);
app.use(router);
app.use(Toast, { position: "bottom-right", timeout: 3000 });
app.mount("#app");
