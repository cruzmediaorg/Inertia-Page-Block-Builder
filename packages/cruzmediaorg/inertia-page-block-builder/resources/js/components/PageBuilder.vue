<template>
  <div class="ipbb-page-builder">
    <div class="ipbb-header">
      <h2>Editor</h2>
      <div class="ipbb-header-actions">
        <button class="ipbb-icon-button">
          <i class="fas fa-mobile-alt"></i>
        </button>
        <button class="ipbb-icon-button">
          <i class="fas fa-expand"></i>
        </button>
      </div>
    </div>
    <div class="ipbb-content">
      <div class="ipbb-editor">
        <div class="ipbb-preview">
          <div
            v-for="(block, index) in blocks"
            :key="index"
            class="ipbb-block-wrapper"
            :class="{ 'ipbb-block-selected': selectedBlock === index }"
            @click="selectBlock(index)"
          >
            <component
              :is="block.component"
              v-bind="block.props"
            />
            <div class="ipbb-block-actions">
              <button @click.stop="editBlock(index)">Edit</button>
              <button @click.stop="deleteBlock(index)">Delete</button>
            </div>
          </div>
        </div>
      </div>
      <div class="ipbb-sidebar">
        <div v-if="selectedBlock !== null && isEditing">
          <h3>Edit {{ blocks[selectedBlock].name }}</h3>
          <component
            :is="blocks[selectedBlock].options"
            v-model="blocks[selectedBlock].props"
            @update:modelValue="updateBlockProps"
          />
          <button @click="finishEditing">Done</button>
        </div>
        <div v-else>
          <h3>Blocks</h3>
          <div class="ipbb-search">
            <input type="text" placeholder="Search blocks" v-model="searchQuery" />
          </div>
          <div class="ipbb-available-blocks">
            <button
              v-for="block in filteredBlocks"
              :key="block.name"
              class="ipbb-block-button"
              @click="addBlock(block)"
            >
              <i :class="block.icon"></i>
              {{ block.name }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue'

export default {
  props: {
    registeredBlocks: {
      type: Array,
      required: true
    }
  },
  setup(props) {
    const blocks = ref([])
    const selectedBlock = ref(null)
    const isEditing = ref(false)
    const searchQuery = ref('')

    const availableBlocks = computed(() => {
      if (!props.registeredBlocks) return []
      return props.registeredBlocks.map(block => ({
        name: block.name,
        component: resolveComponent(block.render),
        options: resolveComponent(block.render.replace('Render', 'Options')),
        defaultProps: Object.fromEntries(
          Object.entries(block.options).map(([key, value]) => [key, value.default])
        ),
        icon: 'fas fa-cube', // You might want to add an icon property to your Block class
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

    return {
      blocks,
      selectedBlock,
      isEditing,
      searchQuery,
      filteredBlocks,
      addBlock,
      selectBlock,
      editBlock,
      deleteBlock,
      updateBlockProps,
      finishEditing,
    }
  },
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