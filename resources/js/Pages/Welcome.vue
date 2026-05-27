<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="Barangay Bacani" />

    <div class="min-h-screen bg-emerald-50 flex flex-col">
        <nav class="bg-white/95 shadow-sm backdrop-blur">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex min-h-16 flex-wrap items-center justify-between gap-3 py-3">
                    <div class="flex min-w-0 items-center">
                        <div class="flex min-w-0 flex-shrink-0 items-center gap-2">
                            <ApplicationLogo class="w-10 h-10" />
                            <span class="truncate text-base font-bold text-emerald-950 sm:text-xl">Barangay Bacani</span>
                        </div>
                    </div>
                    <div class="flex min-w-0 items-center justify-end">
                        <div v-if="canLogin" class="flex flex-wrap items-center justify-end gap-3 text-sm sm:text-base">
                            <Link href="/news-events" class="text-gray-600 hover:text-emerald-700 font-medium">
                                News & Events
                            </Link>
                            <Link v-if="$page.props.auth?.user" href="/residents" class="text-gray-600 hover:text-emerald-700 font-medium">
                                Dashboard
                            </Link>

                            <template v-else>
                                <Link href="/login" class="text-gray-600 hover:text-emerald-700 font-medium">
                                    Log in
                                </Link>
                                <Link v-if="canRegister" href="/register" class="bg-emerald-600 text-white px-4 py-2 rounded-md font-medium hover:bg-emerald-700 transition">
                                    Register
                                </Link>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main class="relative flex-grow overflow-hidden bg-gradient-to-b from-emerald-100 via-lime-50 to-white px-4 sm:px-6 lg:px-8">
            <div class="absolute left-0 top-20 h-64 w-64 rounded-full bg-emerald-200/50 blur-3xl"></div>
            <div class="absolute bottom-10 right-0 h-72 w-72 rounded-full bg-yellow-100/70 blur-3xl"></div>
            <div class="absolute inset-x-0 top-0 h-24 bg-[linear-gradient(135deg,rgba(22,163,74,0.12)_25%,transparent_25%,transparent_50%,rgba(22,163,74,0.12)_50%,rgba(22,163,74,0.12)_75%,transparent_75%,transparent)] bg-[length:28px_28px] opacity-60"></div>

            <div class="relative max-w-5xl mx-auto py-16 sm:py-24">
                <div class="text-center">
                    <div class="mx-auto mb-6 flex h-28 w-28 items-center justify-center rounded-full bg-white p-2 shadow-xl shadow-emerald-900/10 ring-4 ring-emerald-200">
                        <ApplicationLogo class="h-full w-full ring-0" />
                    </div>
                    <p class="inline-flex rounded-full bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-800 ring-1 ring-emerald-200">
                        Bacani, Clarin, Bohol
                    </p>
                    <h1 class="mt-6 text-3xl sm:text-5xl md:text-6xl font-extrabold text-emerald-950 tracking-tight">
                        Welcome to <br />
                        <span class="text-emerald-700">Barangay Bacani</span>
                    </h1>
                    <p class="mt-6 text-base leading-7 text-emerald-950/70 max-w-3xl mx-auto sm:text-xl sm:leading-8">
                        Barangay Bacani is a community in the Municipality of Clarin, Province of Bohol, built on cooperation,
                        public service, and the shared goal of making local services more accessible to every resident.
                    </p>
                </div>

                <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                    <Link href="/login" class="w-full rounded-lg bg-emerald-600 px-8 py-3 text-center text-base font-bold text-white shadow-lg shadow-emerald-600/20 transition hover:bg-emerald-700 sm:w-auto sm:text-lg">
                        Access Portal
                    </Link>
                    <a href="#features" class="w-full rounded-lg border border-emerald-200 bg-white px-8 py-3 text-center text-base font-bold text-emerald-700 shadow-sm transition hover:bg-emerald-50 sm:w-auto sm:text-lg">
                        Learn About Bacani
                    </a>
                    <Link href="/news-events" class="w-full rounded-lg border border-yellow-400 bg-yellow-300 px-8 py-3 text-center text-base font-bold text-emerald-950 shadow-sm transition hover:bg-yellow-200 sm:w-auto sm:text-lg">
                        News & Events
                    </Link>
                </div>
            </div>
        </main>

        <div id="features" class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-3xl text-center">
                    <h2 class="text-3xl font-bold text-emerald-950">About Barangay Bacani</h2>
                    <p class="mt-4 text-lg leading-8 text-emerald-950/70">
                        Barangay Bacani serves as a home for families, workers, students, and community members who value
                        unity, safety, and dependable barangay service. This portal supports the barangay by providing a
                        clearer way to manage resident verification, requests, records, and local assistance.
                    </p>
                </div>

                <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="p-6 bg-emerald-50 rounded-xl border border-emerald-100">
                        <div class="w-12 h-12 bg-emerald-100 text-emerald-700 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21s7-4.438 7-11a7 7 0 10-14 0c0 6.562 7 11 7 11z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10.5h.01" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-emerald-950 mb-2">Location</h3>
                        <p class="text-emerald-950/70">Barangay Bacani is located in Clarin, Bohol, serving residents through local governance and community-centered services.</p>
                    </div>

                    <div class="p-6 bg-lime-50 rounded-xl border border-lime-100">
                        <div class="w-12 h-12 bg-lime-100 text-emerald-700 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-emerald-950 mb-2">Community</h3>
                        <p class="text-emerald-950/70">The barangay promotes cooperation, respect, safety, and active participation among residents and local leaders.</p>
                    </div>

                    <div class="p-6 bg-yellow-50 rounded-xl border border-yellow-100">
                        <div class="w-12 h-12 bg-yellow-100 text-emerald-700 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-emerald-950 mb-2">Barangay Services</h3>
                        <p class="text-emerald-950/70">Residents can use the system for verification, document requests, assistance tracking, and official barangay transactions.</p>
                    </div>
                </div>

                <div class="mt-12 rounded-xl bg-gradient-to-r from-emerald-700 to-green-600 p-8 text-center shadow-xl shadow-emerald-900/10">
                    <h3 class="text-2xl font-bold text-white">Serving Bacani with Accessible Local Service</h3>
                    <p class="mx-auto mt-4 max-w-4xl text-emerald-50 leading-8">
                        This website is designed to help Barangay Bacani provide faster, more organized, and more transparent
                        service to its residents. It supports resident verification, online requests, record management, and
                        communication between the barangay office and the people it serves.
                    </p>
                    <p class="mt-6 font-semibold text-yellow-200">Barangay Bacani, Clarin, Bohol</p>
                </div>
            </div>
        </div>

        <footer class="bg-emerald-950 text-white py-8 text-center">
            <p class="text-emerald-100/80">&copy; {{ new Date().getFullYear() }} Barangay Bacani, Clarin, Bohol. All rights reserved.</p>
        </footer>
    </div>
</template>
