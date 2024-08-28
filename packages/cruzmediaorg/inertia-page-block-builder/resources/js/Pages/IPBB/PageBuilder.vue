<template>
  <div>
    <PageBuilder :registeredBlocks="registeredBlocks" />
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import PageBuilder from '../../components/PageBuilder.vue'

export default defineComponent({
  components: {
    PageBuilder
  },
  props: {
    registeredBlocks: {
      type: Array,
      required: true
    }
  }
})
</script>