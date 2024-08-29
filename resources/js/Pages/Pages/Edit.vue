<template>
    <AuthenticatedLayout>   
            <PageBuilderComponent :registeredBlocks="blocks" :data="page.content" @save="saveBlocks" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageBuilderComponent from '@/../../packages/cruzmediaorg/inertia-page-block-builder/resources/js/components/PageBuilder.vue';
import { router } from '@inertiajs/vue3';
const props = defineProps(['blocks', 'page']);

const saveBlocks = (blocks) => {
    // Parse the JSON string to convert it to an object
    const contentObject = JSON.parse(blocks);
    
    router.put(route('pages.update', props.page.id), {
        content: contentObject
    });
}
</script>

<style lang="scss" scoped>

</style>