<script lang="ts" setup>
import { ref, watch, defineEmits, defineProps, reactive } from 'vue';
import api from '@/services/axios';

const emit = defineEmits(['close', 'refresh']);

const props = defineProps<{
  visible: boolean;
  table?: any;
}>();

const form = reactive({
  identification: props.table?.identification || '',
  places: props.table?.places || null,
})

watch(() => props.table, (newVal) => {
  if (!newVal) {
    Object.assign(form, newVal);
  } else {
    Object.assign(form, {
      identification: newVal.identification,
      places: newVal.places,
    });
  }
}, {
  immediate: true,
});

const resetForm = () => {
  form.identification = '';
  form.places = null;
};

const submitForm = async () => {
  try {
    if (props.table?.id) {
      await api.put(`/tables/${props.table?.id}`, form);
    } else {
      await api.post('/tables', form);
    }
    emit('refresh');
    emit('close');
    resetForm();
  } catch (error) {
    console.error(error);
  } finally {
    emit('close');
  }
};

const close = () => {
  emit('close');
  resetForm();
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

h2 {
  margin-bottom: 1rem;
  color: #333;
}
</style>

<template>
  <div class="modal-overlay" v-if="visible">
    <div class="modal-content">
      <h2>Controle de Mesas</h2>

      <label for="identification">Identificação</label>
      <input type="text" id="identification" v-model="form.identification" placeholder="Identificação da mesa"/>

      <label for="places">Lugares</label>
      <input type="number" id="places" v-model="form.places" placeholder="Número de lugares"/>

      <div class="modal-actions">
        <button @click="submitForm">Salvar</button>
        <button @click="close">Cancelar</button>
      </div>
    </div>
  </div>
</template>