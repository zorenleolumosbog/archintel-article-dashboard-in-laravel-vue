<script setup lang="ts">
    const props = defineProps([
        'inputFilter'
    ]);
</script>

<template>
    <input v-model="props.inputFilter.search" @keydown="$emit('debouncedRecords')" type="text" class="form-control" placeholder="Search">
    <input v-model="props.inputFilter.dateFrom" @change="$emit('debouncedRecords')" type="date" class="form-control" placeholder="Filter Date Start">
    <input v-model="props.inputFilter.dateTo" @change="$emit('debouncedRecords')" type="date" class="form-control" placeholder="Filter Date End">
</template>