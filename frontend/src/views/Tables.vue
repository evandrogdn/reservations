<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import api from '@/services/axios';

interface Table {
  id: number;
  identification: string;
  places: number;
}

const tables = ref<Table[]>([]);
const loading = ref(false);
const error = ref('');

// controls to show modal
const showModal = ref(false);
const isEdit = ref(false);
const form = ref<Table>({
  id: null,
  identification: '',
  places: 0,
});

const fetchTables = async () => {
  loading.value = true;
  error.value = '';

  try {
    const response = await api.get<Table[]>('/tables');
    tables.value = response.data;
  } catch (err: any) {
    error.value = err.message || 'Erro inesperado ao carregar as mesas';
  } finally {
    loading.value = false;
  }
};

const addTable = () => {
  isEdit.value = false;
  form.value = {
    id: null,
    identification: '',
    places: 0,
  };
  showModal.value = true;
}

const editTable = (id: number) => {
  const table = tables.value.find((table) => table.id === id);
  if (table) {
    form.value = { ...table };
    isEdit.value = true;
    showModal.value = true;
  }
};

const deleteTable = async (id: number) => {
  if (confirm('Tem certeza que deseja excluir esta mesa?')) {
    loading.value = true;
    error.value = '';

    try {
      await api.delete(`/tables/${id}`);
    } catch (err: any) {
      error.value = err.message || 'Erro inesperado ao excluir a mesa';
    } finally {
      loading.value = false;
      await fetchTables();
    }
  }
};

const closeModal = () => {
  showModal.value = false;
};

const submitTable = async () => {
  loading.value = true;
  error.value = '';

  try {
    if (isEdit.value) {
      await api.put(`/tables/${form.value.id}`, form.value);
    } else {
      await api.post('/tables', form.value);
    }
    await fetchTables();
    closeModal();
  } catch (err: any) {
    error.value = err.message || 'Erro inesperado ao salvar a mesa';
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchTables();
});
</script>

<style scoped>
  .tables-container {
    max-width: 900px;
    margin: 2rem auto;
    padding: 1rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 0 12px rgba(0, 0, 0, 0.05);
  }

  .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
  }

  .add-btn {
    background-color: #42b983;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    cursor: pointer;
  }

  .add-btn:hover {
    background-color: #369d72;
  }

  .tables-list {
    width: 100%;
    border-collapse: collapse;
  }

  .tables-list th,
  .tables-list td {
    border: 1px solid #eee;
    padding: 0.75rem;
    text-align: left;
    color: #000000;
  }

  .tables-list th {
    background-color: #f9f9f9;
  }

  .actions {
    display: flex;
    gap: 0.5rem;
  }

  .edit-btn,
  .delete-btn {
    border: none;
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9rem;
  }

  .edit-btn {
    background-color: #3498db;
    color: white;
  }

  .edit-btn:hover {
    background-color: #2980b9;
  }

  .delete-btn {
    background-color: #e74c3c;
    color: white;
  }

  .delete-btn:hover {
    background-color: #c0392b;
  }

  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
  }

  .modal {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 0 12px rgba(0, 0, 0, 0.2);
  }

  .modal h3 {
    margin-bottom: 1.5rem;
    color: #000000;
  }

  .modal input,
  .modal select {
    width: 100%;
    padding: 0.6rem;
    margin-bottom: 1rem;
    border: 1px solid #ccc;
    border-radius: 6px;
    background: #f9f9f9;
    color: #000000;
  }

  .modal-actions {
    display: flex;
    justify-content: space-between;
  }

  .modal-actions button {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 6px;
    cursor: pointer;
  }

  .modal-actions button:first-child {
    background-color: #369d72;
    color: white;
  }

  .modal-actions button:last-child {
    background-color: #e74c3c;
  }

</style>

<template>
  <div class="tables-container">
    <div class="header">
      <h2>Listagem de Mesas</h2>
      <button class="add-btn" @click="addTable">+ Adicionar Mesa</button>
    </div>

    <div v-if="loading" class="status">Carregando mesas...</div>
    <div v-else-if="error" class="status error">{{ error }}</div>

    <table v-else class="tables-list">
      <thead>
        <tr>
          <th>ID</th>
          <th>Identificacao</th>
          <th>Lugares</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="table in tables" :key="table.id">
          <td>{{ table.id }}</td>
          <td>{{ table.identification }}</td>
          <td>{{ table.places }}</td>
          <td class="actions">
            <button class="edit-btn" @click="editTable(table.id)">Editar</button>
            <button class="delete-btn" @click="deleteTable(table.id)">Excluir</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- modal to edit and add a table -->
    <div v-if="showModal" class="modal-overlay">
      <div class="modal">
        <h3>{{isEdit ? 'Editar mesa': 'Nova mesa'}}</h3>
        <form @submit.prevent="submitTable">
          <input type="text" v-model="form.identification" placeholder="Identificação" required />
          <input type="number" v-model="form.places" placeholder="Lugares" required />
          <div class="modal-actions">
            <button type="submit">{{ isEdit ? 'Atualizar' : 'Adicionar'}}</button>
            <button type="button" @click="closeModal">Fechar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>