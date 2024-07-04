<script setup>
import { useLayout } from '@/layout/composables/layout';
import { computed } from 'vue';

const { layoutConfig } = useLayout();

const logoUrl = computed(() => {
    return `/layout/images/${layoutConfig.darkTheme.value ? 'logo-dark' : 'logo-dark'}.svg`;
});

const logoFooter = computed(() => {
    return `/layout/images/${layoutConfig.darkTheme.value ? 'logo-footer' : 'logo-footer'}.svg`;
});
</script>

<template>
    <div class="layout-footer">
        <img :src="logoUrl" alt="Logo" height="20" class="mr-2 mt-1" />
        by
        <img :src="logoFooter" alt="XOXTECH" height="20" class="ml-2 mt-1" />
    </div>
</template>
<style lang="scss" scoped></style>
