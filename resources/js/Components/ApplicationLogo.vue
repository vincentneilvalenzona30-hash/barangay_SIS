<script setup>
import { ref } from 'vue';

defineOptions({
    inheritAttrs: false,
});

defineProps({
    src: {
        type: String,
        default: '/images/barangay-logo.png',
    },
});

const imageFailed = ref(false);
</script>

<template>
    <span
        v-bind="$attrs"
        class="inline-flex shrink-0 items-center justify-center overflow-hidden rounded-full bg-white ring-1 ring-slate-200"
    >
        <img
            v-if="!imageFailed"
            :src="src"
            alt="Barangay logo"
            class="h-full w-full object-cover"
            @error="imageFailed = true"
        />
        <svg
            v-else
            class="h-2/3 w-2/3 text-indigo-600"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="1.8"
                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
            />
        </svg>
    </span>
</template>
