<script lang="ts" setup>

const logout = () => {
  localStorage.removeItem('user_token');
  window.location.href = '/'; // Redireciona para login
};

</script>

<template>
  <div class="layout">
    <aside class="sidebar">
      <router-link to="/application/inicio">Início</router-link>
      <router-link to="/application/reservas">Reservas</router-link>
      <router-link to="/application/mesas">Mesas</router-link>

      <button class="logout-btn" @click="logout">Sair</button>      
    </aside>

    <main class="content">
      <h1>Bem-vindo ao Painel de Reservas!</h1>
      <router-view />
    </main>
  </div>
</template>

<style scoped>
.layout {
  display: flex;
  height: 100vh;
}

.sidebar {
  width: 200px;
  background-color: #2c3e50;
  padding: 1rem;
  display: flex;
  flex-direction: column;
}

.sidebar a {
  color: #ecf0f1;
  text-decoration: none;
  margin-bottom: 1rem;
}

.sidebar a.router-link-active {
  font-weight: bold;
  color: #42b983;
}

.logout-btn {
  padding: 0.5rem;
  background-color: #e74c3c;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}

.logout-btn:hover {
  background-color: #c0392b;
}

.content {
  flex: 1;
  padding: 2rem;
}
</style>
