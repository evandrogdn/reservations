<script setup lang="ts">
import { ref, onMounted } from 'vue';

import api from '@/services/axios';
import ReservationModal from '@/components/ReservationModal.vue';

interface Reservation {
  id?: number,
  client_name: string,
  table_id: number,
  phone_contact: string,
  email_contact: string,
  guests: number,
  reservation_start: string,
  reservation_end: string,
}

const reservations = ref<Reservation[]>([]);
const loading = ref(false);
const error = ref('');

// filters
const filterClientName = ref('');
const filterReservationStart = ref('');
const filterReservationEnd = ref('');

const filtersApplied = ref(false);

// modal
const showModal = ref(false);
const selectedReservation = ref<Reservation | null>(null);

const applyFilters = () => {
  fetchReservations();
}

const resetFilters = () => {
  filterClientName.value = '';
  filterReservationStart.value = '';
  filterReservationEnd.value = '';
  fetchReservations();
}

const fetchReservations = async () => {
  loading.value = true;
  error.value = '';

  try {
    const params: any = {};
    if (filterClientName.value) {
      params.client_name = filterClientName.value;
    }
    if (filterReservationStart.value) {
      params.reservation_start = filterReservationStart.value;
    }
    if (filterReservationEnd.value) {
      params.reservation_end = filterReservationEnd.value;
    }

    const response = await api.get<Reservation[]>('/reservations', {params});
    reservations.value = response.data;
  } catch (err: any) {
    error.value = err.message || 'Erro inesperado ao carregar as reservas';
  } finally {
    loading.value = false;
  }
}

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

const maskPhone = (phone: string) => {
  return phone?.length === 11 ? phone.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3'): phone.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
}

const addReservation = () => {
  selectedReservation.value = null;
  showModal.value = true;
}

const editReservation = (reservationId: number) => {
  const reservation = reservations.value.find(res => res.id === reservationId);
  if (reservation) {
    selectedReservation.value = reservation;
    showModal.value = true;
  }
}

onMounted(() => {
  fetchReservations();
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
  <div class="reservations">
    <div class="header">
      <h2>Listagem de Reservas</h2>
      <button class="add-btn" @click="addReservation">+ Adicionar Mesa</button>
    </div>

    <div class="filters">
      <input v-model="filterClientName" type="text" placeholder="Cliente" />
      <input v-model="filterReservationStart" type="date" placeholder="Data de Início" />
      <input v-model="filterReservationEnd" type="date" placeholder="Data de Fim" />
      <button @click="applyFilters">Filtrar</button>
      <button @click="resetFilters">Limpar Filtros</button>
    </div>

    <div v-if="loading" class="loading">Carregando...</div>
    <div v-if="error" class="error">{{ error }}</div>
    <div v-if="!loading && reservations.length === 0" class="no-data">Nenhuma reserva encontrada.</div>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Cliente</th>
          <th>Mesa</th>
          <th>Telefone</th>
          <th>Entrada</th>
          <th>Saída</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="reservation in reservations" :key="reservation.id">
          <td>{{ reservation.id }}</td>
          <td>{{ reservation.client_name }}</td>
          <td>{{ reservation.table_id }}</td>
          <td>{{ maskPhone(reservation.phone_contact) }}</td>
          <td>{{ formatDate(reservation.reservation_start) }}</td>
          <td>{{ formatDate(reservation.reservation_end) }}</td>
          <td class="actions">
            <button class="edit-btn" @click="editReservation(reservation.id)">Editar</button>
            <button class="delete-btn" @click="deleteReservation(reservation.id)">Excluir</button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="!loading && reservations.length > 0" class="table-info">
      <p>Total de reservas: {{ reservations.length }}</p>
      <p>Última atualização: {{ formatDate(new Date().toISOString()) }}</p>
    </div>
  </div>

  <ReservationModal
    :visible="showModal"
    :reservation="selectedReservation"
    @close="showModal = false"
    @refresh="fetchReservations"
  />
</template>