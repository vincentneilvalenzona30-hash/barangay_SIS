<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { computed, reactive, ref } from 'vue';

const props = defineProps({
    requests: Array,
});

const search = ref('');
const notes = reactive({});

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
        request.user?.name,
        request.user?.email,
    ].filter(Boolean).join(' ').toLowerCase().includes(term));
});

const statusClass = (status) => ({
    Pending: 'bg-amber-50 text-amber-700 ring-amber-200',
    'In Review': 'bg-sky-50 text-sky-700 ring-sky-200',
    Approved: 'bg-emerald-50 text-emerald-700 ring-emerald-200',
    Released: 'bg-indigo-50 text-indigo-700 ring-indigo-200',
    Rejected: 'bg-rose-50 text-rose-700 ring-rose-200',
}[status] || 'bg-slate-50 text-slate-700 ring-slate-200');

const updateRequest = (request, status) => {
    router.patch(route('admin.requests.update', request.id), {
        status,
        admin_notes: notes[request.id] ?? request.admin_notes ?? '',
    }, {
        preserveScroll: true,
    });
};

const deleteRequest = (id) => {
    if (confirm('Delete this service request?')) {
        router.delete(route('admin.requests.destroy', id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="Service Requests" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2 class="text-2xl font-bold leading-tight text-slate-900">Service Requests</h2>
                <p class="mt-1 text-sm text-slate-500">Review user requests and update their status.</p>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <section class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="flex flex-col gap-4 border-b border-slate-200 p-6 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Request Queue</h3>
                            <p class="mt-1 text-sm text-slate-500">{{ filteredRequests.length }} of {{ requests.length }} requests shown</p>
                        </div>
                        <input v-model="search" type="search" class="w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:w-80" placeholder="Search by user, type, or status..." />
                    </div>

                    <div class="divide-y divide-slate-100">
                        <article v-for="request in filteredRequests" :key="request.id" class="p-6">
                            <div class="grid gap-5 lg:grid-cols-[1fr_320px]">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h4 class="font-semibold text-slate-950">{{ request.request_type }}</h4>
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold ring-1" :class="statusClass(request.status)">
                                            {{ request.status }}
                                        </span>
                                    </div>
                                    <p class="mt-2 text-sm text-slate-500">
                                        Requested by {{ request.user?.name }} ({{ request.user?.email }})
                                    </p>
                                    <p class="mt-3 text-sm font-medium text-slate-700">Purpose: {{ request.purpose }}</p>
                                    <p v-if="request.details" class="mt-2 text-sm leading-6 text-slate-600">{{ request.details }}</p>
                                    <p class="mt-3 text-xs text-slate-400">Submitted {{ new Date(request.created_at).toLocaleString() }}</p>
                                </div>

                                <div class="space-y-3">
                                    <textarea
                                        v-model="notes[request.id]"
                                        class="w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        rows="3"
                                        :placeholder="request.admin_notes || 'Add admin note...'"
                                    ></textarea>
                                    <select :value="request.status" @change="updateRequest(request, $event.target.value)" class="w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option>Pending</option>
                                        <option>In Review</option>
                                        <option>Approved</option>
                                        <option>Released</option>
                                        <option>Rejected</option>
                                    </select>
                                    <div class="flex gap-2">
                                        <button @click="updateRequest(request, request.status)" class="flex-1 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                                            Save Note
                                        </button>
                                        <button @click="deleteRequest(request.id)" class="rounded-md border border-rose-200 px-3 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </article>

                        <div v-if="filteredRequests.length === 0" class="px-6 py-12 text-center text-sm text-slate-500">
                            No service requests found.
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
