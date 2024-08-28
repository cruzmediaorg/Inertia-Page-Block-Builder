<template>
  <div class="ipbb-options">
    <component
      v-for="(option, index) in options"
      :key="index"
      :is="getComponentType(option)"
      v-bind="option"
      :modelValue="localProps[option.name]"
      @update:modelValue="updateLocalProp(option.name, $event)"
    />
  </div>
</template>

<script setup>
import { ref, watch, onMounted, defineAsyncComponent } from 'vue'

const props = defineProps({
  modelValue: {
    type: Object,
    required: true,
  },
  options: {
    type: Array,
    required: true,
  },
})

const emit = defineEmits(['update:modelValue'])

const localProps = ref(props.modelValue)

watch(() => props.modelValue, (newValue) => {
  Object.keys(newValue).forEach(key => {
    if (localProps.value[key] !== newValue[key]) {
      localProps.value[key] = newValue[key]
    }
  })
}, { deep: true, immediate: true })

const updateLocalProp = (name, value) => {
  if (localProps.value[name] !== value) {
    localProps.value[name] = value
    emit('update:modelValue', localProps.value)
  }
}

const componentCache = new Map();

const getComponentType = (option) => {
  if (componentCache.has(option.componentPath)) {
    return componentCache.get(option.componentPath);
  }

  console.log('option', option);
  const componentPromise = defineAsyncComponent(() => {
    // Try to load from the package directory first
    return import(/* @vite-ignore */ option.componentPath)
      .catch(() => {
        console.warn(`Failed to load component from package directory: ${option.componentPath}. Trying root directory.`);
        // If package directory fails, try loading from the root directory
        return import(/* @vite-ignore */ `/resources/js/IPBB/Options/${option.type.charAt(0).toUpperCase() + option.type.slice(1)}Option.vue`)
          .catch(() => {
            console.warn(`Failed to load component from root directory. Falling back to default.`);
            // If both fail, fall back to the default (package directory)
            return import(/* @vite-ignore */ `./options/${option.type.charAt(0).toUpperCase() + option.type.slice(1)}Option.vue`);
          });
      });
  });

  componentCache.set(option.componentPath, componentPromise);
  return componentPromise;
}

</script>