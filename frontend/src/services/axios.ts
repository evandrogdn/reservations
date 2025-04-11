import axios from 'axios';
import type { AxiosRequestConfig } from 'axios';

const api: any = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json'
  }
});

api.interceptors.request.use((config: AxiosRequestConfig) => {
  const token = localStorage.getItem('user_token');

  if (token && config?.headers) {
    config.headers.Authorization = `Bearer ${token}`;
  }

  return config;
})

export default api;

