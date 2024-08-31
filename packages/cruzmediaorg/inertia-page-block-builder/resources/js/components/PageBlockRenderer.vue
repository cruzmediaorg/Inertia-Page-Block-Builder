<template>
  <div class="ipbb-page-block-renderer">
    <template v-for="container in parsedContainers" :key="container.id">
      <div
        class="ipbb-container"
        :style="getContainerStyle(container)"
      >
        <div
          class="ipbb-columns"
          :class="getColumnsClass(container.type)"
        >
          <template v-for="(column, columnIndex) in container.columns" :key="columnIndex">
            <div class="ipbb-column">
              <component
                v-for="block in column.blocks"
                :key="block.id"
                :is="getBlockComponent(block)"
                v-bind="block.props"
              />
            </div>
          </template>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup>
import { ref, onMounted, defineAsyncComponent, computed } from 'vue';

const props = defineProps({
  page: {
    type: Object,
    required: true,
  },
});

const parsedContainers = computed(() => {
  if (typeof props.page.content === 'string') {
    try {
      return JSON.parse(props.page.content);
    } catch (error) {
      console.error('Failed to parse page content:', error);
      return [];
    }
  }
  return props.page.content || [];
});

const getBlockComponent = (block) => {
  return defineAsyncComponent({
    loader: () => import(
      /* @vite-ignore */ `../../../../../../../../resources/js/IPBB/Blocks/${block.name}/Render.vue`
    ),
    error: () => import('./FallbackBlock.vue'),
    onError: (error, retry, fail, attempts) => {
      if (attempts <= 3) {
        retry();
      } else {
        fail();
      }
    },
  });
};

const getContainerStyle = (container) => ({
  backgroundColor: container.attributes.backgroundColor,
  padding: `${container.attributes.paddingTop} ${container.attributes.paddingRight} ${container.attributes.paddingBottom} ${container.attributes.paddingLeft}`,
  margin: `${container.attributes.marginTop} ${container.attributes.marginRight} ${container.attributes.marginBottom} ${container.attributes.marginLeft}`,
  borderRadius: container.attributes.borderRadius,
});

const getColumnsClass = (containerType) => {
  switch (containerType) {
    case 'one-column': return 'grid grid-cols-1';
    case 'two-columns': return 'grid grid-cols-1 md:grid-cols-2';
    case 'three-columns': return 'grid grid-cols-1 md:grid-cols-3';
    case 'four-columns': return 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4';
    default: return 'grid grid-cols-1';
  }
};
</script>

<style scoped>
.ipbb-page-block-renderer {
  width: 100%;
}

.ipbb-container {
  width: 100%;
}

.ipbb-column {
  width: 100%;
}
</style>

