<template>
  <div class="number-stepper flex items-center">
    <button
      @click="decrement"
      class="px-2 py-1 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md hover:bg-gray-300 focus:outline-none"
      :disabled="disabled"
    >
        -
    </button>
    <input
      type="text"
      :value="displayValue"
      class="w-full px-2 py-1 border-t border-b border-gray-300 text-center"
      readonly
    >
    <button
      @click="increment"
      class="px-2 py-1 bg-gray-200 border border-l-0 border-gray-300 rounded-r-md hover:bg-gray-300 focus:outline-none"
      :disabled="disabled"
    >
      +
    </button>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  modelValue: {
    type: [Number, String],
    required: true,
  },
  step: {
    type: Number,
    default: 1,
  },
  min: {
    type: Number,
    default: 0,
  },
  max: {
    type: Number,
    default: Infinity,
  },
  unit: {
    type: String,
    default: 'px',
  },
  disabled: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['update:modelValue']);

const numericValue = computed(() => {
  return parseInt(props.modelValue) || 0;
});

const displayValue = computed(() => {
  return `${numericValue.value}${props.unit}`;
});

const increment = () => {
  const newValue = Math.min(numericValue.value + props.step, props.max);
  emit('update:modelValue', `${newValue}${props.unit}`);
};

const decrement = () => {
  const newValue = Math.max(numericValue.value - props.step, props.min);
  emit('update:modelValue', `${newValue}${props.unit}`);
};
</script>

<style scoped>
.number-stepper input:read-only {
  background-color: #f9fafb;
  cursor: default;
}

.number-stepper button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>