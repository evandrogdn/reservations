<template>
  <div class="layout">
    <aside class="sidebar">
      <router-link to="/application/inicio">Início</router-link>
      <router-link to="/application/reservas">Reservas</router-link>
      <router-link to="/application/mesas">Mesas</router-link>
    </aside>

    <main class="content">
      <h1>Bem-vindo ao Painel de Reservas!</h1>
      <p>Selecione uma opção no menu à esquerda para começar.</p>
      <router-view />
    </main>
  </div>
</template>

<script lang="ts" setup>
// Nenhuma lógica por enquanto
</script>

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

.content {
  flex: 1;
  padding: 2rem;
}
</style>
