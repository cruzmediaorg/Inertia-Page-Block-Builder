<template>
    <div class="ipbb-{{name.toLowerCase()}}-options space-y-4">
      <div class="flex flex-col">
        <label for="title" class="mb-1 text-sm font-medium text-gray-700">Title:</label>
        <input 
          id="title"
          v-model="localProps.title" 
          @input="updateProps"
          class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>
      <div class="flex flex-col">
        <label for="subtitle" class="mb-1 text-sm font-medium text-gray-700">Subtitle:</label>
        <input 
          id="subtitle"
          v-model="localProps.subtitle" 
          @input="updateProps"
          class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>
      <div class="flex flex-col">
        <label for="backgroundColor" class="mb-1 text-sm font-medium text-gray-700">Background Color:</label>
        <input 
          id="backgroundColor"
          v-model="localProps.backgroundColor" 
          @input="updateProps"
          class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
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