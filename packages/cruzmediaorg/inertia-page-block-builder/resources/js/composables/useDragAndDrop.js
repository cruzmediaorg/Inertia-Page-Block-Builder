import { ref, toRefs } from 'vue';

export default function useDragAndDrop(containersRef, addContainer, addBlock, moveBlock) {
  const { containers } = toRefs(containersRef);
  const isDragging = ref(false);
  const draggedItem = ref(null);

  const dragStart = (evt, type) => {
    isDragging.value = true;
    if (type === 'block') {
      draggedItem.value = { type: 'block', id: evt.item.dataset.blockId };
    } else if (type === 'container') {
      draggedItem.value = { type: 'container', id: evt.item.dataset.containerId };
    }
  };
  
  const dragEnd = () => {
    isDragging.value = false;
    draggedItem.value = null;
  };

  const handleDrop = (event, containerId, columnIndex) => {
    event.preventDefault();
    event.stopPropagation();

    let data;
    try {
      data = JSON.parse(event.dataTransfer.getData('text/plain'));
    } catch (error) {
      console.error('Failed to parse drag data', error);
      return;
    }

    if (data.type === 'container' && !containerId) {
      addContainer(data.containerType.type);
    } else if (data.type === 'block') {
      if (draggedItem.value && draggedItem.value.type === 'block') {
        // Move existing block
        moveBlock(draggedItem.value.id, containerId, columnIndex);
      } else {
        // Add new block
        addBlock(data.block, containerId, columnIndex);
      }
    }

    dragEnd();
  };

  return {
    isDragging,
    draggedItem,
    dragStart,
    dragEnd,
    handleDrop,
  };
}