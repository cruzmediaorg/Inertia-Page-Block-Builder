<template>
    <header :style="{ backgroundColor: backgroundColor }" class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl md:text-6xl">
                {{ title }}
            </h1>
            <h2 v-if="subtitle" class="mt-3 text-xl text-gray-300 sm:text-2xl md:text-3xl">
                {{ subtitle }}
            </h2>
        </div>
    </header>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    subtitle: {
        type: String,
        default: '',
    },
    name: {
        type: String,
        required: true,
    },
    backgroundColor: {
        type: String,
        default: '#000000',
    },
});
</script>

