import { ref } from 'vue';

export default function useDragAndDrop(containers, addContainer, addBlock) {
  const isDragging = ref(false);
  const draggedItem = ref(null);

  const dragStart = (evt) => {
    isDragging.value = true;
    if (evt.item.dataset.blockId) {
      draggedItem.value = { type: 'block', id: evt.item.dataset.blockId };
    } else if (evt.item.dataset.containerId) {
      draggedItem.value = { type: 'container', id: evt.item.dataset.containerId };
    }
  };

  const dragEnd = () => {
    isDragging.value = false;
    draggedItem.value = null;
  };

  const handleDrop = (event, containerId, index) => {
    event.preventDefault();

    if (draggedItem.value && draggedItem.value.type === 'block') {
      handleBlockDrop(containerId, index);
    } else {
      handleNewItemDrop(event, containerId, index);
    }

    event.dataTransfer.dropEffect = 'move';
    event.dataTransfer.clearData();
  };

  const handleBlockDrop = (containerId, index) => {
    const sourceContainer = containers.value.find(c => c.blocks.some(b => b.id === draggedItem.value.id));
    const targetContainer = containers.value.find(c => c.id === containerId);

    if (sourceContainer && targetContainer) {
      const blockToMove = sourceContainer.blocks.find(b => b.id === draggedItem.value.id);
      const sourceIndex = sourceContainer.blocks.findIndex(b => b.id === draggedItem.value.id);

      sourceContainer.blocks[sourceIndex] = {
        isPlaceholder: true,
        id: `placeholder-${Date.now()}-${sourceIndex}`,
        placeholderId: `placeholder-${Date.now()}-${sourceIndex}`,
        index: sourceIndex
      };

      addBlock(blockToMove, targetContainer.id, index);
      draggedItem.value = null;
    }
  };

  const handleNewItemDrop = (event, containerId, index) => {
    let data;
    try {
      data = JSON.parse(event.dataTransfer.getData('text/plain'));
    } catch (error) {
      console.error('Failed to parse drag data', error);
      return;
    }

    if (data.type === 'container' && !containerId) {
      const dropIndex = getDropIndex(event);
      addContainer(data.containerType.type, dropIndex);
    } else if (data.type === 'block') {
      let targetContainerId = containerId;
      if (!targetContainerId) {
        targetContainerId = getNearestContainerId(event);
        if (!targetContainerId) {
          addContainer('full');
          targetContainerId = containers.value[0].id;
        }
      }
      const container = containers.value.find(c => c.id === targetContainerId);
      if (container) {
        addBlock(data.block, targetContainerId, index);
      }
    }
  };

  const getDropIndex = (event) => {
    const containerElements = document.querySelectorAll('.container');
    for (let i = 0; i < containerElements.length; i++) {
      const rect = containerElements[i].getBoundingClientRect();
      if (event.clientY < rect.bottom) {
        return i;
      }
    }
    return containers.value.length;
  };

  const getNearestContainerId = (event) => {
    const containerElements = document.querySelectorAll('.container');
    let nearestContainer = null;
    let minDistance = Infinity;

    containerElements.forEach((container) => {
      const rect = container.getBoundingClientRect();
      const distance = Math.abs(event.clientY - (rect.top + rect.height / 2));
      if (distance < minDistance) {
        minDistance = distance;
        nearestContainer = container;
      }
    });

    return nearestContainer ? nearestContainer.getAttribute('data-container-id') : null;
  };

  return {
    isDragging,
    draggedItem,
    dragStart,
    dragEnd,
    handleDrop,
  };
}