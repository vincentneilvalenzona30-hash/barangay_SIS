<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    stats: Object,
    recentRequests: Array,
    verification: Object,
});

const statusClass = (status) => ({
    Pending: 'bg-amber-50 text-amber-700 ring-amber-200',
    'In Review': 'bg-sky-50 text-sky-700 ring-sky-200',
    Approved: 'bg-emerald-50 text-emerald-700 ring-emerald-200',
    Released: 'bg-indigo-50 text-indigo-700 ring-indigo-200',
    Rejected: 'bg-rose-50 text-rose-700 ring-rose-200',
}[status] || 'bg-slate-50 text-slate-700 ring-slate-200');

const verificationClass = (status) => ({
    Submitted: 'bg-sky-50 text-sky-700 ring-sky-200',
    Verified: 'bg-emerald-50 text-emerald-700 ring-emerald-200',
    Rejected: 'bg-rose-50 text-rose-700 ring-rose-200',
}[status] || 'bg-amber-50 text-amber-700 ring-amber-200');
</script>

<template>
    <Head title="User Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-2xl font-bold leading-tight text-slate-900">User Dashboard</h2>
                    <p class="mt-1 text-sm text-slate-500">Request barangay documents and track your submissions.</p>
                </div>
                <Link :href="route('user.requests.index')" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                    Submit Request
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
                <section class="rounded-lg border border-slate-200 bg-white p-8 shadow-sm">
                    <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">Resident portal</p>
                    <h1 class="mt-3 text-3xl font-bold text-slate-950">Welcome, {{ $page.props.auth.user.name }}</h1>
                    <p class="mt-4 max-w-3xl text-base leading-7 text-slate-600">
                        Use this portal to send barangay service requests, monitor status updates, and keep your account details current.
                    </p>

                    <div class="mt-8 grid gap-4 sm:grid-cols-3">
                        <div class="rounded-lg bg-indigo-50 p-5 ring-1 ring-indigo-100">
                            <p class="text-sm font-semibold text-indigo-700">Total Requests</p>
                            <p class="mt-2 text-3xl font-bold text-indigo-950">{{ stats.totalRequests }}</p>
                        </div>
                        <div class="rounded-lg bg-amber-50 p-5 ring-1 ring-amber-100">
                            <p class="text-sm font-semibold text-amber-700">Pending</p>
                            <p class="mt-2 text-3xl font-bold text-amber-950">{{ stats.pendingRequests }}</p>
                        </div>
                        <div class="rounded-lg bg-emerald-50 p-5 ring-1 ring-emerald-100">
                            <p class="text-sm font-semibold text-emerald-700">Approved/Released</p>
                            <p class="mt-2 text-3xl font-bold text-emerald-950">{{ stats.approvedRequests }}</p>
                        </div>
                    </div>
                </section>

                <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Resident Legitimacy Verification</h3>
                            <p class="mt-1 text-sm text-slate-500">Submit your barangay information, valid ID, and face capture before requesting sensitive services.</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="rounded-full px-3 py-1 text-xs font-semibold ring-1" :class="verificationClass(verification?.status)">
                                {{ verification?.status || 'Not submitted' }}
                            </span>
                            <Link :href="route('user.verification.show')" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                                {{ verification ? 'View Verification' : 'Start Verification' }}
                            </Link>
                        </div>
                    </div>
                </section>

                <section class="grid gap-6 lg:grid-cols-[0.64fr_0.36fr]">
                    <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-200 px-6 py-4">
                            <h3 class="text-lg font-semibold text-slate-900">Recent Requests</h3>
                        </div>
                        <div class="divide-y divide-slate-100">
                            <div v-for="request in recentRequests" :key="request.id" class="flex flex-col gap-3 px-6 py-4 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ request.request_type }}</p>
                                    <p class="mt-1 text-sm text-slate-500">{{ request.purpose }}</p>
                                    <p v-if="request.admin_notes" class="mt-2 text-sm text-slate-600">Admin note: {{ request.admin_notes }}</p>
                                </div>
                                <span class="w-fit rounded-full px-3 py-1 text-xs font-semibold ring-1" :class="statusClass(request.status)">
                                    {{ request.status }}
                                </span>
                            </div>
                            <div v-if="recentRequests.length === 0" class="px-6 py-10 text-center text-sm text-slate-500">
                                No requests yet. Start by submitting your first barangay service request.
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-slate-900">Available Services</h3>
                            <div class="mt-4 space-y-3 text-sm text-slate-600">
                                <p class="rounded-md bg-slate-50 p-3">Barangay Clearance</p>
                                <p class="rounded-md bg-slate-50 p-3">Certificate of Residency</p>
                                <p class="rounded-md bg-slate-50 p-3">Certificate of Indigency</p>
                                <p class="rounded-md bg-slate-50 p-3">Incident Assistance</p>
                            </div>
                        </div>

                        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-slate-900">Account</h3>
                            <p class="mt-2 text-sm text-slate-500">Keep your name, email, and password up to date.</p>
                            <Link :href="route('profile.edit')" class="mt-4 inline-flex rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Manage Profile
                            </Link>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
