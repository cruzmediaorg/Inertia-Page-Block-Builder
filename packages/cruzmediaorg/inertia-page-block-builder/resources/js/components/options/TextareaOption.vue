<template>
  <div class="ipbb-option">
    <label :for="name" class="block text-sm font-medium text-gray-700">{{ label }}</label>
    <textarea
      :id="name"
      v-bind="$attrs"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
    ></textarea>
  </div>
</template>

<script setup>
defineProps(['label', 'name', 'modelValue'])
defineEmits(['update:modelValue'])
</script>