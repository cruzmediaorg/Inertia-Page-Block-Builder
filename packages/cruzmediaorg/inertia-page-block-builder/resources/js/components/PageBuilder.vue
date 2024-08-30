<template>
  <div
    class="flex flex-col h-screen bg-gray-100"
    :class="{ 'mobile-preview': isMobilePreview }"
  >
    <div class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
          <div></div>
          <div class="hidden md:flex space-x-4">
            <button
              @click="toggleMobilePreview"
              class="bg-white p-2 border rounded-md"
              :class="{ 'text-blue-500': isMobilePreview }"
            >
              <svg v-if="isMobilePreview" class="w-6 h-6 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M5 4a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V4Zm12 12V5H7v11h10Zm-5 1a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H12Z" clip-rule="evenodd" />
              </svg>
              <svg v-else class="w-6 h-6 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v5m-3 0h6M4 11h16M5 15h14a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1Z" />
              </svg>
            </button>
            <button @click="toggleFullScreen" class="bg-white p-2 border rounded-md">
              <svg class="w-6 h-6 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H4m0 0v4m0-4 5 5m7-5h4m0 0v4m0-4-5 5M8 20H4m0 0v-4m0 4 5-5m7 5h4m0 0v-4m0 4-5-5" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="flex-1 overflow-hidden border-t">
      <div class="flex flex-col md:flex-row h-full">
        <div
          :class="[containerClass, 'w-full md:w-[calc(100%-320px)] overflow-y-auto p-4']"
          @dragover.prevent
          @drop="handleDrop"
        >
          <draggable
            v-model="containers"
            :item-key="(container) => container.id"
            handle=".container-drag-handle"
            @start="dragStart"
            @end="dragEnd"
          >
          <template #item="{ element: container }">
              <div class="container border border-dashed border-gray-300 relative" @dragover.prevent @drop.stop="handleDrop($event, container.id)" :data-container-id="container.id" :style="{
                backgroundColor: container.attributes.backgroundColor,
                padding: container.attributes.padding,
                margin: container.attributes.margin,
                borderRadius: container.attributes.borderRadius,
                display: container.attributes.hideOnMobile && isMobilePreview ? 'none' : 'block',
              }" >           
                <div class="flex flex-wrap -mx-2">
                   <!-- Add this button for container selection -->
                   <button 
                  @click.stop="selectContainer(container.id)"
                  class="absolute top-2 left-2 z-50 bg-white p-1 rounded-full shadow-md hover:bg-gray-100 transition-colors duration-200"
                >
                  <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                  </svg>
                </button>
                  <draggable
                    :list="container.blocks"
                    :item-key="(block) => block.id || block.placeholderId"
                    handle=".block-drag-handle"
                    group="blocks"
                    class="flex flex-wrap w-full"
                    @start="dragStart"
                    @end="dragEnd"
                  >
                    <template #item="{ element: block, index }">
                      <div :class="getBlockWrapperClass(container.type)" :data-block-id="block.id">
                        <template v-if="block.isPlaceholder">
                          <div 
                            class="block-placeholder min-h-24 h-full border-2 border-dashed border-gray-300  flex items-center justify-center"
                            @dragover.prevent
                            @drop.stop="handleDrop($event, container.id, index)"
                          >
                            <p class="text-gray-400 text-sm">Drag a block here</p>
                          </div>
                        </template>
                        <template v-else>
                          <div
                            class="group relative h-full"
                            :class="{
                              'ring-2 ring-blue-500': selectedBlock === block.id,
                            }"
                            @click="selectBlock(block.id)"
                          >
                            <component :is="getBlockComponent(block)" v-bind="block.props" />
                            <BlockActions
                              class="block-actions"
                              @edit="selectBlock(block.id)"
                              @delete="deleteBlock(container.id, block.id)"
                              @duplicate="duplicateBlock(container.id, block.id)"
                            />
                          </div>
                        </template>
                      </div>
                    </template>
                  </draggable>
                </div>
                <div v-if="!hasPlaceholders(container)" 
                  :class="{ 'absolute -bottom-6 left-0 right-0 flex justify-center z-[10]': true, 'hidden': isMobilePreview }"
                 class="container-drag-handle cursor-move absolute -bottom-6 left-0 right-0 flex justify-center z-[10]">
                  <button @click="addNewRow(container)" class=" bg-white text-gray-500  rounded-b-md py-0 px-12 hover:bg-gray-100 transition-colors border-black">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                  </button>
                </div>
              </div>
            </template>
          </draggable>
          <div
            v-if="containers.length === 0"
            class="flex justify-center items-center p-5 flex-col h-full min-w-80 bg-white"
          >
            <p class="text-gray-500 text-center font-bold">No containers added</p>
            <p class="text-gray-500 text-center">Drag and drop a container to get started</p>
          </div>
        </div>
        <Sidebar
          :containers="containers"
          :selected-block="selectedBlockData"
          :selected-container="selectedContainer"
          :is-editing="isEditing"
          :container-types="containerTypes"
          :available-blocks="availableBlocks"
          @add-container="addContainer"
          @add-block="addBlock"
          @update-block-props="updateBlockProps"
          @finish-editing="finishEditing"
          @save-blocks="saveBlocks"
          @update-container-attributes="updateContainerAttributes"
        />
      </div>
    </div>
  </div>
  <pre>
    {{ containers }}
  </pre>
</template>

<script setup>
import { ref, computed, defineAsyncComponent, watch } from "vue";
import draggable from "vuedraggable";
import FallbackBlock from "./FallbackBlock.vue";
import BlockActions from "./BlockActions.vue";
import Sidebar from "./Sidebar.vue";
import "../../css/style.css";

const props = defineProps({
  registeredBlocks: {
    type: Array,
    required: true,
  },
  data: {
    type: Array,
    required: false,
    default: () => [],
  },
});

const emit = defineEmits(['save']);

const containers = ref(props.data.map(container => ({
  ...container,
  attributes: {
    backgroundColor: '#ffffff',
    padding: '0px',
    margin: '0px',
    borderRadius: '0px',
    hideOnMobile: false,
  },
})));
const selectedBlock = ref(null);
const selectedBlockData = ref(null);
const isEditing = ref(false);
const isDragging = ref(false);
const draggedItem = ref(null);
const selectedContainer = ref(null);

const containerTypes = [
  { type: 'full', name: 'Full Width (1 block per row)', blocksPerRow: 1 },
  { type: 'half', name: '1/2 - 1/2 (2 blocks per row)', blocksPerRow: 2 },
  { type: 'third', name: '1/3 - 1/3 - 1/3 (3 blocks per row)', blocksPerRow: 3 },
  { type: 'quarter', name: '1/4 - 1/4 - 1/4 - 1/4 (4 blocks per row)', blocksPerRow: 4 },
];

const availableBlocks = computed(() => {
  if (!Array.isArray(props.registeredBlocks)) {
    return [];
  }

  return props.registeredBlocks.map((block) => ({
    name: block.name,
    reference: block.reference,
    component: defineAsyncComponent({
      loader: () =>
        import(
          /* @vite-ignore */ `../../../../../../../../resources/js/IPBB/Blocks/${block.render}.vue`
        ),
      error: FallbackBlock,
      onError: (error, retry, fail, attempts) => {
        if (attempts <= 3) {
          retry();
        } else {
          fail();
        }
      },
    }),
    options: block.options,
    defaultProps: block.data,
    icon: "fas fa-cube",
  }));
});

const dragStart = (evt) => {
  isDragging.value = true;
  if (evt.item.dataset.blockId) {
    draggedItem.value = { type: 'block', id: evt.item.dataset.blockId };
  } else if (evt.item.dataset.containerId) {
    draggedItem.value = { type: 'container', id: evt.item.dataset.containerId };
  }
};

const dragEnd = () => {
  isDragging.value = false;
  draggedItem.value = null;
};

const handleDrop = (event, containerId, index) => {

  event.preventDefault();

  if (draggedItem.value && draggedItem.value.type === 'block') {
    const sourceContainer = containers.value.find(c => c.blocks.some(b => b.id === draggedItem.value.id));
    const targetContainer = containers.value.find(c => c.id === containerId);

    if (sourceContainer && targetContainer) {
      const blockToMove = sourceContainer.blocks.find(b => b.id === draggedItem.value.id);
      const sourceIndex = sourceContainer.blocks.findIndex(b => b.id === draggedItem.value.id);

      sourceContainer.blocks[sourceIndex] = {
        isPlaceholder: true,
        id: `placeholder-${Date.now()}-${sourceIndex}`,
        placeholderId: `placeholder-${Date.now()}-${sourceIndex}`,
        index: sourceIndex
      };

      addBlock(blockToMove, targetContainer.id, index);

      draggedItem.value = null;
      return;
    }
  }

  let data;
  try {
    data = JSON.parse(event.dataTransfer.getData('text/plain'));
  } catch (error) {
    console.error('Failed to parse drag data', error);
    return;
  }

  if (data.type === 'container' && !containerId) {
    const dropIndex = getDropIndex(event);
    addContainer(data.containerType.type, dropIndex);
  } else if (data.type === 'block') {
    let targetContainerId = containerId;
    if (!targetContainerId) {
      targetContainerId = getNearestContainerId(event);
      if (!targetContainerId) {
        addContainer('full');
        targetContainerId = containers.value[0].id;
      }
    }
    const container = containers.value.find(c => c.id === targetContainerId);
    if (container) {
      addBlock(data.block, targetContainerId, index);
    }
  }

  event.dataTransfer.dropEffect = 'move';
  event.dataTransfer.clearData();
};

const getDropIndex = (event) => {
  const containerElements = document.querySelectorAll('.container');
  for (let i = 0; i < containerElements.length; i++) {
    const rect = containerElements[i].getBoundingClientRect();
    if (event.clientY < rect.bottom) {
      return i;
    }
  }
  return containers.value.length;
};

const getDropIndexInContainer = (event, container) => {
  const containerElement = document.querySelector(`[data-container-id="${container.id}"]`);
  if (!containerElement) return container.blocks.length;

  const blockElements = containerElement.querySelectorAll('[data-block-id]');
  const containerRect = containerElement.getBoundingClientRect();
  const relativeY = event.clientY - containerRect.top;

  for (let i = 0; i < blockElements.length; i++) {
    const blockRect = blockElements[i].getBoundingClientRect();
    const blockMiddle = blockRect.top + blockRect.height / 2 - containerRect.top;
    if (relativeY < blockMiddle) {
      return i;
    }
  }
  return container.blocks.length;
};

const getNearestContainerId = (event) => {
  const containerElements = document.querySelectorAll('.container');
  let nearestContainer = null;
  let minDistance = Infinity;

  containerElements.forEach((container) => {
    const rect = container.getBoundingClientRect();
    const distance = Math.abs(event.clientY - (rect.top + rect.height / 2));
    if (distance < minDistance) {
      minDistance = distance;
      nearestContainer = container;
    }
  });

  return nearestContainer ? nearestContainer.getAttribute('data-container-id') : null;
};

const addContainer = (type, position = containers.value.length) => {
  const blocksPerRow = getContainerBlocksPerRow(type);
  const newContainer = {
    id: Date.now().toString(),
    type,
    blocks: Array(blocksPerRow).fill().map((_, index) => ({
      isPlaceholder: true,
      id: `placeholder-${Date.now()}-${index}`,
      placeholderId: `placeholder-${Date.now()}-${index}`,
      index
    })),
    attributes: {
      backgroundColor: '#ffffff',
      padding: '0px',
      margin: '0px',
      borderRadius: '0px',
      hideOnMobile: false,
    },
  };
  containers.value.splice(position, 0, newContainer);
};

const addBlock = (block, containerId, index) => {
  const container = containers.value.find(c => c.id === containerId);
  if (!container) return;

  const newBlock = {
    ...JSON.parse(JSON.stringify(block)),
    props: JSON.parse(JSON.stringify(block.defaultProps)),
    id: block.id || Date.now().toString(),
  };

 
  // If the index is beyond the current blocks, add it to the end
  if (index >= container.blocks.length) {
    index = container.blocks.length - 1;
  }

  // Replace the placeholder at the specified index with the new block
  container.blocks[index] = newBlock;
};

const hasPlaceholders = (container) => {
  return container.blocks.some(block => block.isPlaceholder);
};

const addNewRow = (container) => {
  const blocksPerRow = getContainerBlocksPerRow(container.type);
  const newRowPlaceholders = Array(blocksPerRow).fill().map((_, index) => ({
    isPlaceholder: true,
    id: `placeholder-${Date.now()}-${container.blocks.length + index}`,
    placeholderId: `placeholder-${Date.now()}-${container.blocks.length + index}`,
    index: container.blocks.length + index
  }));
  container.blocks.push(...newRowPlaceholders);
};

const selectBlock = (id) => {
  selectedBlock.value = id;
  const block = getSelectedBlock();
  if (block) {
    selectedBlockData.value = {
      id: block.id,
      name: block.name,
      props: block.props,
      options: block.options
    };
  }
  isEditing.value = true;
};

const editBlock = (id) => {
  selectedBlock.value = id;
  isEditing.value = true;
};

const deleteBlock = (containerId, blockId) => {
  const container = containers.value.find(c => c.id === containerId);
  if (container) {
    const index = container.blocks.findIndex(b => b.id === blockId);
    if (index !== -1) {
      container.blocks[index] = {
        isPlaceholder: true,
        id: `placeholder-${Date.now()}-${index}`,
        placeholderId: `placeholder-${Date.now()}-${index}`,
        index
      };
    }
  }
  if (selectedBlock.value === blockId) {
    selectedBlock.value = null;
    isEditing.value = false;
  }
};

const duplicateBlock = (containerId, blockId) => {
  const container = containers.value.find(c => c.id === containerId);
  if (container) {
    const originalBlock = container.blocks.find(b => b.id === blockId);
    if (originalBlock) {
      const newBlock = JSON.parse(JSON.stringify(originalBlock));
      newBlock.id = Date.now().toString();
      container.blocks.push(newBlock);
    }
  }
};

const updateBlockProps = (newProps) => {
  if (selectedBlock.value !== null) {
    const block = getSelectedBlock();
    if (block) {
      block.props = { ...newProps };
      selectedBlockData.value = {
        ...selectedBlockData.value,
        props: newProps
      };
    }
  }
};

const finishEditing = () => {
  isEditing.value = false;
};

const getBlockComponent = (block) => {
  const foundBlock = availableBlocks.value.find((b) => b.name === block.name);
  return foundBlock ? foundBlock.component : null;
};

const isMobilePreview = ref(false);
const isFullScreen = ref(false);

const toggleMobilePreview = () => {
  isMobilePreview.value = !isMobilePreview.value;
};

const toggleFullScreen = () => {
  if (!document.fullscreenElement) {
    document.documentElement.requestFullscreen();
    isFullScreen.value = true;
  } else {
    if (document.exitFullscreen) {
      document.exitFullscreen();
      isFullScreen.value = false;
    }
  }
};

const containerClass = computed(() => ({
  "w-full min-w-[450px]": isMobilePreview.value,
  "w-full md:w-[calc(100%-320px)]": !isMobilePreview.value,
}));

const getBlockWrapperClass = (containerType) => {
  const containerConfig = containerTypes.find(ct => ct.type === containerType);
  if (!containerConfig) return 'w-full p-2 ';

  const widthClass = {
    1: 'w-full',
    2: 'w-1/2',
    3: 'w-1/3',
    4: 'w-1/4',
  }[containerConfig.blocksPerRow] || 'w-full';

  return `${widthClass}`;
};

const getContainerName = (type) => {
  const containerConfig = containerTypes.find(ct => ct.type === type);
  return containerConfig ? containerConfig.name : 'Container';
};

const getSelectedBlock = () => {
  for (const container of containers.value) {
    const block = container.blocks.find(b => b.id === selectedBlock.value);
    if (block) {
      return block;
    }
  }
  return null;
};

const saveBlocks = () => {
  const blocksData = containers.value.map(container => ({
    id: container.id,
    type: container.type,
    blocks: container.blocks.map(block => ({
      id: block.id,
      name: block.name,
      reference: block.reference,
      props: block.props,
      options: block.options,
    })),
  }));

  emit('save', JSON.stringify(blocksData));
};

const getContainerBlocksPerRow = (type) => {
  const containerConfig = containerTypes.find(ct => ct.type === type);
  return containerConfig ? containerConfig.blocksPerRow : 1;
};

const updateContainerAttributes = (containerId, newAttributes) => {
  const container = containers.value.find(c => c.id === containerId);
  if (container) {
    container.attributes = { ...newAttributes };
  }
};

const selectContainer = (containerId) => {
  selectedContainer.value = containers.value.find(c => c.id === containerId);
  selectedBlock.value = null;
  isEditing.value = false;
};
</script>

<style scoped>
.container {
  transition: all 0.3s ease;
}
.container:hover {
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
}
.block-drag-handle,
.container-drag-handle {
  opacity: 0;
  transition: opacity 0.2s ease;
}
.container:hover .container-drag-handle,
.group:hover .block-drag-handle {
  opacity: 1;
}
.container .flex-wrap {
  display: flex;
  flex-wrap: wrap;
  min-height: 100px; 
}

.block-placeholder {
  transition: all 0.3s ease;
}

.block-placeholder:hover {
  background-color: rgba(59, 130, 246, 0.1);
}
</style>

