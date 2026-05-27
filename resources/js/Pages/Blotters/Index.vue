<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    blotters: Array,
    residents: Array,
});

const search = ref('');

const form = useForm({
    resident_id: '',
    incident_type: '',
    date_of_incident: '',
    narrative: '',
    status: 'Active',
});

const filteredBlotters = computed(() => {
    const term = search.value.toLowerCase().trim();

    if (!term) {
        return props.blotters;
    }

    return props.blotters.filter((blotter) => [
        blotter.incident_type,
        blotter.status,
        blotter.narrative,
        blotter.resident?.first_name,
        blotter.resident?.last_name,
    ].filter(Boolean).join(' ').toLowerCase().includes(term));
});

const statusClass = (status) => ({
    Active: 'bg-rose-50 text-rose-700 ring-rose-200',
    Settled: 'bg-emerald-50 text-emerald-700 ring-emerald-200',
    'Scheduled for Hearing': 'bg-amber-50 text-amber-700 ring-amber-200',
}[status] || 'bg-slate-50 text-slate-700 ring-slate-200');

const submitForm = () => {
    form.post(route('blotters.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset('resident_id', 'incident_type', 'date_of_incident', 'narrative'),
    });
};

const updateStatus = (blotter, status) => {
    router.patch(route('blotters.update', blotter.id), { status }, {
        preserveScroll: true,
    });
};

const deleteBlotter = (id) => {
    if (confirm('Delete this blotter record?')) {
        router.delete(route('blotters.destroy', id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="Blotter Records" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2 class="text-2xl font-bold leading-tight text-slate-900">Blotter Records</h2>
                <p class="mt-1 text-sm text-slate-500">File incidents, track case status, and monitor settlement progress.</p>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-[0.35fr_0.65fr] lg:px-8">
                <section class="h-fit rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">Record Incident</h3>
                    <p class="mt-1 text-sm text-slate-500">Connect each blotter entry to a registered resident.</p>

                    <form @submit.prevent="submitForm" class="mt-6 space-y-4">
                        <div>
                            <label class="text-sm font-medium text-slate-700">Resident</label>
                            <select v-model="form.resident_id" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="" disabled>Select resident</option>
                                <option v-for="resident in residents" :key="resident.id" :value="resident.id">
                                    {{ resident.last_name }}, {{ resident.first_name }}
                                </option>
                            </select>
                            <p v-if="form.errors.resident_id" class="mt-1 text-xs text-rose-600">{{ form.errors.resident_id }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Incident Type</label>
                            <input v-model="form.incident_type" type="text" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Noise complaint, dispute, theft..." required />
                            <p v-if="form.errors.incident_type" class="mt-1 text-xs text-rose-600">{{ form.errors.incident_type }}</p>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-medium text-slate-700">Incident Date</label>
                                <input v-model="form.date_of_incident" type="date" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-700">Status</label>
                                <select v-model="form.status" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="Active">Active</option>
                                    <option value="Scheduled for Hearing">Scheduled for Hearing</option>
                                    <option value="Settled">Settled</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Narrative</label>
                            <textarea v-model="form.narrative" rows="5" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Write a clear summary of the incident..." required></textarea>
                            <p v-if="form.errors.narrative" class="mt-1 text-xs text-rose-600">{{ form.errors.narrative }}</p>
                        </div>

                        <button type="submit" :disabled="form.processing" class="w-full rounded-md bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 disabled:opacity-50">
                            {{ form.processing ? 'Saving...' : 'Save Blotter' }}
                        </button>
                    </form>
                </section>

                <section class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="flex flex-col gap-4 border-b border-slate-200 p-6 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Case List</h3>
                            <p class="mt-1 text-sm text-slate-500">{{ filteredBlotters.length }} of {{ blotters.length }} cases shown</p>
                        </div>
                        <input v-model="search" type="search" class="w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:w-72" placeholder="Search cases..." />
                    </div>

                    <div class="divide-y divide-slate-100">
                        <article v-for="blotter in filteredBlotters" :key="blotter.id" class="p-6">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h4 class="text-base font-semibold text-slate-950">{{ blotter.incident_type }}</h4>
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold ring-1" :class="statusClass(blotter.status)">
                                            {{ blotter.status }}
                                        </span>
                                    </div>
                                    <p class="mt-2 text-sm text-slate-500">
                                        Complainant/Resident: {{ blotter.resident?.last_name }}, {{ blotter.resident?.first_name }} • {{ blotter.date_of_incident }}
                                    </p>
                                    <p class="mt-3 text-sm leading-6 text-slate-700">{{ blotter.narrative }}</p>
                                </div>

                                <div class="flex shrink-0 flex-wrap gap-2">
                                    <select :value="blotter.status" @change="updateStatus(blotter, $event.target.value)" class="rounded-md border-slate-300 text-xs shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="Active">Active</option>
                                        <option value="Scheduled for Hearing">Scheduled</option>
                                        <option value="Settled">Settled</option>
                                    </select>
                                    <button @click="deleteBlotter(blotter.id)" class="rounded-md border border-rose-200 px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </article>

                        <div v-if="filteredBlotters.length === 0" class="px-6 py-12 text-center text-sm text-slate-500">
                            No blotter records found.
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
