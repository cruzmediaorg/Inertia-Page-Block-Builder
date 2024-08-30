<template>
    <div class="container-preview border-2 border-dotted hover:border-green-400 transition-colors duration-300 hover:cursor-pointer hover:scale-105 " :class="containerClass">
      <div v-for="(column, index) in columns" :key="index" class="column">
        <div class="block"></div>
      </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  type: {
    type: String,
    required: true,
  },
});

const containerClass = computed(() => props.type);

const columns = computed(() => {
  switch (props.type) {
    case 'full':
      return [1];
    case 'half':
      return [1, 1];
    case 'third':
      return [1, 1, 1];
    case 'quarter':
      return [1, 1, 1, 1];
    case 'left-sidebar':
      return [1, 3];
    case 'right-sidebar':
      return [3, 1];
    default:
      return [1];
  }
});
</script>

<style scoped>
.container-preview {

  border-radius: 0.25rem;
  padding: 0.5rem;
  margin-bottom: 0.5rem;
  height: 40px;
  display: flex;
}

.column {
  flex: 1;
  display: flex;
  padding: 0 2px;
}

.block {
  background-color: #e2e8f0;
  border-radius: 0.25rem;
  flex: 1;
}

.full-width {
  display: flex;
}

.two-column, .three-column, .four-column, .left-sidebar, .right-sidebar {
  display: flex;
  gap: 4px;
}

.left-sidebar .column:first-child,
.right-sidebar .column:last-child {
  flex: 0 0 25%;
}
</style>