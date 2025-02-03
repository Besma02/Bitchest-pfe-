import { createStore } from "vuex";
import auth from "./auth"; // ✅ Import auth module


const store = createStore({
  modules: {
    auth, // ✅ Register the auth module
   
  },
});

export default store;
