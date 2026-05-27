<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed, reactive } from 'vue';

const props = defineProps({
    residents: Array,
});

const certificate = reactive({
    resident_id: '',
    type: 'Barangay Clearance',
    purpose: 'Employment requirements',
    control_number: `BC-${new Date().getFullYear()}-${String(Date.now()).slice(-5)}`,
    issued_by: 'Barangay Secretary',
});

const selectedResident = computed(() => props.residents.find((resident) => resident.id === Number(certificate.resident_id)));

const issuedDate = computed(() => new Date().toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
}));

const documentStatement = computed(() => {
    if (!selectedResident.value) {
        return '';
    }

    const name = `${selectedResident.value.first_name} ${selectedResident.value.last_name}`;
    const address = selectedResident.value.address;

    if (certificate.type === 'Certificate of Residency') {
        return `This is to certify that ${name} is a registered resident of ${address}. Based on barangay records, the above-named person resides within the jurisdiction of Barangay Bacani, Clarin, Bohol.`;
    }

    if (certificate.type === 'Certificate of Indigency') {
        return `This is to certify that ${name}, a resident of ${address}, is known to this barangay and may request this certification for assistance, scholarship, medical, legal, or other lawful support purposes.`;
    }

    return `This is to certify that ${name}, a ${selectedResident.value.gender} resident with civil status of ${selectedResident.value.civil_status}, is a registered resident of ${address}. This document is issued as a barangay clearance based on the available barangay record.`;
});

const printCertificate = () => {
    window.print();
};
</script>

<template>
    <Head title="Clearances" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2 class="text-2xl font-bold leading-tight text-slate-900">Clearances & Certificates</h2>
                <p class="mt-1 text-sm text-slate-500">Generate printable barangay documents from resident records.</p>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-[0.34fr_0.66fr] lg:px-8">
                <section class="no-print h-fit rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">Document Details</h3>
                    <p class="mt-1 text-sm text-slate-500">Select a resident and fill in the certificate purpose.</p>

                    <div class="mt-6 space-y-4">
                        <div>
                            <label class="text-sm font-medium text-slate-700">Resident</label>
                            <select v-model="certificate.resident_id" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="" disabled>Select resident</option>
                                <option v-for="resident in residents" :key="resident.id" :value="resident.id">
                                    {{ resident.last_name }}, {{ resident.first_name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Document Type</label>
                            <select v-model="certificate.type" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option>Barangay Clearance</option>
                                <option>Certificate of Residency</option>
                                <option>Certificate of Indigency</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Purpose</label>
                            <input v-model="certificate.purpose" type="text" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Control Number</label>
                            <input v-model="certificate.control_number" type="text" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Issued By</label>
                            <input v-model="certificate.issued_by" type="text" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>

                        <button @click="printCertificate" :disabled="!selectedResident" class="w-full rounded-md bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50">
                            Print Document
                        </button>
                    </div>
                </section>

                <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm print-area">
                    <div class="min-h-[720px] border-4 border-double border-slate-700 p-8 text-center">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-indigo-600 text-white">
                            <svg class="h-9 w-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>

                        <p class="mt-4 text-sm uppercase tracking-wide text-slate-600">Republic of the Philippines</p>
                        <p class="text-sm uppercase tracking-wide text-slate-600">Office of the Barangay</p>
                        <h1 class="mt-8 text-3xl font-bold uppercase tracking-wide text-slate-950">{{ certificate.type }}</h1>
                        <p class="mt-2 text-sm text-slate-500">Control No. {{ certificate.control_number }}</p>

                        <div v-if="selectedResident" class="mx-auto mt-12 max-w-2xl text-left text-base leading-8 text-slate-800">
                            <p>{{ documentStatement }}</p>

                            <p class="mt-6">
                                This certification is issued upon request of the above-named resident for
                                <strong>{{ certificate.purpose }}</strong> and for whatever lawful purpose it may serve.
                            </p>

                            <p class="mt-6">
                                Issued this {{ issuedDate }} at the Barangay Office.
                            </p>
                        </div>

                        <div v-else class="mt-20 rounded-lg bg-slate-50 p-8 text-sm text-slate-500">
                            Select a resident to preview the document.
                        </div>

                        <div class="mt-20 grid grid-cols-2 gap-10 text-sm text-slate-700">
                            <div>
                                <div class="border-t border-slate-400 pt-2">Resident Signature</div>
                            </div>
                            <div>
                                <div class="border-t border-slate-400 pt-2">{{ certificate.issued_by }}</div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
@media print {
    body * {
        visibility: hidden;
    }

    .print-area,
    .print-area * {
        visibility: visible;
    }

    .print-area {
        position: absolute;
        inset: 0;
        border: 0 !important;
        box-shadow: none !important;
        padding: 0 !important;
    }

    .no-print {
        display: none !important;
    }
}
</style>
