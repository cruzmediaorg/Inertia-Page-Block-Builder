<template>
  <div class="number-stepper-option">
    <label :for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ label }}</label>
    <div class="number-stepper flex items-center">
      <button
        @click="decrement"
        class="px-2 py-1 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md hover:bg-gray-300 focus:outline-none"
        :disabled="isAtMin"
      >
        -
      </button>
      <input
        :id="name"
        type="text"
        :value="displayValue"
        class=" py-1 border-t border-b text-center block w-full  border-gray-300 shadow-sm focus:border-gray-300 focus:ring focus:ring-gray-200 focus:ring-opacity-50  px-4  border focus:outline-none"
        readonly
      >
      <button
        @click="increment"
        class="px-2 py-1 bg-gray-200 border border-l-0 border-gray-300 rounded-r-md hover:bg-gray-300 focus:outline-none"
        :disabled="isAtMax"
      >
        +
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  modelValue: {
    type: [Number, String],
    required: true,
  },
  label: {
    type: String,
    required: true,
  },
  name: {
    type: String,
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
    default: 100,
  },
  append: {
    type: String,
    default: '',
  },
  attributes: {
    type: Object,
    default: () => ({}),
  },
});

const emit = defineEmits(['update:modelValue']);

const numericValue = computed(() => {
  return parseInt(props.modelValue) || props.min;
});

const displayValue = computed(() => {
  const value = numericValue.value.toString();
  return props.append ? `${value}${props.append}` : value;
});

const isAtMin = computed(() => {
  return numericValue.value <= props.min;
});

const isAtMax = computed(() => {
  return numericValue.value >= props.max;
});

const increment = () => {
  if (!isAtMax.value) {
    const newValue = Math.min(numericValue.value + props.step, props.max);
    emitValue(newValue);
  }
};

const decrement = () => {
  if (!isAtMin.value) {
    const newValue = Math.max(numericValue.value - props.step, props.min);
    emitValue(newValue);
  }
};

const emitValue = (value) => {
  emit('update:modelValue', `${value}${props.append}`);
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