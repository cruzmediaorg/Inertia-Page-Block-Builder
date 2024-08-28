<template>
  <div class="flex flex-col h-screen bg-gray-100">
    <div class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
          <h2 class="text-2xl font-bold text-gray-900">Editor</h2>
          <div class="flex space-x-4">
            <button class="text-gray-500 hover:text-gray-700">
              <i class="fas fa-mobile-alt"></i>
            </button>
            <button class="text-gray-500 hover:text-gray-700">
              <i class="fas fa-expand"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="flex-1 overflow-hidden">
      <div class="flex h-full">
        <div class="flex-1 overflow-y-auto p-6">
          <div class="">
         <div
  v-for="(block, index) in blocks"
  :key="index"
  class="group relative" 
  :class="{ 'ring-2 ring-blue-500': selectedBlock === index }"
  @click="selectBlock(index)"
>
              <component
                :is="getBlockComponent(block)"
                v-bind="block.props"
              />
              <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 ease-in-out">
  <button @click.stop="editBlock(index)" class="bg-blue-500 text-white px-2 py-1 rounded text-sm mr-2 hover:bg-blue-600">Edit</button>
  <button @click.stop="deleteBlock(index)" class="bg-red-500 text-white px-2 py-1 rounded text-sm hover:bg-red-600">Delete</button>
</div>
            </div>
          </div>
        </div>
        <div class="w-80 bg-white border-l border-gray-200 overflow-y-auto">
          <div class="p-6">
            <div v-if="selectedBlock !== null && isEditing">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Edit {{ blocks[selectedBlock].name }}</h3>
              <component
                :is="blocks[selectedBlock].options"
                v-model="blocks[selectedBlock].props"
                @update:modelValue="updateBlockProps"
              />
              <button @click="finishEditing" class="mt-4 w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Done</button>
            </div>
            <div v-else>
              <h3 class="text-lg font-medium text-gray-900 mb-4">Blocks</h3>
              <div class="mb-4">
                <input type="text" placeholder="Search blocks" v-model="searchQuery" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" />
              </div>
              <div class="space-y-2">
                <button
                  v-for="block in filteredBlocks"
                  :key="block.name"
                  class="w-full flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  @click="addBlock(block)"
                >
                  <i :class="[block.icon, 'mr-3 text-gray-400']"></i>
                  {{ block.name }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, computed, defineAsyncComponent } from 'vue'
import FallbackBlock from './FallbackBlock.vue'

const props = defineProps({
  registeredBlocks: {
    type: Array,
    required: true
  }
})

const blocks = ref([])
const selectedBlock = ref(null)
const isEditing = ref(false)
const searchQuery = ref('')

const availableBlocks = computed(() => {
  if (!Array.isArray(props.registeredBlocks)) {
    console.error('registeredBlocks is not an array:', props.registeredBlocks);
    return [];
  }
  
  return props.registeredBlocks.map(block => ({
    name: block.name,
    reference: block.reference,
    component: defineAsyncComponent({
      loader: () => import(/* @vite-ignore */ `../../../../../../../../resources/js/IPBB/Blocks/${block.render}.vue`),
      error: FallbackBlock,
      onError: (error, retry, fail, attempts) => {
        if (attempts <= 3) {
          retry()
        } else {
          console.error(`Failed to load component: ${block.render}`, error)
          fail()
        }
      }
    }),
    options: defineAsyncComponent({
      loader: () => import(/* @vite-ignore */ `../../../../../../../../resources/js/IPBB/Blocks/${block.options}.vue`),
      error: FallbackBlock,
      onError: (error, retry, fail, attempts) => {
        if (attempts <= 3) {
          retry()
        } else {
          console.error(`Failed to load component: ${block.options}`, error)
          fail()
        }
      }
    }),
    defaultProps: block.data,
    icon: 'fas fa-cube',
  }))
})

const filteredBlocks = computed(() => {
  return availableBlocks.value.filter(block => 
    block.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

const addBlock = (block) => {
  blocks.value.push({
    ...block,
    props: { ...block.defaultProps },
  })
  selectedBlock.value = blocks.value.length - 1
}

const selectBlock = (index) => {
  selectedBlock.value = index
  isEditing.value = false
}

const editBlock = (index) => {
  selectedBlock.value = index
  isEditing.value = true
}

const deleteBlock = (index) => {
  blocks.value.splice(index, 1)
  if (selectedBlock.value === index) {
    selectedBlock.value = null
    isEditing.value = false
  }
}

const updateBlockProps = (newProps) => {
  if (selectedBlock.value !== null) {
    blocks.value[selectedBlock.value].props = newProps
  }
}

const finishEditing = () => {
  isEditing.value = false
}

const getBlockComponent = (block) => {
  const foundBlock = availableBlocks.value.find(b => b.name === block.name);
  return foundBlock ? foundBlock.component : null;
}
</script>

<style scoped>
.ipbb-page-builder {
  display: flex;
  flex-direction: column;
  height: 100vh;
  font-family: Arial, sans-serif;
}

.ipbb-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 20px;
  background-color: #f0f0f0;
  border-bottom: 1px solid #ddd;
}

.ipbb-header h2 {
  margin: 0;
}

.ipbb-header-actions {
  display: flex;
  gap: 10px;
}

.ipbb-icon-button {
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1.2em;
}

.ipbb-content {
  display: flex;
  flex: 1;
  overflow: hidden;
}

.ipbb-editor {
  flex: 1;
  padding: 20px;
  overflow-y: auto;
}

.ipbb-sidebar {
  width: 300px;
  background-color: #f8f8f8;
  border-left: 1px solid #ddd;
  padding: 20px;
  overflow-y: auto;
}

.ipbb-search {
  margin-bottom: 20px;
}

.ipbb-search input {
  width: 100%;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.ipbb-available-blocks {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.ipbb-block-button {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px;
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 4px;
  cursor: pointer;
  text-align: left;
}

.ipbb-block-button:hover {
  background-color: #f0f0f0;
}

.ipbb-block-wrapper {
  position: relative;
  margin-bottom: 10px;
  border: 2px solid transparent;
}

.ipbb-block-selected {
  border-color: #007bff;
}

.ipbb-block-actions {
  display: none;
  position: absolute;
  top: 5px;
  right: 5px;
  background-color: rgba(255, 255, 255, 0.9);
  border-radius: 4px;
  padding: 5px;
}

.ipbb-block-wrapper:hover .ipbb-block-actions {
  display: flex;
  gap: 5px;
}

.ipbb-block-actions button {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 3px 8px;
  border-radius: 3px;
  cursor: pointer;
}

.ipbb-block-actions button:hover {
  background-color: #0056b3;
}
</style>