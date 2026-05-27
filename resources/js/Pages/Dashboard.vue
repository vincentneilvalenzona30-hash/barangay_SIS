<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    stats: Object,
    recentResidents: Array,
    recentBlotters: Array,
});

const statusClass = (status) => ({
    Active: 'bg-rose-50 text-rose-700 ring-rose-200',
    Settled: 'bg-emerald-50 text-emerald-700 ring-emerald-200',
    'Scheduled for Hearing': 'bg-amber-50 text-amber-700 ring-amber-200',
}[status] || 'bg-slate-50 text-slate-700 ring-slate-200');
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-2xl font-bold leading-tight text-slate-900">Dashboard</h2>
                    <p class="mt-1 text-sm text-slate-500">Barangay operations overview and quick access center.</p>
                </div>
                <div class="flex gap-2">
                    <Link :href="route('residents.index')" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                        Add Resident
                    </Link>
                    <Link :href="route('clearances.index')" class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Issue Clearance
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
                <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Total Residents</p>
                        <p class="mt-3 text-3xl font-bold text-slate-950">{{ stats.residents }}</p>
                        <p class="mt-2 text-xs text-slate-500">Registered community members</p>
                    </div>
                    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Blotter Records</p>
                        <p class="mt-3 text-3xl font-bold text-slate-950">{{ stats.blotters }}</p>
                        <p class="mt-2 text-xs text-slate-500">Filed incident reports</p>
                    </div>
                    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Active Cases</p>
                        <p class="mt-3 text-3xl font-bold text-rose-600">{{ stats.activeBlotters }}</p>
                        <p class="mt-2 text-xs text-slate-500">Require follow-up</p>
                    </div>
                    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Pending Requests</p>
                        <p class="mt-3 text-3xl font-bold text-amber-600">{{ stats.pendingRequests }}</p>
                        <p class="mt-2 text-xs text-slate-500">Need admin review</p>
                    </div>
                </section>

                <section class="grid gap-6 lg:grid-cols-[0.72fr_0.28fr]">
                    <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-200 px-6 py-4">
                            <h3 class="text-lg font-semibold text-slate-900">Recent Blotter Activity</h3>
                        </div>
                        <div class="divide-y divide-slate-100">
                            <div v-for="blotter in recentBlotters" :key="blotter.id" class="grid gap-3 px-6 py-4 sm:grid-cols-[1fr_auto]">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ blotter.incident_type }}</p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ blotter.resident?.last_name }}, {{ blotter.resident?.first_name }} - {{ blotter.date_of_incident }}
                                    </p>
                                    <p class="mt-2 line-clamp-2 text-sm text-slate-600">{{ blotter.narrative }}</p>
                                </div>
                                <span class="h-fit rounded-full px-3 py-1 text-xs font-semibold ring-1" :class="statusClass(blotter.status)">
                                    {{ blotter.status }}
                                </span>
                            </div>
                            <div v-if="recentBlotters.length === 0" class="px-6 py-10 text-center text-sm text-slate-500">
                                No blotter activity yet.
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-slate-900">Quick Actions</h3>
                            <div class="mt-4 grid gap-3">
                                <Link :href="route('residents.index')" class="rounded-md border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">
                                    Manage Residents
                                </Link>
                                <Link :href="route('blotters.index')" class="rounded-md border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">
                                    Record Blotter
                                </Link>
                                <Link :href="route('admin.requests.index')" class="rounded-md border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">
                                    Review Requests
                                </Link>
                                <Link :href="route('reports.index')" class="rounded-md border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">
                                    View Reports
                                </Link>
                            </div>
                        </div>

                        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-slate-900">Newest Residents</h3>
                            <div class="mt-4 space-y-3">
                                <div v-for="resident in recentResidents" :key="resident.id" class="rounded-md bg-slate-50 p-3">
                                    <p class="font-medium text-slate-900">{{ resident.last_name }}, {{ resident.first_name }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ resident.address }}</p>
                                </div>
                                <p v-if="recentResidents.length === 0" class="text-sm text-slate-500">No residents registered yet.</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
