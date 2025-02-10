import axios from "axios";

export default {
  async submitRegistrationRequest(email) {
    const response = await axios.post(
      "http://localhost:8000/api/registration-request",
      { email }
    );
    return response.data;
  },
}