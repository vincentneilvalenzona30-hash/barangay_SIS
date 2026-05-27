<script setup>
defineProps({
    newsItems: {
        type: Array,
        default: () => [],
    },
    events: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <section class="relative overflow-hidden px-4 py-12 sm:px-6 lg:px-8">
        <div class="relative mx-auto max-w-7xl">
            <div class="mx-auto max-w-3xl text-center">
                <p class="inline-flex rounded-full bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-800 ring-1 ring-emerald-200">
                    Barangay Bacani Updates
                </p>
                <h1 class="mt-6 text-4xl font-extrabold tracking-tight text-emerald-950 sm:text-5xl">
                    News & Events
                </h1>
                <p class="mt-5 text-lg leading-8 text-emerald-950/70">
                    Stay informed about barangay announcements, public service updates, community activities, and resident service reminders in Bacani, Clarin, Bohol.
                </p>
            </div>

            <section class="mt-12 grid gap-6 lg:grid-cols-3">
                <article v-for="item in newsItems" :key="item.title" class="overflow-hidden rounded-lg border border-emerald-100 bg-white shadow-sm">
                    <img v-if="item.image_url" :src="item.image_url" :alt="item.title" class="h-48 w-full object-cover" />
                    <div class="p-6">
                        <div class="flex items-center justify-between gap-3">
                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">{{ item.tag }}</span>
                            <span class="text-xs font-medium text-emerald-700">{{ item.date_label || item.date }}</span>
                        </div>
                        <h2 class="mt-5 text-xl font-bold text-emerald-950">{{ item.title }}</h2>
                        <p class="mt-3 text-sm leading-6 text-emerald-950/70">{{ item.description }}</p>
                    </div>
                </article>
            </section>

            <section class="mt-12 rounded-lg bg-white p-6 shadow-sm ring-1 ring-emerald-100 sm:p-8">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-emerald-950">Upcoming & Regular Activities</h2>
                        <p class="mt-2 text-sm text-emerald-950/70">Schedules may be updated by the barangay office as announcements are finalized.</p>
                    </div>
                    <p class="font-semibold text-yellow-700">Bacani, Clarin, Bohol</p>
                </div>

                <div class="mt-6 divide-y divide-emerald-100">
                    <div v-for="event in events" :key="event.title" class="grid gap-4 py-5 sm:grid-cols-[96px_1fr_220px_220px] sm:items-center">
                        <img v-if="event.image_url" :src="event.image_url" :alt="event.title" class="h-20 w-full rounded-md object-cover sm:w-24" />
                        <div v-else class="hidden h-20 rounded-md bg-emerald-50 ring-1 ring-emerald-100 sm:block"></div>
                        <p class="font-semibold text-emerald-950">{{ event.title }}</p>
                        <p class="text-sm text-emerald-950/70">{{ event.schedule || event.date_label }}</p>
                        <p class="text-sm text-emerald-950/70">{{ event.location || 'Barangay Bacani' }}</p>
                    </div>
                </div>
            </section>
        </div>
    </section>
</template>
