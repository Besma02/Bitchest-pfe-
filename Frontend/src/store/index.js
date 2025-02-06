import { createStore } from "vuex";
import users from "./modules/users";
import auth from "./auth";
import registrationRequests from "./modules/registrationRequests";

const store = createStore({
  modules: {
    users,
    auth,
    registrationRequests,
  },
});

export default store;
