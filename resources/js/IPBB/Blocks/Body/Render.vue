<template>
  <div class="ipbb-{{name.toLowerCase()}}-block">
    <h1>{{ title }}</h1>
    <h2>{{ subtitle }}</h2>
  </div>
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
.ipbb-{{name.toLowerCase()}}-block {
  /* Add your styles here */
}
</style>