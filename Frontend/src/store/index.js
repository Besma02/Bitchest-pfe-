import { createStore } from "vuex";
import users from "./modules/users";
import auth from "./auth";


const store = createStore({
  modules: {
    users,
    auth
 
  },
});

export default store; 
