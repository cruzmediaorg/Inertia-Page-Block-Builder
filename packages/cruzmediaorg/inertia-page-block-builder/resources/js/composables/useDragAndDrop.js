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

  const handleDrop = (event, containerId, index) => {
    event.preventDefault();

    if (draggedItem.value && draggedItem.value.type === 'block') {
      moveBlock(draggedItem.value.id, containerId, index);
    } else {
      handleNewItemDrop(event, containerId, index);
    }

    dragEnd();
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
      addContainer(data.containerType.type);
    } else if (data.type === 'block') {
      addBlock(data.block, containerId, index);
    }
  };

  return {
    isDragging,
    draggedItem,
    dragStart,
    dragEnd,
    handleDrop,
  };
}