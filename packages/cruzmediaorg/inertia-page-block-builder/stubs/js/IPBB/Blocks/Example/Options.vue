<template>
    <div class="ipbb-{{name.toLowerCase()}}-options space-y-4 p-4 bg-white rounded-lg shadow">
      <div class="ipbb-option">
        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title:</label>
        <input 
          id="title"
          v-model="localProps.title" 
          @input="updateProps"
          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        />
      </div>
      <div class="ipbb-option">
        <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-1">Subtitle:</label>
        <input 
          id="subtitle"
          v-model="localProps.subtitle" 
          @input="updateProps"
          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        />
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, watch } from 'vue'

  const props = defineProps({
    modelValue: {
      type: Object,
      required: true,
    },
  })

  const emit = defineEmits(['update:modelValue'])

  const localProps = ref({ ...props.modelValue })

  watch(() => props.modelValue, (newValue) => {
    localProps.value = { ...newValue }
  })

  const updateProps = () => {
    emit('update:modelValue', { ...localProps.value })
  }
  </script>
  
