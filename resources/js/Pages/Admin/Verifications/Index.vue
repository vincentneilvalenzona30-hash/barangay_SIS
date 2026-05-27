<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { computed, reactive, ref } from 'vue';

const props = defineProps({
    verifications: Array,
});

const search = ref('');
const notes = reactive({});

const filteredVerifications = computed(() => {
    const term = search.value.toLowerCase().trim();

    if (!term) {
        return props.verifications;
    }

    return props.verifications.filter((verification) => [
        verification.first_name,
        verification.middle_name,
        verification.last_name,
        verification.address,
        verification.purok,
        verification.valid_id_type,
        verification.valid_id_number,
        verification.status,
        verification.user?.email,
    ].filter(Boolean).join(' ').toLowerCase().includes(term));
});

const statusClass = (status) => ({
    Submitted: 'bg-sky-50 text-sky-700 ring-sky-200',
    Verified: 'bg-emerald-50 text-emerald-700 ring-emerald-200',
    Rejected: 'bg-rose-50 text-rose-700 ring-rose-200',
}[status] || 'bg-slate-50 text-slate-700 ring-slate-200');

const updateVerification = (verification, status) => {
    router.patch(route('admin.verifications.update', verification.id), {
        status,
        admin_notes: notes[verification.id] ?? verification.admin_notes ?? '',
    }, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Resident Verifications" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2 class="text-2xl font-bold leading-tight text-slate-900">Resident Verifications</h2>
                <p class="mt-1 text-sm text-slate-500">Review valid IDs, face captures, and barangay residency information.</p>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <section class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="flex flex-col gap-4 border-b border-slate-200 p-6 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Verification Queue</h3>
                            <p class="mt-1 text-sm text-slate-500">{{ filteredVerifications.length }} of {{ verifications.length }} records shown</p>
                        </div>
                        <input v-model="search" type="search" class="w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:w-80" placeholder="Search resident verification..." />
                    </div>

                    <div class="divide-y divide-slate-100">
                        <article v-for="verification in filteredVerifications" :key="verification.id" class="p-6">
                            <div class="grid gap-6 xl:grid-cols-[1fr_360px]">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h4 class="text-lg font-semibold text-slate-950">
                                            {{ verification.last_name }}, {{ verification.first_name }} {{ verification.middle_name || '' }}
                                        </h4>
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold ring-1" :class="statusClass(verification.status)">
                                            {{ verification.status }}
                                        </span>
                                    </div>
                                    <p class="mt-2 text-sm text-slate-500">{{ verification.user?.email }} • {{ verification.contact_number }}</p>

                                    <dl class="mt-5 grid gap-4 text-sm sm:grid-cols-2">
                                        <div class="rounded-md bg-slate-50 p-3">
                                            <dt class="font-semibold text-slate-700">Address</dt>
                                            <dd class="mt-1 text-slate-600">{{ verification.address }}</dd>
                                        </div>
                                        <div class="rounded-md bg-slate-50 p-3">
                                            <dt class="font-semibold text-slate-700">Purok / Residency</dt>
                                            <dd class="mt-1 text-slate-600">{{ verification.purok || 'N/A' }} • {{ verification.years_of_residency || 'N/A' }}</dd>
                                        </div>
                                        <div class="rounded-md bg-slate-50 p-3">
                                            <dt class="font-semibold text-slate-700">Birth / Gender / Civil Status</dt>
                                            <dd class="mt-1 text-slate-600">{{ verification.birth_date }} • {{ verification.gender }} • {{ verification.civil_status }}</dd>
                                        </div>
                                        <div class="rounded-md bg-slate-50 p-3">
                                            <dt class="font-semibold text-slate-700">Valid ID</dt>
                                            <dd class="mt-1 text-slate-600">{{ verification.valid_id_type }} • {{ verification.valid_id_number || 'No ID number' }}</dd>
                                        </div>
                                    </dl>

                                    <div class="mt-5 grid gap-4 md:grid-cols-2">
                                        <a :href="verification.valid_id_url" target="_blank" class="block overflow-hidden rounded-lg border border-slate-200 bg-slate-50">
                                            <img v-if="!verification.valid_id_url.endsWith('.pdf')" :src="verification.valid_id_url" class="aspect-video w-full object-cover" alt="Valid ID" />
                                            <div v-else class="flex aspect-video items-center justify-center text-sm font-semibold text-slate-600">Open PDF Valid ID</div>
                                        </a>
                                        <a :href="verification.face_photo_url" target="_blank" class="block overflow-hidden rounded-lg border border-slate-200 bg-slate-50">
                                            <img :src="verification.face_photo_url" class="aspect-video w-full object-cover" alt="Face capture" />
                                        </a>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <label class="text-sm font-medium text-slate-700">Admin Notes</label>
                                    <textarea
                                        v-model="notes[verification.id]"
                                        class="w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        rows="5"
                                        :placeholder="verification.admin_notes || 'Add review notes...'"
                                    ></textarea>
                                    <select :value="verification.status" @change="updateVerification(verification, $event.target.value)" class="w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option>Submitted</option>
                                        <option>Verified</option>
                                        <option>Rejected</option>
                                    </select>
                                    <div class="grid grid-cols-2 gap-2">
                                        <button @click="updateVerification(verification, 'Verified')" class="rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                                            Verify
                                        </button>
                                        <button @click="updateVerification(verification, 'Rejected')" class="rounded-md bg-rose-600 px-3 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                                            Reject
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </article>

                        <div v-if="filteredVerifications.length === 0" class="px-6 py-12 text-center text-sm text-slate-500">
                            No verification submissions found.
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
