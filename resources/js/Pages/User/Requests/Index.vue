<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    requests: Array,
});

const search = ref('');

const form = useForm({
    request_type: 'Barangay Clearance',
    purpose: '',
    details: '',
});

const filteredRequests = computed(() => {
    const term = search.value.toLowerCase().trim();

    if (!term) {
        return props.requests;
    }

    return props.requests.filter((request) => [
        request.request_type,
        request.purpose,
        request.details,
        request.status,
        request.admin_notes,
    ].filter(Boolean).join(' ').toLowerCase().includes(term));
});

const statusClass = (status) => ({
    Pending: 'bg-amber-50 text-amber-700 ring-amber-200',
    'In Review': 'bg-sky-50 text-sky-700 ring-sky-200',
    Approved: 'bg-emerald-50 text-emerald-700 ring-emerald-200',
    Released: 'bg-indigo-50 text-indigo-700 ring-indigo-200',
    Rejected: 'bg-rose-50 text-rose-700 ring-rose-200',
}[status] || 'bg-slate-50 text-slate-700 ring-slate-200');

const submitForm = () => {
    form.post(route('user.requests.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset('purpose', 'details'),
    });
};
</script>

<template>
    <Head title="My Requests" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2 class="text-2xl font-bold leading-tight text-slate-900">My Requests</h2>
                <p class="mt-1 text-sm text-slate-500">Submit barangay service requests and track admin updates.</p>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-[0.36fr_0.64fr] lg:px-8">
                <section class="h-fit rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">New Request</h3>
                    <p class="mt-1 text-sm text-slate-500">Tell the barangay office what you need.</p>

                    <form @submit.prevent="submitForm" class="mt-6 space-y-4">
                        <div>
                            <label class="text-sm font-medium text-slate-700">Service Type</label>
                            <select v-model="form.request_type" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option>Barangay Clearance</option>
                                <option>Certificate of Residency</option>
                                <option>Certificate of Indigency</option>
                                <option>Incident Assistance</option>
                                <option>Other Barangay Service</option>
                            </select>
                            <p v-if="form.errors.request_type" class="mt-1 text-xs text-rose-600">{{ form.errors.request_type }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Purpose</label>
                            <input v-model="form.purpose" type="text" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Employment, school, assistance..." required />
                            <p v-if="form.errors.purpose" class="mt-1 text-xs text-rose-600">{{ form.errors.purpose }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Details</label>
                            <textarea v-model="form.details" rows="5" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Add helpful details, preferred pickup date, or concern summary."></textarea>
                            <p v-if="form.errors.details" class="mt-1 text-xs text-rose-600">{{ form.errors.details }}</p>
                        </div>

                        <button type="submit" :disabled="form.processing" class="w-full rounded-md bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 disabled:opacity-50">
                            {{ form.processing ? 'Submitting...' : 'Submit Request' }}
                        </button>
                    </form>
                </section>

                <section class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="flex flex-col gap-4 border-b border-slate-200 p-6 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Request History</h3>
                            <p class="mt-1 text-sm text-slate-500">{{ filteredRequests.length }} of {{ requests.length }} requests shown</p>
                        </div>
                        <input v-model="search" type="search" class="w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:w-72" placeholder="Search requests..." />
                    </div>

                    <div class="divide-y divide-slate-100">
                        <article v-for="request in filteredRequests" :key="request.id" class="p-6">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h4 class="font-semibold text-slate-950">{{ request.request_type }}</h4>
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold ring-1" :class="statusClass(request.status)">
                                            {{ request.status }}
                                        </span>
                                    </div>
                                    <p class="mt-2 text-sm text-slate-500">Purpose: {{ request.purpose }}</p>
                                    <p v-if="request.details" class="mt-3 text-sm leading-6 text-slate-700">{{ request.details }}</p>
                                    <p v-if="request.admin_notes" class="mt-3 rounded-md bg-slate-50 p-3 text-sm text-slate-700">
                                        Admin note: {{ request.admin_notes }}
                                    </p>
                                </div>
                                <p class="shrink-0 text-xs text-slate-400">{{ new Date(request.created_at).toLocaleDateString() }}</p>
                            </div>
                        </article>

                        <div v-if="filteredRequests.length === 0" class="px-6 py-12 text-center text-sm text-slate-500">
                            No requests found.
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
