<template>
  <div class="ipbb-option">
    <label class="block text-sm font-medium text-gray-700 mb-2">{{ label }}</label>
    <div class="flex">
      <template v-for="(option, index) in options" :key="option.value">
        <label
          :for="`${name}-${option.value}`"
          :class="[
            'flex-1 flex items-center justify-center p-2 border border-gray-300 bg-white cursor-pointer hover:bg-gray-50',
            localModelValue[option.value] ? 'bg-indigo-50 border-indigo-500' : '',
            index === 0 ? 'rounded-l-md' : '',
            index === options.length - 1 ? 'rounded-r-md' : '',
            index !== 0 ? '-ml-px' : ''
          ]"
        >
          <input
            type="checkbox"
            :id="`${name}-${option.value}`"
            :name="name"
            :value="option.value"
            v-model="localModelValue[option.value]"
            class="sr-only"
            @change="updateModelValue"
          >
          <i :class="option.icon"></i>
        </label>
      </template>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  label: String,
  name: String,
  modelValue: {
    type: Object,
    default: () => ({})
  },
  options: {
    type: Array,
    required: true
  },
  attributes: {
    type: Object,
    default: () => ({})
  }
});

const emit = defineEmits(['update:modelValue']);

const localModelValue = ref({ ...props.modelValue });

const updateModelValue = () => {
  emit('update:modelValue', { ...localModelValue.value });
};

watch(() => props.modelValue, (newValue) => {
  localModelValue.value = { ...newValue };
}, { deep: true });
</script>