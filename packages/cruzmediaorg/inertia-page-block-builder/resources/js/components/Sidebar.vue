<template>
  <div class="w-full h-[90vh] md:w-80 border-t md:border-l bg-white border-gray-200 overflow-y-auto md:absolute md:right-0 relative">
    <div class="p-6">
      <div
        v-if="selectedBlock !== null && isEditing"
        class="space-y-4 p-4 bg-white rounded-lg divide"
      >
        <h3 class="text-lg font-medium text-gray-900 mb-4">
          Edit {{ getSelectedBlockName() }}
        </h3>
        <DynamicOptions
          v-model="selectedBlockProps"
          :options="getSelectedBlockOptions()"
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
        <!-- Container types -->
        <h3 class="text-lg font-medium text-gray-900 mb-4">Containers</h3>
        <div class="space-y-2 mb-6">
          <div
            v-for="containerType in containerTypes"
            :key="containerType.type"
            class="w-full flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            draggable="true"
            @dragstart="startDragContainer(containerType, $event)"
          >
            {{ containerType.name }}
          </div>
        </div>
        <!-- Block types -->
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
          <div
            v-for="block in filteredBlocks"
            :key="block.name"
            class="w-full flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            draggable="true"
            @dragstart="startDragBlock(block, $event)"
          >
            <i :class="[block.icon, 'mr-3 text-gray-400']"></i>
            {{ block.name }}
          </div>
        </div>
      </div>
    </div>
    <div class="w-full p-4">
      <button @click="saveBlocks" class="w-full bg-black text-white px-4 py-2 rounded hover:bg-gray-900">
        Save
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import DynamicOptions from './DynamicOptions.vue';

const props = defineProps({
  containers: {
    type: Array,
    required: true,
  },
  selectedBlock: {
    type: String,
    default: null,
  },
  isEditing: {
    type: Boolean,
    required: true,
  },
  containerTypes: {
    type: Array,
    required: true,
  },
  availableBlocks: {
    type: Array,
    required: true,
  },
});

const emit = defineEmits(['add-container', 'add-block', 'update-block-props', 'finish-editing', 'save-blocks']);

const searchQuery = ref('');
const selectedBlockProps = ref({});

const filteredBlocks = computed(() => {
  return props.availableBlocks.filter((block) =>
    block.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

const getSelectedBlockName = () => {
  const block = getSelectedBlock();
  return block ? block.name : '';
};

const getSelectedBlockOptions = () => {
  const block = getSelectedBlock();
  return block ? block.options : [];
};

const getSelectedBlock = () => {
  for (const container of props.containers) {
    const block = container.blocks.find(b => b.id === props.selectedBlock);
    if (block) {
      return block;
    }
  }
  return null;
};

const addContainer = (type) => {
  emit('add-container', type);
};

const addBlock = (block) => {
  emit('add-block', block);
};

const updateBlockProps = (newProps) => {
  emit('update-block-props', newProps);
};

const finishEditing = () => {
  emit('finish-editing');
};

const saveBlocks = () => {
  emit('save-blocks');
};

const startDragContainer = (containerType, event) => {
  event.dataTransfer.setData('text/plain', JSON.stringify({ type: 'container', containerType }));
};

const startDragBlock = (block, event) => {
  event.dataTransfer.setData('text/plain', JSON.stringify({ type: 'block', block }));
};
</script>