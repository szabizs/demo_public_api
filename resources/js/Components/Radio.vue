<script setup>
import { computed } from 'vue';

const emit = defineEmits(['update:selected']);

const props = defineProps({
    selected: {
        type: [Array, Boolean],
        default: false,
    },
    value: {
        default: null,
    },
});

const proxyChecked = computed({
    get() {
        return props.selected;
    },

    set(val) {
        emit("update:selected", val);
    },
});
</script>

<template>
    <input type="radio" :value="value" v-model="proxyChecked"
           class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
</template>
