<template>
    <div class="spacing-input">
      <div class="flex items-center justify-between mb-2">
        <label class="block text-sm font-medium text-gray-700">{{ label }}</label>
        <button
          @click="toggleSync"
          class="text-xs text-gray-500 hover:text-gray-700 focus:outline-none"
          :class="{ 'text-green-600 hover:text-gray-800': synced }"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
          </svg>
        </button>
      </div>
      <div class="grid grid-cols-2 gap-2">
        <div v-for="side in ['top', 'right', 'bottom', 'left']" :key="side" class="relative">
          <NumberStepper
            :modelValue="synced ? values.top : values[side]"
            @update:modelValue="updateValue(side, $event)"
            :step="5"
            :disabled="synced && side !== 'top'"
          />
         <span class="flex justify-center uppercase inset-y-0 text-xs text-gray-400 text-center w-full ">
            {{ side[0]}}
          </span>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, watch } from 'vue';
  import NumberStepper from './NumberStepperOption.vue';
  
  const props = defineProps({
    label: {
      type: String,
      required: true,
    },
    modelValue: {
      type: Object,
      required: true,
    },
  });
  
  const emit = defineEmits(['update:modelValue']);
  
  const synced = ref(false);
  const values = ref({ ...props.modelValue });
  
  watch(() => props.modelValue, (newValue) => {
    values.value = { ...newValue };
  }, { deep: true });
  
  const updateValue = (side, value) => {
    if (synced.value) {
      Object.keys(values.value).forEach(key => {
        values.value[key] = value;
      });
    } else {
      values.value[side] = value;
    }
    emit('update:modelValue', values.value);
  };
  
  const toggleSync = () => {
    synced.value = !synced.value;
    if (synced.value) {
      const syncedValue = values.value.top;
      Object.keys(values.value).forEach(key => {
        values.value[key] = syncedValue;
      });
      emit('update:modelValue', values.value);
    }
  };
  
  const formatValue = (side) => {
    let value = values.value[side];
    if (typeof value === 'string') {
      value = value.trim();
      if (value === '') {
        value = '0px';
      } else if (!value.endsWith('px')) {
        value += 'px';
      }
    } else {
      value = '0px';
    }
    updateValue(side, value);
  };
  </script>
  
  <style scoped>
  .spacing-input input:disabled {
    background-color: #f3f4f6;
    cursor: not-allowed;
  }
  </style>