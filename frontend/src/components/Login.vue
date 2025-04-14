<script lang="ts">
import { defineComponent, ref } from 'vue';
import { useRouter } from 'vue-router';
import api from '@/services/axios';

interface LoginResponse {
  token: string
}

export default defineComponent({
  name: 'Login',
  setup() {
    const email = ref('');
    const password = ref('');
    const loading = ref(false);
    const error = ref('');

    const router = useRouter();

    const handleLogin = async () => {
      loading.value = true;
      error.value = '';

      try {
        const response = await api.post<LoginResponse>('/login', {
          email: email.value,
          password: password.value,
        });

        const { data } = response;

        localStorage.setItem('user_token', data.token);

        router.push('/application')
      } catch (err: any) {
        error.value = err.message || 'Erro inesperado ao realizar login';
      } finally {
        loading.value = false;
      }
    };

    return {
      email,
      password,
      loading,
      error,
      handleLogin,
    }
  }
});
</script>

<style scoped>
.login-container {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
  background-color: #f4f4f4;
}

.login-box {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
  width: 320px;
  text-align: center;
}

.login-box h2 {
  margin-bottom: 1.5rem;
}

.login-box input {
  width: 100%;
  padding: 0.75rem;
  margin-bottom: 1rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 1rem;
  background-color: #f9f9f9;
  color: #333;
}

.login-box button {
  width: 100%;
  padding: 0.75rem;
  background-color: #42b983;
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  cursor: pointer;
}

.login-box button:hover {
  background-color: #369d72;
}

.error {
  color: red;
  margin-top: 1rem;
  font-size: 0.9rem;
}
</style>

<template>
  <div class="login-container">
    <div class="login-box">
      <h2>Login</h2>

      <form @submit.prevent="handleLogin">
        <input type="email" v-model="email" placeholder="E-mail" required />

        <input type="password" v-model="password" placeholder="Senha" required />

        <button type="submit" :disabled="loading">
          {{ loading ? 'Acessando...' : 'Entrar' }}
        </button>
      </form>

      <p v-if="error" class="error">{{ error }}</p>
    </div>
  </div>
</template>