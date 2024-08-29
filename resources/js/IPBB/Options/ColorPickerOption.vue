<template>
  <div class="ipbb-option">
    <label :for="name" class="block text-sm font-medium text-gray-700">{{ label }}</label>
    <div class="mt-1 flex rounded-md shadow-sm">
      <input
        :id="name"
        v-bind="$attrs"
        type="color"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        class="w-12 h-10 border-gray-300 rounded-l-md focus:ring-indigo-500 focus:border-indigo-500"
      >
      <input
        :id="`${name}-text`"
        type="text"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300"
      >
    </div>
  </div>
</template>

<script setup>
defineProps(['label', 'name', 'modelValue'])
defineEmits(['update:modelValue'])
</script>