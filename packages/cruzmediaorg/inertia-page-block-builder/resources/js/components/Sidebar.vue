<template>
  <div class="w-full h-[90vh] md:w-80 border-t md:border-l bg-white border-gray-200 overflow-y-auto md:absolute md:right-0 relative">
    <div class="p-6">
      <!-- Container attributes editing -->
      <div v-if="selectedContainer && !selectedBlock">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Container</h3>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Background Color</label>
            <input type="color" v-model="selectedContainer.attributes.backgroundColor" class="mt-1 block w-full" @change="updateContainerAttributes">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Padding</label>
            <input type="text" v-model="selectedContainer.attributes.padding" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" @change="updateContainerAttributes">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Margin</label>
            <input type="text" v-model="selectedContainer.attributes.margin" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" @change="updateContainerAttributes">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Border Radius</label>
            <input type="text" v-model="selectedContainer.attributes.borderRadius" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" @change="updateContainerAttributes">
          </div>
          <div>
            <label class="flex items-center">
              <input type="checkbox" v-model="selectedContainer.attributes.hideOnMobile" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" @change="updateContainerAttributes">
              <span class="ml-2 text-sm text-gray-600">Hide on Mobile</span>
            </label>
          </div>
        </div>
      </div>

      <!-- Block editing -->
      <div v-else-if="selectedBlock !== null && isEditing">
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

      <!-- Add Containers and Blocks -->
      <div v-else>
        <h3 class="text-lg font-medium text-gray-900 mb-4">Add Elements</h3>
        
        <!-- Container Types -->
        <div class="mb-6">
          <h4 class="font-medium text-gray-700 mb-2">Containers</h4>
          <div class="grid grid-cols-2 gap-2">
            <div v-for="containerType in containerTypes" :key="containerType.type" 
                 class="border rounded p-2 cursor-move"
                 draggable="true"
                 @dragstart="startDragContainer(containerType, $event)">
              {{ containerType.name }}
            </div>
          </div>
        </div>

        <!-- Available Blocks -->
        <div class="mb-6">
          <h4 class="font-medium text-gray-700 mb-2">Blocks</h4>
          <input v-model="searchQuery" type="text" placeholder="Search blocks..." class="w-full mb-2 p-2 border rounded">
          <div class="grid grid-cols-2 gap-2">
            <div v-for="block in filteredBlocks" :key="block.reference" 
                 class="border rounded p-2 cursor-move"
                 draggable="true"
                 @dragstart="startDragBlock(block, $event)">
              {{ block.name }}
            </div>
          </div>
        </div>

        <!-- Layer view -->
        <div>
          <h4 class="font-medium text-gray-700 mb-2">Layers</h4>
          <div v-for="container in containers" :key="container.id" class="mb-4">
            <h5 class="font-medium text-gray-600 mb-1">{{ getContainerName(container.type) }}</h5>
            <ul class="space-y-1">
              <li v-for="block in container.blocks" :key="block.id" class="flex items-center">
                <button 
                  @click="selectBlock(block.id)" 
                  class="text-left w-full py-1 px-2 text-sm hover:bg-gray-100 rounded"
                  :class="{ 'bg-blue-100': selectedBlock === block.id }"
                >
                  {{ block.name || 'Empty' }}
                </button>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import DynamicOptions from './DynamicOptions.vue';

const props = defineProps({
  containers: {
    type: Array,
    required: true,
  },
  selectedBlock: {
    type: Object,
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
  selectedContainer: {
    type: Object,
    default: null,
  },
});

const emit = defineEmits(['add-container', 'add-block', 'update-block-props', 'finish-editing', 'save-blocks', 'update-container-attributes']);

const searchQuery = ref('');
const selectedBlockProps = ref({});

watch(() => props.selectedBlock, (newValue) => {
  if (newValue) {
    selectedBlockProps.value = { ...newValue.props };
  } else {
    selectedBlockProps.value = {};
  }
}, { immediate: true, deep: true });

const filteredBlocks = computed(() => {
  return props.availableBlocks.filter((block) =>
    block.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

const getSelectedBlockName = () => {
  return props.selectedBlock ? props.selectedBlock.name : '';
};

const getSelectedBlockOptions = () => {
  return props.selectedBlock ? props.selectedBlock.options : [];
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

const updateContainerAttributes = () => {
  if (props.selectedContainer) {
    emit('update-container-attributes', props.selectedContainer.id, props.selectedContainer.attributes);
  }
};

const getContainerName = (type) => {
  const containerConfig = props.containerTypes.find(ct => ct.type === type);
  return containerConfig ? containerConfig.name : 'Container';
};

const selectBlock = (blockId) => {
  emit('select-block', blockId);
};
</script>