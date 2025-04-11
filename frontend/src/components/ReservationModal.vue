<script setup lang="ts">
import { reactive, watch, defineEmits } from 'vue';
import api from '@/services/axios';

const emit = defineEmits(['close', 'refresh']);

const props = defineProps<{
  visible: boolean;
  reservation?: any;
}>();

const isEdit = !!props.reservation;

const form = reactive({
  client_name: props.reservation?.client_name || '',
  table_id: props.reservation?.table_id || null,
  phone_contact: props.reservation?.phone_contact || '',
  email_contact: props.reservation?.email_contact || '',
  guests: props.reservation?.guests || null,
  reservation_start: props.reservation?.reservation_start || '',
  reservation_end: props.reservation?.reservation_end || '',
});

watch(
  () => props.reservation,
  (newVal) => {
    if (!newVal) {
      Object.assign(form, newVal);
    } else {
      Object.assign(form, {
        client_name: newVal.client_name,
        table_id: newVal.table_id,
        phone_contact: newVal.phone_contact,
        email_contact: newVal.email_contact,
        guests: newVal.guests,
        reservation_start: newVal.reservation_start,
        reservation_end: newVal.reservation_end,
      });
    }
  }, {
    immediate: true,
  }
);

const submitForm = async () => {
  try {
    if (isEdit) {
      await api.put(`/reservations/${props.reservation?.id}`, form);
    } else {
      await api.post('/reservations', form);
    }
    emit('refresh');
    emit('close');
  } catch (error) {
   alert(error || 'Ocorreu um erro inesperado ao salvar a reserva');
  }
};

const close = () => {
  emit('close');
};
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 999;
}

.modal-content {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  width: 500px;
  max-width: 90%;
}

.modal-content label {
  display: block;
  margin: 1rem 0 0.5rem;
}

.modal-content input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 6px;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  margin-top: 1.5rem;
  gap: 1rem;
}

.modal-actions button {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 8px;
  cursor: pointer;
}

.modal-actions button:first-child {
  background-color: #42b983;
  color: white;
}

.modal-actions button:last-child {
  background-color: #ccc;
}

input {
  background-color: #f9f9f9;
  color: #333;
}

label {
  font-weight: bold;
  color: #333;
  text-align: left;
}

</style>

<template>
  <div class="modal-overlay" v-if="visible">
    <div class="modal-content">
      <h2>{{ isEdit ? 'Editar Reserva' : 'Criar Reserva' }}</h2>
      <label for="client_name">Nome do Cliente:</label>
      <input type="text" id="client_name" v-model="form.client_name" />

      <label for="table_id">Mesa:</label>
      <input type="number" id="table_id" v-model="form.table_id" />

      <label for="phone_contact">Telefone:</label>
      <input type="text" id="phone_contact" v-model="form.phone_contact" />

      <label for="email_contact">Email:</label>
      <input type="email" id="email_contact" v-model="form.email_contact" />

      <label for="guests">Número de Convidados:</label>
      <input type="number" id="guests" v-model="form.guests" />

      <label for="reservation_start">Início da Reserva:</label>
      <input type="datetime-local" id="reservation_start" v-model="form.reservation_start" />

      <label for="reservation_end">Fim da Reserva:</label>
      <input type="datetime-local" id="reservation_end" v-model="form.reservation_end" />

      <div class="modal-actions">
        <button @click.prevent="submitForm">{{ isEdit ? 'Salvar' : 'Criar' }}</button>
        <button @click.prevent="close">Cancelar</button>
      </div>
    </div>
  </div>
</template>