<template>
  <div class="ipbb-page-block-renderer">
    <component
      v-for="block in renderedBlocks"
      :key="block.id"
      :is="block.component"
      v-bind="block.props"
    />
  </div>
</template>

<script setup>
import { ref, onMounted, defineAsyncComponent, computed } from 'vue';

const props = defineProps({
  blocks: {
    type: [Array, String],
    required: true,
  },
});

const parsedBlocks = computed(() => {
  if (typeof props.blocks === 'string') {
    try {
      return JSON.parse(props.blocks);
    } catch (error) {
      console.error('Failed to parse blocks:', error);
      return [];
    }
  }
  return props.blocks;
});

const renderedBlocks = ref([]);

const loadBlockComponent = async (block) => {
  try {
    const componentModule = await import(
      /* @vite-ignore */ `../../../../../../../../resources/js/IPBB/Blocks/${block.name}/Render.vue`
    );
    return {
      id: block.id,
      component: defineAsyncComponent(() => Promise.resolve(componentModule.default)),
      props: block.props,
    };
  } catch (error) {
    console.error(`Failed to load component for block: ${block.name}`, error);
    return null;
  }
};

onMounted(async () => {
  const loadedBlocks = await Promise.all(parsedBlocks.value.map(loadBlockComponent));
  renderedBlocks.value = loadedBlocks.filter(block => block !== null);
});
</script>

