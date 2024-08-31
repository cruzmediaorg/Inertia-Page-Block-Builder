<template>
  <div class="ipbb-option">
    <label :for="name" class="block text-sm font-medium text-gray-700">{{ label }}</label>
    <select
      :id="name"
      v-bind="$attrs"
      :value="modelValue"
      @change="$emit('update:modelValue', $event.target.value)"
      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
    >
      <option v-for="option in options" :key="option.value" :value="option.value">
        {{ option.text }}
      </option>
    </select>
  </div>
</template>

<script setup>
defineProps({
  label: String,
  name: String,
  modelValue: [String, Number],
  options: {
    type: Array,
    default: () => [],
  },
})
defineEmits(['update:modelValue'])
</script>