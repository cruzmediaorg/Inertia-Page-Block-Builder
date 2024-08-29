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
              <svg
                v-if="isMobilePreview"
                class="w-6 h-6 text-gray-500"
                aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                fill="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  fill-rule="evenodd"
                  d="M5 4a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V4Zm12 12V5H7v11h10Zm-5 1a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H12Z"
                  clip-rule="evenodd"
                />
              </svg>
              <svg
                v-else
                class="w-6 h-6 text-gray-600"
                aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                fill="none"
                viewBox="0 0 24 24"
              >
                <path
                  stroke="currentColor"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 15v5m-3 0h6M4 11h16M5 15h14a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1Z"
                />
              </svg>
            </button>
            <button @click="toggleFullScreen" class="bg-white p-2 border rounded-md">
              <svg
                class="w-6 h-6 text-gray-600"
                aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                fill="none"
                viewBox="0 0 24 24"
              >
                <path
                  stroke="currentColor"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 4H4m0 0v4m0-4 5 5m7-5h4m0 0v4m0-4-5 5M8 20H4m0 0v-4m0 4 5-5m7 5h4m0 0v-4m0 4-5-5"
                />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="flex-1 overflow-hidden border-t">
      <div class="flex flex-col md:flex-row h-full">
        <div
          :class="[containerClass, 'w-full md:w-[calc(100%-320px)] overflow-y-auto']"
          @dragover.prevent
          @drop="handleDrop"
        >
          <draggable
            v-if="blocks.length > 0"
            v-model="blocks"
            :item-key="(block) => block.id"
            handle=".drag-handle"
            @start="dragStart"
            @end="dragEnd"
          >
            <template #item="{ element }">
              <div
                class="group"
                :class="{
                  'ring-2 ring-blue-500': selectedBlock === blocks.indexOf(element),
                }"
                @click="selectBlock(blocks.indexOf(element))"
              >
                <component :is="getBlockComponent(element)" v-bind="element.props" />
                <BlockActions
                  class="block-actions"
                  @edit="editBlock(blocks.indexOf(element))"
                  @delete="deleteBlock(blocks.indexOf(element))"
                  @duplicate="duplicateBlock(blocks.indexOf(element))"
                />
              </div>
            </template>
          </draggable>
          <div
            v-else
            class="flex justify-center items-center p-5 flex-col h-full min-w-80 bg-white"
          >
            <p class="text-gray-500 text-center font-bold">No blocks added</p>
            <p class="text-gray-500 text-center">Drag and drop a block to get started</p>
          </div>
        </div>
        <div class="w-full h-[90vh] md:w-80 border-t md:border-l bg-white border-gray-200 overflow-y-auto md:absolute md:right-0  relative">
          <div class="p-6">
            <div
              v-if="selectedBlock !== null && isEditing"
              class="space-y-4 p-4 bg-white rounded-lg divide"
            >
              <h3 class="text-lg font-medium text-gray-900 mb-4">
                Edit {{ blocks[selectedBlock].name }}
              </h3>
              <DynamicOptions
                v-model="blocks[selectedBlock].props"
                :options="blocks[selectedBlock].options"
                @update:modelValue="updateBlockProps"
              />
              <button
                @click="finishEditing"
                class="mt-4 w-full bg-black text-white px-4 py-2 rounded hover:bg-gray-900"
              >
                Done
              </button>
            </div>
            <div v-else>
              <h3 class="text-lg font-medium text-gray-900 mb-4">Blocks</h3>
              <div class="mb-4">
                <input
                  type="text"
                  placeholder="Search blocks"
                  v-model="searchQuery"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                />
              </div>
              <div class="space-y-2">
                <button
                  v-for="block in filteredBlocks"
                  :key="block.name"
                  class="w-full flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  @click="addBlock(block)"
                  @dragstart="startDrag(block)"
                  draggable="true"
                >
                  <i :class="[block.icon, 'mr-3 text-gray-400']"></i>
                  {{ block.name }}
                </button>
              </div>
            </div>
          </div>
          <div class="w-full p-4">
            <button @click="saveBlocks" class="w-full bg-black text-white px-4 py-2 rounded hover:bg-gray-900">
              Save
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, computed, defineAsyncComponent, watch } from "vue";
import draggable from "vuedraggable";
import FallbackBlock from "./FallbackBlock.vue";
import BlockActions from "./BlockActions.vue";
import DynamicOptions from "./DynamicOptions.vue";
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

const emit =defineEmits(['save']);

const blocks = ref(props.data);
const selectedBlock = ref(null);
const isEditing = ref(false);
const searchQuery = ref("");
const isDragging = ref(false);

const draggedBlock = ref(null);

const startDrag = (block) => {
  draggedBlock.value = block;
};

const handleDrop = (event) => {
  if (draggedBlock.value) {
    addBlock(draggedBlock.value);
    draggedBlock.value = null;
  }
};

const availableBlocks = computed(() => {
  if (!Array.isArray(props.registeredBlocks)) {
    console.error("registeredBlocks is not an array:", props.registeredBlocks);
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
          console.error(`Failed to load component: ${block.render}`, error);
          fail();
        }
      },
    }),
    options: block.options,
    defaultProps: block.data,
    icon: "fas fa-cube",
  }));
});

const filteredBlocks = computed(() => {
  return availableBlocks.value.filter((block) =>
    block.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

const addBlock = (block) => {
  blocks.value.push({
    ...JSON.parse(JSON.stringify(block)), // Deep clone the entire block
    props: JSON.parse(JSON.stringify(block.defaultProps)), // Deep clone defaultProps
    id: Date.now(), // Ensure each block has a unique id
  });
  selectedBlock.value = blocks.value.length - 1;
};

const selectBlock = (index) => {
  selectedBlock.value = index;
  editBlock(index);
};

const editBlock = (index) => {
  selectedBlock.value = index;
  isEditing.value = true;
};

const deleteBlock = (index) => {
  blocks.value.splice(index, 1);
  if (selectedBlock.value === index) {
    selectedBlock.value = null;
    isEditing.value = false;
  }
};

const duplicateBlock = (index) => {
  const originalBlock = blocks.value[index];
  blocks.value.push({
    ...JSON.parse(JSON.stringify(originalBlock)), // Deep clone the entire block
    id: Date.now(), // Ensure the duplicated block has a new unique id
  });
};

const updateBlockProps = (newProps) => {
  if (selectedBlock.value !== null) {
    blocks.value[selectedBlock.value].props = { ...newProps };
  }
};

const finishEditing = () => {
  isEditing.value = false;
};

const getBlockComponent = (block) => {
  const foundBlock = availableBlocks.value.find((b) => b.name === block.name);
  return foundBlock ? foundBlock.component : null;
};

const dragStart = () => {
  isDragging.value = true;
};

const dragEnd = () => {
  isDragging.value = false;
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


const saveBlocks = () => {
  const blocksData = blocks.value.map((block) => ({
    id: block.id,
    name: block.name,
    reference: block.reference,
    props: block.props,
    options: block.options,
  }));

  emit('save', JSON.stringify(blocksData));
};
</script>

<style scoped></style>
