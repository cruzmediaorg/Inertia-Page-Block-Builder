<template>
  <div :class="'ipbb-' + blockName + '-block'">
    <h2>{{ title }}</h2>
    <div v-html="content"></div>
  </div>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      required: true,
    },
    content: {
      type: String,
      default: '',
    },
  },
  computed: {
    blockName() {
      return '{{ name }}'.toLowerCase();
    }
  }
}
</script>

<style scoped>
/* Use a more generic selector */
[class^="ipbb-"][class$="-block"] {
  /* Add your styles here */
}
</style>