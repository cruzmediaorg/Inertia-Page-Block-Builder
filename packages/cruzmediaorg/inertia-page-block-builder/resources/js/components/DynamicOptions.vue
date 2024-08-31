<template>
  <div class="flex flex-col gap-2 divide-y">
    <component
      v-for="(option, index) in options"
      :key="index"
      :is="getComponentType(option)"
      v-bind="option"
      class="flex flex-col mt-2 py-2"
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

  const componentPromise = defineAsyncComponent(() => {
    return import(/* @vite-ignore */ option.componentPath)
      .catch(() => {
        console.warn(`Failed to load component from package directory: ${option.componentPath}. Trying root directory.`);
        return import(/* @vite-ignore */ `/resources/js/IPBB/Options/${option.type.charAt(0).toUpperCase() + option.type.slice(1)}Option.vue`)
          .catch(() => {
            console.warn(`Failed to load component from root directory. Falling back to default.`);
            return import(/* @vite-ignore */ `./options/${option.type.charAt(0).toUpperCase() + option.type.slice(1)}Option.vue`);
          });
      });
  });

  componentCache.set(option.componentPath, componentPromise);
  return componentPromise;
}

</script>