<script setup>
defineOptions({
    inheritAttrs: false,
});

const logoUrl = '/images/ai-workspace-logo-transparent.png';
</script>

<template>
    <img
        :src="logoUrl"
        alt="AI Workspace Logo"
        v-bind="$attrs"
    >
</template>
