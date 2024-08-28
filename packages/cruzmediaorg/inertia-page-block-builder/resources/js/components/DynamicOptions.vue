<template>
  <div class="ipbb-options">
    <component
      v-for="(option, index) in options"
      :key="index"
      :is="getComponentType(option.type)"
      v-bind="option"
      :modelValue="localProps[option.name]"
      @update:modelValue="updateLocalProp(option.name, $event)"
    />
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import InputOption from './options/InputOption.vue'
import TextareaOption from './options/TextareaOption.vue'

const props = defineProps({
  modelValue: {
    type: Object,
    required: true,
  },
  options: {
    type: Array,
    required: true,
  },
})

const emit = defineEmits(['update:modelValue'])

const localProps = ref({ ...props.modelValue })

watch(() => props.modelValue, (newValue) => {
  localProps.value = { ...newValue }
}, { deep: true })

const updateLocalProp = (name, value) => {
  localProps.value[name] = value
  emit('update:modelValue', { ...localProps.value })
}

const getComponentType = (type) => {
  switch (type) {
    case 'input':
      return InputOption
    case 'textarea':
      return TextareaOption
    default:
      return null
  }
}

onMounted(() => {
  console.log('Initial localProps:', localProps.value)
})
</script>