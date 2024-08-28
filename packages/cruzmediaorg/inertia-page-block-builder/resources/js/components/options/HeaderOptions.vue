<template>
  <div class="ipbb-header-options">
    <div class="ipbb-option">
      <label for="title">Title:</label>
      <input 
        id="title"
        v-model="localProps.title" 
        @input="updateProps"
      />
    </div>
    <div class="ipbb-option">
      <label for="subtitle">Subtitle:</label>
      <input 
        id="subtitle"
        v-model="localProps.subtitle" 
        @input="updateProps"
      />
    </div>
  </div>
</template>

<script>
import { ref, watch } from 'vue'

export default {
  props: {
    modelValue: {
      type: Object,
      required: true,
    },
  },
  emits: ['update:modelValue'],
  setup(props, { emit }) {
    const localProps = ref({ ...props.modelValue })

    watch(() => props.modelValue, (newValue) => {
      localProps.value = { ...newValue }
    })

    const updateProps = () => {
      emit('update:modelValue', { ...localProps.value })
    }

    return {
      localProps,
      updateProps,
    }
  },
}
</script>

<style scoped>
.ipbb-header-options {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.ipbb-option {
  display: flex;
  flex-direction: column;
}

.ipbb-option label {
  margin-bottom: 5px;
}

.ipbb-option input {
  padding: 5px;
  border: 1px solid #ccc;
  border-radius: 4px;
}
</style>