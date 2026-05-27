<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NewsEventsContent from '@/Components/NewsEventsContent.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    newsItems: {
        type: Array,
        default: () => [],
    },
    events: {
        type: Array,
        default: () => [],
    },
});

const fallbackNews = [
    {
        title: 'Barangay Service Portal Now Available',
        date: 'May 2026',
        tag: 'Announcement',
        description: 'Residents of Barangay Bacani may now use the online portal for account registration, resident verification, and service requests.',
    },
    {
        title: 'Resident Verification Drive',
        date: 'Ongoing',
        tag: 'Community',
        description: 'Residents are encouraged to complete their verification by submitting accurate barangay information, valid ID, and face capture through the portal.',
    },
    {
        title: 'Document Request Assistance',
        date: 'Weekdays',
        tag: 'Service',
        description: 'Barangay document requests such as clearances, residency certificates, and indigency certificates may be tracked through user accounts.',
    },
];

const fallbackEvents = [
    {
        title: 'Community Clean-Up Activity',
        schedule: 'Every scheduled barangay clean-up day',
        location: 'Barangay Bacani, Clarin, Bohol',
    },
    {
        title: 'Barangay Information Updating',
        schedule: 'Office hours',
        location: 'Barangay Office',
    },
    {
        title: 'Health and Public Service Announcements',
        schedule: 'As announced',
        location: 'Barangay Bacani',
    },
];

const newsItems = props.newsItems.length ? props.newsItems : fallbackNews;
const events = props.events.length ? props.events : fallbackEvents;
const page = usePage();
const isAuthenticated = computed(() => Boolean(page.props.auth?.user));
</script>

<template>
    <Head title="News & Events" />

    <AuthenticatedLayout v-if="isAuthenticated">
        <template #header>
            <div>
                <h2 class="text-2xl font-bold leading-tight text-slate-900">News & Events</h2>
                <p class="mt-1 text-sm text-slate-500">Barangay announcements, activities, and public service updates.</p>
            </div>
        </template>

        <section class="px-4 py-8 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
                    <section class="space-y-5">
                        <div class="rounded-lg border border-emerald-100 bg-white p-6 shadow-sm">
                            <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">Community bulletin</p>
                            <h1 class="mt-2 text-3xl font-bold text-emerald-950">Latest News</h1>
                            <p class="mt-3 max-w-3xl text-sm leading-6 text-emerald-950/65">
                                Read barangay announcements, resident reminders, service advisories, and updates from Barangay Bacani.
                            </p>
                        </div>

                        <article v-for="item in newsItems" :key="item.title" class="overflow-hidden rounded-lg border border-emerald-100 bg-white shadow-sm">
                            <div class="grid gap-0 md:grid-cols-[240px_1fr]">
                                <img v-if="item.image_url" :src="item.image_url" :alt="item.title" class="h-56 w-full object-cover md:h-full" />
                                <div v-else class="hidden bg-emerald-50 md:block"></div>

                                <div class="p-6">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">{{ item.tag || 'News' }}</span>
                                        <span class="text-xs font-medium text-slate-500">{{ item.date_label || item.date || 'As announced' }}</span>
                                    </div>
                                    <h2 class="mt-4 text-xl font-bold text-slate-950">{{ item.title }}</h2>
                                    <p class="mt-3 text-sm leading-6 text-slate-600">{{ item.description }}</p>
                                </div>
                            </div>
                        </article>
                    </section>

                    <aside class="space-y-5">
                        <section class="rounded-lg border border-yellow-100 bg-white p-6 shadow-sm">
                            <p class="text-sm font-semibold uppercase tracking-wide text-yellow-700">Schedule board</p>
                            <h2 class="mt-2 text-2xl font-bold text-emerald-950">Events</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                Keep track of upcoming and regular barangay activities.
                            </p>
                        </section>

                        <article v-for="event in events" :key="event.title" class="rounded-lg border border-emerald-100 bg-white p-5 shadow-sm">
                            <img v-if="event.image_url" :src="event.image_url" :alt="event.title" class="mb-4 h-36 w-full rounded-md object-cover" />
                            <div class="flex items-center gap-2">
                                <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800">{{ event.tag || 'Event' }}</span>
                                <span class="text-xs font-medium text-slate-500">{{ event.date_label || 'Schedule' }}</span>
                            </div>
                            <h3 class="mt-3 font-bold text-slate-950">{{ event.title }}</h3>
                            <dl class="mt-4 space-y-3 text-sm">
                                <div>
                                    <dt class="font-semibold text-slate-700">When</dt>
                                    <dd class="mt-1 text-slate-500">{{ event.schedule || event.date_label || 'As announced' }}</dd>
                                </div>
                                <div>
                                    <dt class="font-semibold text-slate-700">Where</dt>
                                    <dd class="mt-1 text-slate-500">{{ event.location || 'Barangay Bacani' }}</dd>
                                </div>
                            </dl>
                            <p class="mt-4 text-sm leading-6 text-slate-600">{{ event.description }}</p>
                        </article>
                    </aside>
                </div>
            </div>
        </section>
    </AuthenticatedLayout>

    <div v-else class="min-h-screen bg-emerald-50">
        <nav class="bg-white/95 shadow-sm backdrop-blur">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <Link href="/" class="flex items-center gap-2">
                    <ApplicationLogo class="h-10 w-10" />
                    <span class="font-bold text-xl text-emerald-950">Barangay Bacani</span>
                </Link>
                <div class="flex items-center gap-4">
                    <Link href="/" class="text-sm font-semibold text-emerald-800 hover:text-emerald-950">Home</Link>
                    <Link href="/login" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Portal</Link>
                </div>
            </div>
        </nav>

        <main class="relative overflow-hidden bg-gradient-to-b from-emerald-100 via-lime-50 to-white px-4 py-16 sm:px-6 lg:px-8">
            <div class="absolute left-0 top-16 h-64 w-64 rounded-full bg-emerald-200/60 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 h-72 w-72 rounded-full bg-yellow-100/80 blur-3xl"></div>

            <NewsEventsContent class="relative" :news-items="newsItems" :events="events" />
        </main>
    </div>
</template>
