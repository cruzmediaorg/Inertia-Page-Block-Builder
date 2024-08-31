<template>
  <div class="w-full h-[90vh] md:w-80 border-t md:border-l bg-white border-gray-200 overflow-y-auto md:absolute md:right-0 relative">
    <div class="p-4">
      <div class="mb-4">
        <div 
          v-for="tab in ['Structures', 'Blocks']" 
          :key="tab" 
          class="border-b"
        >
          <button 
            @click="toggleAccordion(tab)"
            class="w-full p-2 text-left flex justify-between items-center hover:bg-gray-50"
          >
            <span>{{ tab }}</span>
            <svg 
              class="w-5 h-5 transition-transform" 
              :class="{ 'rotate-180': activeContentTab === tab.toLowerCase() }"
              fill="currentColor" 
              viewBox="0 0 20 20" 
              xmlns="http://www.w3.org/2000/svg"
            >
              <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
          <div v-show="activeContentTab === tab.toLowerCase()" class="p-2">
            <!-- Content for Structures -->
            <div v-if="tab === 'Structures'">
              <div class="mb-6">
                <h4 class="font-medium text-gray-700 mb-2">Containers</h4>
                <div class="grid grid-cols-1 gap-2">
                  <div v-for="containerType in containerTypes" :key="containerType.type" 
                       class=""
                       draggable="true"
                       @dragstart="startDragContainer(containerType, $event)">
                    <ContainerPreview :type="containerType.type" />
                  </div>
                </div>
              </div>
            </div>
            <!-- Content for Blocks -->
            <div v-else>
              <div class="mb-6">
                <h4 class="font-medium text-gray-700 mb-2">Blocks</h4>
                <input v-model="searchQuery" type="text" placeholder="Search blocks..." class="w-full mb-2 p-2 border rounded-md">
                <div class="grid grid-cols-2 gap-2">
                  <div v-for="block in filteredBlocks" :key="block.reference" 
                       class="border rounded-md p-2 cursor-move hover:bg-gray-50 transition-colors duration-200"
                       draggable="true"
                       @dragstart="startDragBlock(block, $event)">
                    {{ block.name }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Container editing -->
      <div v-if="selectedContainer">
        <div v-if="activeTab === 'LAYOUT'">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Container Layout</h3>
          <div class="space-y-6">
            <!-- Background Color -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Background Color</label>
              <div class="flex items-center">
                <input type="color" v-model="selectedContainer.attributes.backgroundColor" class="w-8 h-8 rounded-full overflow-hidden border-2 border-gray-300" @change="updateContainerAttributes">
                <input type="text" v-model="selectedContainer.attributes.backgroundColor" class="ml-2 flex-grow px-2 py-1 border rounded-md" @change="updateContainerAttributes">
              </div>
            </div>

            <!-- Padding -->
            <SpacingInput
              label="Padding"
              v-model="containerPadding"
              @update:modelValue="updateContainerPadding"
            />

            <!-- Margin -->
            <SpacingInput
              label="Margin"
              v-model="containerMargin"
              @update:modelValue="updateContainerMargin"
            />

            <!-- Border Radius -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Border Radius</label>
              <NumberStepper
                v-model="selectedContainer.attributes.borderRadius"
                @update:modelValue="updateContainerAttributes"
                :step="1"
              />
            </div>

            <!-- Hide on Mobile -->
            <div>
              <label class="flex items-center">
                <input type="checkbox" v-model="selectedContainer.attributes.hideOnMobile" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" @change="updateContainerAttributes">
                <span class="ml-2 text-sm text-gray-600">Hide on Mobile</span>
              </label>
            </div>

            <!-- Block Gap -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Block Gap</label>
              <NumberStepper
                v-model="selectedContainer.attributes.blockGap"
                @update:modelValue="updateContainerAttributes"
                :step="1"
                :unit="'px'"
              />
            </div>
            <!-- Flex Direction -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Flex Direction</label>
              <select v-model="selectedContainer.attributes.flexDirectionDesktop" @change="updateContainerAttributes" class="w-full p-2 border rounded-md">
                <option value="row">Row</option>
                <option value="column">Column</option>
              </select>
            </div>

            <!-- Flex Direction Mobile -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Flex Direction Mobile</label>
              <select v-model="selectedContainer.attributes.flexDirectionMobile" @change="updateContainerAttributes" class="w-full p-2 border rounded-md">
                <option value="column">Column</option>
                <option value="row">Row</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Block editing -->
      <div v-else-if="selectedBlock">
        <div v-if="activeTab === 'BLOCK'">
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
            class="mt-4 w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors duration-200"
          >
            Done
          </button>
        </div>

        <div v-else-if="activeTab === 'CONTAINER'">
          <button
            @click="selectContainerOfBlock"
            class="mb-4 w-full bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 transition-colors duration-200"
          >
            Edit Container
          </button>
          <!-- You can add a preview of container settings here if needed -->
        </div>
      </div>

      <!-- Layer view -->
      <div v-if="!selectedContainer && !selectedBlock">
        <h4 class="font-medium text-gray-700 mb-2">Layers</h4>
        <div v-for="container in containers" :key="container.id" class="mb-4">
          <div @click="selectContainer(container.id)" class="cursor-pointer">
            <ContainerPreview :type="container.type" />
          </div>
          <ul class="space-y-1 mt-2">
            <li v-for="(column, columnIndex) in container.columns" :key="columnIndex">
              <div v-for="block in column.blocks" :key="block.id" class="flex items-center">
                <button 
                  @click="selectBlock(block.id)" 
                  class="text-left w-full py-1 px-2 text-sm hover:bg-gray-100 rounded-md transition-colors duration-200"
                  :class="{ 'bg-indigo-100': selectedBlock === block.id }"
                >
                  {{ block.name || 'Empty' }}
                </button>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import DynamicOptions from './DynamicOptions.vue';
import SpacingInput from './SpacingInput.vue';
import NumberStepper from './NumberStepper.vue';
import ContainerPreview from './ContainerPreview.vue';
import useContainerAttributes from "../composables/useContainerAttributes";

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

const emit = defineEmits(['add-container', 'add-block', 'update-block-props', 'finish-editing', 'save-blocks', 'update-container-attributes', 'select-block', 'deselect-container', 'select-container']);

const searchQuery = ref('');
const selectedBlockProps = ref({});
const activeTab = ref('BLOCKS');
const activeContentTab = ref('structures');

const { defaultAttributes } = useContainerAttributes();

const tabs = computed(() => {
  if (props.selectedContainer) {
    return ['BLOCKS', 'LAYOUT'];
  } else if (props.selectedBlock) {
    return ['BLOCK', 'CONTAINER'];
  }
  return [];
});

watch(() => props.selectedBlock, (newValue) => {
  if (newValue) {
    selectedBlockProps.value = { ...newValue.props };
    activeTab.value = 'BLOCK';
  } else {
    selectedBlockProps.value = {};
  }
}, { immediate: true, deep: true });

watch(() => props.selectedContainer, (newValue) => {
  if (newValue) {
    activeTab.value = 'LAYOUT';
  }
}, { immediate: true });

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
  const blocksData = props.containers.map(container => ({
    id: container.id,
    type: container.type,
    columns: container.columns.map(column => ({
      blocks: column.blocks.map(block => ({
        id: block.id,
        name: block.name,
        reference: block.reference,
        props: block.props,
        options: block.options,
      })),
    })),
    attributes: container.attributes,
  }));

  emit('save-blocks', JSON.stringify(blocksData));
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

const selectContainerOfBlock = () => {
  if (props.selectedBlock) {
    const container = props.containers.find(c => c.columns.some(col => col.blocks.some(b => b.id === props.selectedBlock.id)));
    if (container) {
      emit('select-container', container.id);
    }
  }
};

const deselectContainer = () => {
  emit('deselect-container');
};

const containerPadding = computed(() => ({
  Top: props.selectedContainer?.attributes.paddingTop || defaultAttributes.paddingTop,
  Right: props.selectedContainer?.attributes.paddingRight || defaultAttributes.paddingRight,
  Bottom: props.selectedContainer?.attributes.paddingBottom || defaultAttributes.paddingBottom,
  Left: props.selectedContainer?.attributes.paddingLeft || defaultAttributes.paddingLeft,
}));

const containerMargin = computed(() => ({
  Top: props.selectedContainer?.attributes.marginTop || defaultAttributes.marginTop,
  Right: props.selectedContainer?.attributes.marginRight || defaultAttributes.marginRight,
  Bottom: props.selectedContainer?.attributes.marginBottom || defaultAttributes.marginBottom,
  Left: props.selectedContainer?.attributes.marginLeft || defaultAttributes.marginLeft,
}));

const updateContainerPadding = (newPadding) => {
  if (props.selectedContainer) {
    props.selectedContainer.attributes.paddingTop = newPadding.Top;
    props.selectedContainer.attributes.paddingRight = newPadding.Right;
    props.selectedContainer.attributes.paddingBottom = newPadding.Bottom;
    props.selectedContainer.attributes.paddingLeft = newPadding.Left;
    updateContainerAttributes();
  }
};

const updateContainerMargin = (newMargin) => {
  if (props.selectedContainer) {
    props.selectedContainer.attributes.marginTop = newMargin.Top;
    props.selectedContainer.attributes.marginRight = newMargin.Right;
    props.selectedContainer.attributes.marginBottom = newMargin.Bottom;
    props.selectedContainer.attributes.marginLeft = newMargin.Left;
    updateContainerAttributes();
  }
};

const selectContainer = (containerId) => {
  emit('select-container', containerId);
};

const toggleAccordion = (tab) => {
  activeContentTab.value = activeContentTab.value === tab.toLowerCase() ? '' : tab.toLowerCase();
};
</script>

<style scoped>
.sidebar {
  box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
}

input[type="color"] {
  -webkit-appearance: none;
  border: none;
  padding: 0;
  cursor: pointer;
}

input[type="color"]::-webkit-color-swatch-wrapper {
  padding: 0;
}

input[type="color"]::-webkit-color-swatch {
  border: none;
  border-radius: 50%;
}
</style>