<template>
    <header :style="{ backgroundColor: backgroundColor }" class="text-white py-24 px-4 sm:px-6 lg:px-8 flex items-center justify-center">
      <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold leading-tight">{{ title }}</h1>
        <h2 v-if="subtitle" class="mt-2 text-xl text-gray-300">{{ subtitle }}</h2>
      </div>
    </header>
</template>

<script setup>
defineProps({
    title: String,
    subtitle: String,
    backgroundColor: String,
});
</script>
