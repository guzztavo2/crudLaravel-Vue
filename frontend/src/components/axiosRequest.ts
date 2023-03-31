import axios from "axios";
import User from "./User";
const AxiosInstance = axios.create({
  baseURL: "http://127.0.0.1:8000/api",
  timeout: 5000,
});

AxiosInstance.interceptors.request.use(function (config) {
  const token = User.getLocalStorage();
  config.headers["Authorization"] = "Bearer " + token;
  return config;
});

export default AxiosInstance;