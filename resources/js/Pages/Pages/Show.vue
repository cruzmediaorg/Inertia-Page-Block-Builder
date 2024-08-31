<template>
            <PageBlockRenderer :page="page" />

</template>

<script setup>
import PageBlockRenderer from '@/../../packages/cruzmediaorg/inertia-page-block-builder/resources/js/components/PageBlockRenderer.vue';
const props = defineProps(['page']);
</script>