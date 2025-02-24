import { createStore } from "vuex";
import users from "./modules/users";
import auth from "./auth";
import registrationRequests from "./modules/registrationRequests";
import crypto from "./modules/crypto";
import transactions from "./modules/transactions";
const store = createStore({
  modules: {
    users,
    auth,
    registrationRequests,
    crypto,
    transactions
  },
});

export default store;
