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
          @drop="handleDrop"
          @click="selectContainer(null)"
        >
          <draggable
            v-model="containers"
            :item-key="(container) => container.id"
            handle=".container-drag-handle"
            @start="dragStart"
            @end="dragEnd"
          >
          <template #item="{ element: container }">
              <div 
                class="container border border-dashed border-gray-300 relative" 
                @dragover.prevent 
                @drop.stop="handleDrop($event, container.id)" 
                :data-container-id="container.id"
                :style="{
                  backgroundColor: container.attributes.backgroundColor,
                  padding: `${container.attributes.paddingTop} ${container.attributes.paddingRight} ${container.attributes.paddingBottom} ${container.attributes.paddingLeft}`,
                  margin: `${container.attributes.marginTop} ${container.attributes.marginRight} ${container.attributes.marginBottom} ${container.attributes.marginLeft}`,
                  borderRadius: container.attributes.borderRadius,
                  display: container.attributes.hideOnMobile && isMobilePreview ? 'none' : 'block',
                }"
              >
                <!-- Add this button for container selection -->
                <button 
                  @click.stop="selectContainer(container.id)"
                  class="absolute top-2 left-2 z-50 bg-white p-1 rounded-full shadow-md hover:bg-gray-100 transition-colors duration-200"
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
                  <draggable
                    :list="container.blocks"
                    :item-key="(block) => block.id || block.placeholderId"
                    handle=".block-drag-handle"
                    group="blocks"
                    @start="dragStart"
                    @end="dragEnd"
                    class="flex w-full">
                    <template #item="{ element: block, index }">
                      <div :data-block-id="block.id" :class="[getBlockWrapperClass(container.type, container.attributes), 'flex-grow']">
                        <template v-if="block.isPlaceholder">
                          <div 
                            class="block-placeholder min-h-24 h-full border-2 border-dashed border-gray-300 flex items-center justify-center"
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
          @deselect-container="deselectContainer"
          @select-block="selectBlock"
          @select-container="selectContainerById"
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
import useContainerManagement from "../composables/useContainerManagement";
import useDragAndDrop from "../composables/useDragAndDrop";
import "../../css/style.css";
import "./PageBuilder.css";

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

const {
  containers,
  selectedContainer,
  addContainer,
  updateContainerAttributes,
  selectContainer,
  deselectContainer,
  addNewRow,
  hasPlaceholders,
  getContainerBlocksPerRow,
  containerTypes,
} = useContainerManagement(props.data);

const selectedBlock = ref(null);
const selectedBlockData = ref(null);
const isEditing = ref(false);

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

const selectContainerById = (containerId) => {
  selectContainer(containerId);
  selectedBlock.value = null;
  isEditing.value = false;
};

const getBlockWrapperClass = (containerType, containerAttributes) => {
  const containerConfig = containerTypes.find(ct => ct.type === containerType);
  if (!containerConfig) return 'w-full';

  switch (containerConfig.blocksPerRow) {
    case 1: return 'w-full';
    case 2: return 'w-1/2';
    case 3: return 'w-1/3';
    case 4: return 'w-1/4';
    default: return 'w-full';
  }
};

const { isDragging, draggedItem, dragStart, dragEnd, handleDrop } = useDragAndDrop(
  containers,
  addContainer,
  addBlock
);
</script>

