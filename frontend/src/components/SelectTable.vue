<script lang="ts" setup>
import { ref, onMounted, defineEmits, defineProps } from 'vue';
import api from '@/services/axios';

interface Table {
  id: number;
  identification: string;
}

const props = defineProps<{
  modelValue: number | null;
}>();

const emit = defineEmits<{
  (e: 'update:modelValue', value: number | null): void;
}>();

const tables = ref<Table[]>([]);
const loading = ref(false);

onMounted(async () => {
  loading.value = true;
  try {
    const response = await api.get<Table[]>('/tables');
    tables.value = response.data;
  } catch (error) {
    console.error('Erro ao carregar as mesas:', error);
  } finally {
    loading.value = false;
  }
});

</script>

<style scoped>
select {
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 6px;
  background-color: #f9f9f9;
  color: #333;
  font-size: 1rem;
  width: 100%;
}
</style>

<template>
  <div>
    <select :value="modelValue" @change="$emit('update:modelValue', Number($event.target.value))">
      <option value="" disabled selected>Selecione uma mesa</option>
      <option v-for="table in tables" :key="table.id" :value="table.id">
        {{ table.identification }}
      </option>
    </select>
  </div>
</template>