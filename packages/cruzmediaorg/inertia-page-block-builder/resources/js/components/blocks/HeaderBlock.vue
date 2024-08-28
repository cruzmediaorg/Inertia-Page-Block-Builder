<template>
  <header class="ipbb-header-block">
    <h1>{{ title }}</h1>
    <h2>{{ subtitle }}</h2>
  </header>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      required: true,
    },
    subtitle: {
      type: String,
      default: '',
    },
  },
}
</script>

<style scoped>
.ipbb-header-block {
  text-align: center;
  padding: 20px;
  background-color: #f0f0f0;
}

.ipbb-header-block h1 {
  font-size: 2.5em;
  margin-bottom: 10px;
}

.ipbb-header-block h2 {
  font-size: 1.5em;
  color: #666;
}
</style>