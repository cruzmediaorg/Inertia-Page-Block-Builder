<template>
    <component :is="type" :style="computedStyle">{{ value }}</component>
  </template>
  
  <script setup>
  import { computed } from 'vue';
  import useBlockAttributes from '@/../../packages/cruzmediaorg/inertia-page-block-builder/resources/js/composables/useBlockAttributes.js';
  
  const props = defineProps({
    value: String,
    type: {
      type: String,
      validator: (value) => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p'].includes(value),
    },
    fontSize: String,
    fontFamily: String,
    color: String,
    backgroundColor: String,
    textAlign: String,
    textDecoration: String,
    textTransform: String,
    lineHeight: String,
    letterSpacing: String,
    padding: Object,
    margin: Object,
    fontStyle: {
      type: Object,
      default: () => ({
        bold: false,
        italic: false,
        underline: false,
      }),
    },
  });
  
  const { blockStyle } = useBlockAttributes(props);
  
  const computedStyle = computed(() => {
    const baseStyle = {
      ...blockStyle.value,
      ...Object.fromEntries(
        Object.entries(props).filter(([key, value]) => 
          typeof value === 'string' && !['value', 'type'].includes(key)
        )
      ),
    };
  
    // Add font-weight if bold is true
    if (props.fontStyle.bold) {
      baseStyle.fontWeight = 'bold';
    }
  
    // Add font-style if italic is true
    if (props.fontStyle.italic) {
      baseStyle.fontStyle = 'italic';
    }
  
    if (props.fontStyle.underline) {
      baseStyle.textDecoration = 'underline';
    }
  
    return baseStyle;
  });
  </script>
  