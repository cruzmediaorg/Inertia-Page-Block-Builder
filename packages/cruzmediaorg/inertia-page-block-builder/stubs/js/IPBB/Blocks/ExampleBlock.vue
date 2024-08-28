<template>
  <div class="example-block">
    <h2>{{ title }}</h2>
  </div>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      default: 'Example Block',
    },
  },
}
</script>