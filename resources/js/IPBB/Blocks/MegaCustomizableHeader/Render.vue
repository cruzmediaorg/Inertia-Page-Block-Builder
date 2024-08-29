<template>
  <div :style="containerStyle" class="mega-customizable-header">
    <div class="header-content">
      <h1 :style="titleStyle" class="title">{{ title }}</h1>
      <h2 :style="subtitleStyle" class="subtitle">{{ subtitle }}</h2>
      <div :style="contentStyle" class="content" v-html="content"></div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  title: {
    type: String,
    required: true,
  },
  subtitle: {
    type: String,
    default: '',
  },
  content: {
    type: String,
    default: '',
  },
  titleFontSize: {
    type: String,
    default: '48px',
  },
  subtitleFontSize: {
    type: String,
    default: '24px',
  },
  contentFontSize: {
    type: String,
    default: '18px',
  },
  titleFontWeight: {
    type: String,
    default: '700',
  },
  subtitleFontWeight: {
    type: String,
    default: '400',
  },
  contentFontWeight: {
    type: String,
    default: '400',
  },
  fontFamily: {
    type: String,
    default: 'Arial, sans-serif',
  },
  backgroundColor: {
    type: String,
    default: '#f8f9fa',
  },
  textColor: {
    type: String,
    default: '#333333',
  },
  textAlignment: {
    type: String,
    default: 'left',
  },
});

const containerStyle = computed(() => ({
  backgroundColor: props.backgroundColor,
  color: props.textColor,
  fontFamily: props.fontFamily,
  textAlign: props.textAlignment,
  padding: '60px 20px',
}));

const titleStyle = computed(() => ({
  fontSize: props.titleFontSize,
  fontWeight: props.titleFontWeight,
  marginBottom: '0.5em',
  lineHeight: '1.2',
}));

const subtitleStyle = computed(() => ({
  fontSize: props.subtitleFontSize,
  fontWeight: props.subtitleFontWeight,
  marginBottom: '1em',
  opacity: '0.8',
}));

const contentStyle = computed(() => ({
  fontSize: props.contentFontSize,
  fontWeight: props.contentFontWeight,
  lineHeight: '1.6',
}));

const blockName = computed(() => '{{ name }}'.toLowerCase());
</script>

<style scoped>
.mega-customizable-header {
  width: 100%;
  box-sizing: border-box;
}

.header-content {
  max-width: 800px;
  margin: 0 auto;
}

.title {
  margin-top: 0;
}

.subtitle {
  margin-top: 0;
}

.content {
  margin-top: 1.5em;
}

.content p {
  margin-bottom: 1em;
}
</style>
