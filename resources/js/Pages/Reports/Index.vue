<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    summary: Object,
    civilStatus: Object,
    incidentTypes: Object,
    recentBlotters: Array,
});

const objectEntries = (value) => Object.entries(value || {});
</script>

<template>
    <Head title="Reports" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2 class="text-2xl font-bold leading-tight text-slate-900">Reports</h2>
                <p class="mt-1 text-sm text-slate-500">Barangay resident and blotter summaries for quick review.</p>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
                <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Residents</p>
                        <p class="mt-3 text-3xl font-bold text-slate-950">{{ summary.residents }}</p>
                    </div>
                    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Male / Female</p>
                        <p class="mt-3 text-3xl font-bold text-slate-950">{{ summary.maleResidents }} / {{ summary.femaleResidents }}</p>
                    </div>
                    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Total Blotters</p>
                        <p class="mt-3 text-3xl font-bold text-slate-950">{{ summary.blotters }}</p>
                    </div>
                    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Active / Settled</p>
                        <p class="mt-3 text-3xl font-bold text-slate-950">{{ summary.activeBlotters }} / {{ summary.settledBlotters }}</p>
                    </div>
                </section>

                <section class="grid gap-6 lg:grid-cols-2">
                    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-slate-900">Civil Status Distribution</h3>
                        <div class="mt-5 space-y-4">
                            <div v-for="[label, count] in objectEntries(civilStatus)" :key="label">
                                <div class="flex justify-between text-sm">
                                    <span class="font-medium text-slate-700">{{ label }}</span>
                                    <span class="text-slate-500">{{ count }}</span>
                                </div>
                                <div class="mt-2 h-2 rounded-full bg-slate-100">
                                    <div class="h-2 rounded-full bg-indigo-600" :style="{ width: `${summary.residents ? (count / summary.residents) * 100 : 0}%` }"></div>
                                </div>
                            </div>
                            <p v-if="objectEntries(civilStatus).length === 0" class="text-sm text-slate-500">No resident data yet.</p>
                        </div>
                    </div>

                    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-slate-900">Incident Type Summary</h3>
                        <div class="mt-5 space-y-4">
                            <div v-for="[label, count] in objectEntries(incidentTypes)" :key="label">
                                <div class="flex justify-between text-sm">
                                    <span class="font-medium text-slate-700">{{ label }}</span>
                                    <span class="text-slate-500">{{ count }}</span>
                                </div>
                                <div class="mt-2 h-2 rounded-full bg-slate-100">
                                    <div class="h-2 rounded-full bg-sky-500" :style="{ width: `${summary.blotters ? (count / summary.blotters) * 100 : 0}%` }"></div>
                                </div>
                            </div>
                            <p v-if="objectEntries(incidentTypes).length === 0" class="text-sm text-slate-500">No blotter data yet.</p>
                        </div>
                    </div>
                </section>

                <section class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-6 py-4">
                        <h3 class="text-lg font-semibold text-slate-900">Recent Blotter Report</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Resident</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Incident</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                <tr v-for="blotter in recentBlotters" :key="blotter.id">
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ blotter.date_of_incident }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ blotter.resident?.last_name }}, {{ blotter.resident?.first_name }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ blotter.incident_type }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ blotter.status }}</td>
                                </tr>
                                <tr v-if="recentBlotters.length === 0">
                                    <td colspan="4" class="px-6 py-10 text-center text-sm text-slate-500">No report data available.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
