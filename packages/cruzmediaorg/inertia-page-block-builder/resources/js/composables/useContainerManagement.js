import { ref, computed } from 'vue';
import useContainerAttributes from './useContainerAttributes';

const { createAttributes } = useContainerAttributes();

const containerTypes = [
  { type: 'full', name: 'Full Width (1 column)', columnsCount: 1 },
  { type: 'half', name: '1/2 - 1/2 (2 columns)', columnsCount: 2 },
  { type: 'third', name: '1/3 - 1/3 - 1/3 (3 columns)', columnsCount: 3 },
  { type: 'quarter', name: '1/4 - 1/4 - 1/4 - 1/4 (4 columns)', columnsCount: 4 },
];

export const getContainerColumnsCount = (type) => {
  const containerConfig = containerTypes.find(ct => ct.type === type);
  return containerConfig ? containerConfig.columnsCount : 1;
};

export default function useContainerManagement(initialData = [], registeredBlocks = []) {
  const containers = ref(initialData);
  const selectedContainerId = ref(null);

  const selectedContainer = computed(() => 
    containers.value.find(c => c.id === selectedContainerId.value) || null
  );

  const addContainer = (type, position = containers.value.length) => {
    const columnsCount = getContainerColumnsCount(type);
    const newContainer = {
      id: Date.now().toString(),
      type,
      columns: Array(columnsCount).fill().map(() => ({
        blocks: []
      })),
      attributes: createAttributes(),
    };
    containers.value.splice(position, 0, newContainer);
    return newContainer.id;
  };

  const updateContainerAttributes = (containerId, newAttributes) => {
    const container = containers.value.find(c => c.id === containerId);
    if (container) {
      container.attributes = { ...container.attributes, ...newAttributes };
    }
  };

  const selectContainer = (containerId) => {
    selectedContainerId.value = containerId;
  };

  const deselectContainer = () => {
    selectedContainerId.value = null;
  };

  const addBlockToColumn = (containerId, columnIndex, block) => {
    const container = containers.value.find(c => c.id === containerId);
    if (container && container.columns[columnIndex]) {
      container.columns[columnIndex].blocks.push(block);
    }
  };

  const loadContainers = (data) => {
    containers.value = data.map(container => ({
      ...container,
      columns: container.columns.map(column => ({
        ...column,
        blocks: column.blocks.map(block => {
          const registeredBlock = registeredBlocks.find(rb => rb.name === block.name);
          return {
            ...block,
            options: registeredBlock ? registeredBlock.options : [],
            defaultProps: registeredBlock ? registeredBlock.data : {}
          };
        })
      }))
    }));
  };

  return {
    containers,
    selectedContainer,
    addContainer,
    updateContainerAttributes,
    selectContainer,
    deselectContainer,
    containerTypes,
    loadContainers,
  };
}