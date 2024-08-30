import { ref, computed } from 'vue';
import useContainerAttributes from './useContainerAttributes';

export default function useContainerManagement(initialContainers = []) {
  const { createAttributes } = useContainerAttributes();
  const containers = ref(initialContainers.map(container => ({
    ...container,
    attributes: createAttributes(container.attributes),
  })));

  const selectedContainerId = ref(null);

  const selectedContainer = computed(() => 
    containers.value.find(c => c.id === selectedContainerId.value) || null
  );

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

  const hasPlaceholders = (container) => {
    return container.blocks.some(block => block.isPlaceholder);
  };

  const getContainerBlocksPerRow = (type) => {
    const containerConfig = containerTypes.find(ct => ct.type === type);
    return containerConfig ? containerConfig.blocksPerRow : 1;
  };

  const containerTypes = [
    { type: 'full', name: 'Full Width (1 block per row)', blocksPerRow: 1 },
    { type: 'half', name: '1/2 - 1/2 (2 blocks per row)', blocksPerRow: 2 },
    { type: 'third', name: '1/3 - 1/3 - 1/3 (3 blocks per row)', blocksPerRow: 3 },
    { type: 'quarter', name: '1/4 - 1/4 - 1/4 - 1/4 (4 blocks per row)', blocksPerRow: 4 },
  ];

  return {
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
  };
}