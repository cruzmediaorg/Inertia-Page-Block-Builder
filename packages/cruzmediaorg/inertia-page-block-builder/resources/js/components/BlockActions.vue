<template>
    <div class="absolute top-2 right-2  opacity-0 group-hover:opacity-100 transition-opacity duration-200 ease-in-out">
      <div class="flex bg-white rounded-md shadow-md">
        <div class="relative">
          <button class="block-drag-handle p-2 hover:bg-gray-100 hover:rounded-md">
            <svg class="w-6 h-6 text-gray-800 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h14"/>
            </svg>
            <div class="tooltip">Drag</div>
          </button>
        </div>
        <button @click.stop="$emit('edit')" class="p-2 hover:bg-gray-100 relative" title="Edit">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
          </svg>
          <div class="tooltip">Edit</div>
        </button>
        <button @click.stop="$emit('duplicate')" class="p-2 hover:bg-gray-100 relative" title="Duplicate">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path d="M7 9a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9z" />
            <path d="M5 3a2 2 0 00-2 2v6a2 2 0 002 2V5h8a2 2 0 00-2-2H5z" />
          </svg>
          <div class="tooltip">Duplicate</div>
        </button>
        <button @click.stop="$emit('delete')" class="p-2 hover:bg-gray-100 hover:rounded-md relative" title="Delete">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
          </svg>
          <div class="tooltip">Delete</div>
        </button>
      </div>
    </div>
  </template>
  
  <script setup>
  defineEmits(['more', 'edit', 'duplicate', 'delete'])
  </script>

  <style scoped>
  .tooltip {
    @apply invisible  absolute left-1/2 -translate-x-1/2 px-2 py-1 bg-white text-black text-xs rounded whitespace-nowrap;
    top: calc(100% + 5px);
  }

  .tooltip::after {
    content: '';
    position: absolute;
    top: -5px;
    left: 50%;
    transform: translateX(-50%);
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-bottom: 5px solid theme('colors.black');
  }

  button:hover .tooltip {
    @apply visible;
  }
  </style>