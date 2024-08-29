<template>
  <div class="ipbb-option">
    <label :for="name" class="block text-sm font-medium text-gray-700">{{ label }}</label>
    <select
      :id="name"
      v-bind="$attrs"
      :value="modelValue"
      @change="$emit('update:modelValue', $event.target.value)"
      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
    >
      <option v-for="option in options" :key="option" :value="option">
        {{ option }}
      </option>
    </select>
  </div>
</template>

<script setup>
defineProps(['label', 'name', 'modelValue', 'options'])
defineEmits(['update:modelValue'])
</script>