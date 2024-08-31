<template>
  <div
    class="flex flex-col h-screen bg-gray-100"
    :class="{ 'mobile-preview': isMobilePreview }"
  >
    <div class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 ">
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
    <div class="flex-1 overflow-hidden border-t" >
      <div class="flex flex-col md:flex-row h-full ">
        <div
          :class="[containerClass, 'w-full md:w-[calc(100%-320px)] overflow-y-auto p-4']"
          @dragover.prevent
          @drop="handleDrop($event)"
          @click="selectContainer(null)"
        > 
          <draggable
            v-model="containers"
            :item-key="(container) => container.id"
            handle=".container-drag-handle"
            @start="dragStart($event, 'container')"
            @end="dragEnd"
            group="containers"
          >
            <template #item="{ element: container }">
              <div 
                class="container border border-dashed border-gray-300 relative" 
                @dragover.prevent 
                @drop.stop="handleDrop($event, container.id)" 
                :data-container-id="container.id"
                :style="getContainerStyle(container)"
              >
                <button 
                  @click.stop="selectContainerById(container.id)"
                  class="container-drag-handle absolute top-2 left-2 z-50 bg-white p-1 rounded-full shadow-md hover:bg-gray-100 transition-colors duration-200"
                >
                  <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                  </svg>
                </button>
                <div 
                  class="flex flex-wrap"
                  :style="{
                    gap: container.attributes.blockGap,
                  }"
                >
                  <div 
                    v-for="(column, columnIndex) in container.columns" 
                    :key="columnIndex"
                    :class="getColumnClass(container.type)"
                    @dragover.prevent
                    @drop.stop="handleDrop($event, container.id, columnIndex)"
                  >
                    <draggable
                      :list="column.blocks"
                      :item-key="(block) => block.id"
                      handle=".block-drag-handle"
                      group="blocks"
                      @start="(evt) => dragStart(evt, 'block')"
                      @end="dragEnd"
                      @change="(e) => moveBlock(e.moved.element.id, container.id, columnIndex, e.moved.newIndex)"
                      class="flex flex-col w-full"
                    >
                      <template #item="{ element: block, index }">
                        <div :data-block-id="block.id" class="w-full">
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
                              @delete="deleteBlock(container.id, columnIndex, block.id)"
                              @duplicate="duplicateBlock(container.id, columnIndex, block.id)"
                            />
                          </div>
                        </div>
                      </template>
                    </draggable>
                    <div 
                      v-if="column.blocks.length === 0"
                      class="block-placeholder min-h-24 h-full border-2 border-dashed border-gray-300 flex items-center justify-center"
                      @dragover.prevent
                      @drop.stop="handleDrop($event, container.id, columnIndex)"
                    >
                      <p class="text-gray-400 text-sm">Drag a block here</p>
                    </div>
                  </div>
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
          @deselect-container="deselectContainer"
          @select-block="selectBlock"
          @select-container="selectContainerById"
        />
      </div>
    </div>
  </div>
 
  <button class="bg-black text-white px-4 py-2 rounded-full absolute right-4 bottom-0" @click="saveBlocks">Save Blocks</button>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</template>


<script setup>
import { ref, computed, defineAsyncComponent, watch, reactive, markRaw, onMounted } from "vue";
import draggable from "vuedraggable";
import FallbackBlock from "./FallbackBlock.vue";
import BlockActions from "./BlockActions.vue";
import Sidebar from "./Sidebar.vue";
import useContainerManagement, { getContainerColumnsCount } from '../composables/useContainerManagement';
import useDragAndDrop from "../composables/useDragAndDrop";
import "../../css/style.css";
import "./PageBuilder.css";

const props = defineProps({
  registeredBlocks: {
    type: Array,
    required: true,
  },
  page: {
    type: Object,
    required: true,
  },
});

const emit = defineEmits(['save']);
const blocksData = ref([]);

const {
  containers,
  selectedContainer,
  addContainer,
  updateContainerAttributes,
  selectContainer,
  deselectContainer,
  containerTypes,
  loadContainers,
} = useContainerManagement([], props.registeredBlocks);

const selectedBlock = ref(null);
const selectedBlockData = ref(null);
const isEditing = ref(false);
const isMobilePreview = ref(false);
const isFullScreen = ref(false);

const availableBlocks = computed(() => {
  if (!Array.isArray(props.registeredBlocks)) {
    return [];
  }

  return props.registeredBlocks.map((block) => ({
    name: block.name,
    reference: block.reference,
    component: markRaw(defineAsyncComponent({
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
    })),
    options: block.options,
    defaultProps: block.data,
    icon: block.icon,
  }));
});

const addBlock = (block, containerId, columnIndex) => {
  const container = containers.value.find(c => c.id === containerId);
  if (!container || !container.columns[columnIndex]) return;

  const newBlock = {
    ...JSON.parse(JSON.stringify(block)),
    props: JSON.parse(JSON.stringify(block.defaultProps)),
    id: block.id || Date.now().toString(),
  };

  container.columns[columnIndex].blocks.push(newBlock);
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
  selectedContainer.value = null;
  isEditing.value = true;
};

const editBlock = (id) => {
  selectedBlock.value = id;
  isEditing.value = true;
};

const deleteBlock = (containerId, columnIndex, blockId) => {
  const container = containers.value.find(c => c.id === containerId);
  if (container && container.columns[columnIndex]) {
    const blockIndex = container.columns[columnIndex].blocks.findIndex(b => b.id === blockId);
    if (blockIndex !== -1) {
      container.columns[columnIndex].blocks.splice(blockIndex, 1);
    }
  }
  if (selectedBlock.value === blockId) {
    selectedBlock.value = null;
    isEditing.value = false;
  }
};

const duplicateBlock = (containerId, columnIndex, blockId) => {
  const container = containers.value.find(c => c.id === containerId);
  if (container && container.columns[columnIndex]) {
    const originalBlock = container.columns[columnIndex].blocks.find(b => b.id === blockId);
    if (originalBlock) {
      const newBlock = JSON.parse(JSON.stringify(originalBlock));
      newBlock.id = Date.now().toString();
      container.columns[columnIndex].blocks.push(newBlock);
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

const getSelectedBlock = () => {
  for (const container of containers.value) {
    for (const column of container.columns) {
      const block = column.blocks.find(b => b.id === selectedBlock.value);
      if (block) {
        return block;
      }
    }
  }
  return null;
};

const saveBlocks = () => {
  blocksData.value = containers.value.map(container => ({
    id: container.id,
    type: container.type,
    attributes: container.attributes,
    columns: container.columns.map(column => ({
      blocks: column.blocks.map(block => ({
        id: block.id,
        name: block.name,
        reference: block.reference,
        props: block.props,
      })),
    })),
  }));

  emit('save', JSON.stringify(blocksData.value));
};

const selectContainerById = (containerId) => {
  selectContainer(containerId);
  selectedBlock.value = null;
  isEditing.value = true;
};

const moveBlock = (blockId, targetContainerId, targetColumnIndex, newIndex) => {
  let sourceContainer, sourceColumnIndex, sourceIndex, blockToMove;

  // Find the block to move
  for (const container of containers.value) {
    for (let colIndex = 0; colIndex < container.columns.length; colIndex++) {
      const blockIndex = container.columns[colIndex].blocks.findIndex(b => b.id === blockId);
      if (blockIndex !== -1) {
        sourceContainer = container;
        sourceColumnIndex = colIndex;
        sourceIndex = blockIndex;
        blockToMove = container.columns[colIndex].blocks[blockIndex];
        break;
      }
    }
    if (sourceContainer) break;
  }

  if (!sourceContainer || !blockToMove) return;

  // Remove the block from the source column
  sourceContainer.columns[sourceColumnIndex].blocks.splice(sourceIndex, 1);

  // Add the block to the target column
  const targetContainer = containers.value.find(c => c.id === targetContainerId);
  if (targetContainer && targetContainer.columns[targetColumnIndex]) {
    if (newIndex === undefined) {
      targetContainer.columns[targetColumnIndex].blocks.push(blockToMove);
    } else {
      targetContainer.columns[targetColumnIndex].blocks.splice(newIndex, 0, blockToMove);
    }
  }
};

const getContainerStyle = (container) => ({
  backgroundColor: container.attributes.backgroundColor,
  padding: `${container.attributes.paddingTop} ${container.attributes.paddingRight} ${container.attributes.paddingBottom} ${container.attributes.paddingLeft}`,
  margin: `${container.attributes.marginTop} ${container.attributes.marginRight} ${container.attributes.marginBottom} ${container.attributes.marginLeft}`,
  borderRadius: container.attributes.borderRadius,
  display: container.attributes.hideOnMobile && isMobilePreview.value ? 'none' : 'block',
});

const getColumnClass = (containerType) => {
  const columnsCount = getContainerColumnsCount(containerType);
  switch (columnsCount) {
    case 1: return 'w-full';
    case 2: return 'w-1/2';
    case 3: return 'w-1/3';
    case 4: return 'w-1/4';
    default: return 'w-full';
  }
};

const { isDragging, draggedItem, dragStart, dragEnd, handleDrop } = useDragAndDrop(
  reactive({ containers }),
  addContainer,
  addBlock,
  moveBlock
);

// Add this new function to load the page content
const loadPageContent = () => {
  if (props.page && props.page.content) {
    let pageContent;
    try {
      pageContent = typeof props.page.content === 'string' ? JSON.parse(props.page.content) : props.page.content;
    } catch (error) {
      console.error('Failed to parse page content:', error);
      pageContent = [];
    }
    loadContainers(pageContent);
    blocksData.value = pageContent;
  }
};

// Call this function when the component is mounted
onMounted(() => {
  loadPageContent();
});
</script>

