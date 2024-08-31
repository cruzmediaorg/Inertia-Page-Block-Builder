<template>
  <div class="ipbb-option">
    <label :for="name" class="block text-sm font-medium text-gray-700">{{ label }}</label>
    <div class="mt-1 flex items-center space-x-2">
      <div class="relative">
        <div
          class="w-10 h-10 rounded-full shadow-inner  border cursor-pointer"
          :style="{ backgroundColor: modelValue }"
          @click="openColorPicker"
        ></div>
        <input
          :id="name"
          ref="colorInput"
          v-bind="$attrs"
          type="color"
          :value="modelValue"
          @input="updateColor"
          class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
        >
      </div>
      <input
        :id="`${name}-text`"
        type="text"
        :value="modelValue"
        @input="updateColor"
        @blur="validateHex"
        class="flex-1 min-w-0 block w-full px-3 py-2 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300"
        placeholder="#RRGGBB"
      >
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
  label: String,
  name: String,
  modelValue: String,
});

const emit = defineEmits(['update:modelValue']);

const colorInput = ref(null);

const openColorPicker = () => {
  colorInput.value.click();
};

const updateColor = (event) => {
  emit('update:modelValue', event.target.value);
};

const validateHex = (event) => {
  const hex = event.target.value;
  const isValid = /^#[0-9A-Fa-f]{6}$/.test(hex);
  if (!isValid) {
    emit('update:modelValue', props.modelValue);
  }
};
</script>