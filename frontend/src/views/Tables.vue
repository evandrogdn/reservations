<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import api from '@/services/axios';
import TableModal from '@/components/TableModal.vue';

interface Table {
  id: number;
  identification: string;
  places: number;
}

const tables = ref<Table[]>([]);
const loading = ref(false);
const error = ref('');

// filters
const filterIdentification = ref('');
const filterPlaces = ref('');

// modal
const showModal = ref(false);
const selectedTable = ref<Table | null>(null);

const applyFilters = () => {
  fetchTables();
}

const resetFilters = () => {
  filterIdentification.value = '';
  filterPlaces.value = '';
  fetchTables();
}

const fetchTables = async () => {
  loading.value = true;
  error.value = '';

  try {
    const params: any = {};

    if (filterIdentification.value) {
      params.identification = filterIdentification.value;
    }
    if (filterPlaces.value) {
      params.places = filterPlaces.value;
    }

    const response = await api.get<Table[]>('/tables', { params });
    tables.value = response.data;
  } catch (err: any) {
    error.value = err.message || 'Erro inesperado ao carregar as mesas';
  } finally {
    loading.value = false;
  }
};

const addTable = () => {
  selectedTable.value = null;
  showModal.value = true;
}

const editTable = (id: number) => {
  const table = tables.value.find((table) => table.id === id);
  if (table) {
    selectedTable.value = table;
    showModal.value = true;
  }
};

const formatDate = (dateString: string) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('pt-BR', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
  });
}

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

onMounted(() => {
  fetchTables();
});
</script>

<style scoped>
.reservations {
  padding: 1rem;
}

.reservations h2 {
  margin-bottom: 1rem;
}

table {
  width: 100%;
  border-collapse: collapse;
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
}

th,
td {
  padding: 1rem;
  text-align: left;
  border-bottom: 1px solid #eee;
  color: #000000;
}

th {
  background-color: #f4f4f4;
  font-weight: 600;
}

tr:hover {
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
  .table-info {
    margin-top: 1rem;
    font-size: 0.9rem;
    color: #555;
  }
  .loading {
    text-align: center;
    font-size: 1.2rem;
    color: #555;
  }
  .error {
    color: red;
    text-align: center;
    font-size: 1rem;
  }
  .no-data {
    text-align: center;
    font-size: 1.2rem;
    color: #555;
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
  .actions {
    display: flex;
    gap: 0.5rem;
  }

  .filters {
  display: flex;
  gap: 1rem;
  margin-bottom: 1rem;
}

.filters input {
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 6px;
  background-color: #f9f9f9;
  color : #333;
}
.filters button {
  padding: 0.5rem 1rem;
  background-color: #3498db;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}
</style>

<template>
  <div class="tables-container">
    <div class="header">
      <h2>Listagem de Mesas</h2>
      <button class="add-btn" @click.prevent="addTable">+ Adicionar Mesa</button>
    </div>

    <div class="filters">
      <input v-model="filterIdentification" type="text" placeholder="Filtrar por identificação" />
      <input v-model="filterPlaces" type="number" placeholder="Filtrar por lugares" />
      <button @click="applyFilters">Filtrar</button>
      <button @click="resetFilters">Limpar</button>
    </div>

    <div v-if="loading" class="status">Carregando mesas...</div>
    <div v-else-if="error" class="status error">{{ error }}</div>
    <div v-if="!loading && tables.length === 0" class="no-data">Nenhuma mesa encontrada.</div>

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

    <div v-if="!loading && tables.length > 0" class="table-info">
      <p>Total de Mesas: {{ tables.length }}</p>
      <p>Última atualização: {{ formatDate(new Date().toISOString()) }}</p>
    </div>

    <!-- modal to edit and add a table -->
    <TableModal
      :visible="showModal"
      :table="selectedTable"
      @close="showModal = false"
      @refresh="fetchTables"
    />
  </div>
</template>