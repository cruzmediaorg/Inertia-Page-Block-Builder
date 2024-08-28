<template>
    <div class="fallback-block">
      <p>Failed to load block component</p>
    </div>
  </template>
  
  <script>
  export default {
    name: 'FallbackBlock'
  }
  </script>
  
  <style scoped>
  .fallback-block {
    padding: 20px;
    background-color: #ffeeee;
    border: 1px solid #ffcccc;
    color: #ff0000;
  }
  </style>