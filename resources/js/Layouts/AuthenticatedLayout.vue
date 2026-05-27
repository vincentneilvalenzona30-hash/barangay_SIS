<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, router, usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const isNavigating = ref(false);
const page = usePage();

const adminNavItems = [
    { label: 'Dashboard', routeName: 'dashboard' },
    { label: 'Requests', routeName: 'admin.requests.index' },
    { label: 'Verifications', routeName: 'admin.verifications.index' },
    { label: 'News & Events', routeName: 'admin.news-events.index' },
    { label: 'Residents', routeName: 'residents.index' },
    { label: 'Blotters', routeName: 'blotters.index' },
    { label: 'Clearances', routeName: 'clearances.index' },
    { label: 'Reports', routeName: 'reports.index' },
];

const userNavItems = [
    { label: 'Dashboard', routeName: 'dashboard' },
    { label: 'News & Events', routeName: 'news-events' },
    { label: 'Verification', routeName: 'user.verification.show' },
    { label: 'My Requests', routeName: 'user.requests.index' },
];

const navItems = computed(() => page.props.auth.user?.is_admin ? adminNavItems : userNavItems);

let removeStartListener;
let removeFinishListener;

onMounted(() => {
    removeStartListener = router.on('start', () => {
        isNavigating.value = true;
    });

    removeFinishListener = router.on('finish', () => {
        window.setTimeout(() => {
            isNavigating.value = false;
        }, 180);
    });
});

onUnmounted(() => {
    removeStartListener?.();
    removeFinishListener?.();
});
</script>

<template>
    <div>
        <div class="min-h-screen bg-emerald-50 text-emerald-950">
            <Transition name="route-loader">
                <div
                    v-if="isNavigating"
                    class="fixed inset-x-0 top-0 z-[70] h-1 overflow-hidden bg-emerald-100"
                >
                    <div class="route-loader-bar h-full w-1/2 bg-gradient-to-r from-emerald-500 via-lime-400 to-yellow-300"></div>
                </div>
            </Transition>

            <nav
                class="relative z-50 border-b border-emerald-100 bg-white/95 shadow-sm backdrop-blur"
            >
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center">
                                <Link :href="route('dashboard')" class="flex items-center gap-3">
                                    <ApplicationLogo class="h-10 w-10" />
                                    <span class="hidden text-base font-bold text-slate-900 lg:block">Barangay SIS</span>
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div
                                class="hidden space-x-8 xl:-my-px xl:ms-10 xl:flex"
                            >
                                <NavLink
                                    v-for="item in navItems"
                                    :key="item.routeName"
                                    :href="route(item.routeName)"
                                    :active="route().current(item.routeName)"
                                >
                                    {{ item.label }}
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden xl:ms-6 xl:flex xl:items-center">
                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
                                            >
                                                <span class="text-left">
                                                    <span class="block">{{ $page.props.auth.user.name }}</span>
                                                    <span class="block text-xs capitalize text-slate-400">{{ $page.props.auth.user.role }}</span>
                                                </span>

                                                <svg
                                                    class="-me-0.5 ms-2 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                        >
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center xl:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="xl:hidden"
                >
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink
                            v-for="item in navItems"
                            :key="item.routeName"
                            :href="route(item.routeName)"
                            :active="route().current(item.routeName)"
                        >
                            {{ item.label }}
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div
                        class="border-t border-gray-200 pb-1 pt-4"
                    >
                        <div class="px-4">
                            <div
                                class="text-base font-medium text-gray-800"
                            >
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-500">
                                {{ $page.props.auth.user.email }}
                            </div>
                            <div class="mt-1 text-xs font-semibold uppercase tracking-wide text-indigo-600">
                                {{ $page.props.auth.user.role }} account
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header
                class="bg-white/95 shadow-sm backdrop-blur"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="relative z-0 overflow-hidden bg-gradient-to-b from-emerald-100 via-lime-50 to-white">
                <div class="pointer-events-none absolute left-0 top-12 h-64 w-64 rounded-full bg-emerald-200/50 blur-3xl"></div>
                <div class="pointer-events-none absolute bottom-10 right-0 h-72 w-72 rounded-full bg-yellow-100/70 blur-3xl"></div>
                <div class="pointer-events-none absolute inset-x-0 top-0 h-24 bg-[linear-gradient(135deg,rgba(22,163,74,0.12)_25%,transparent_25%,transparent_50%,rgba(22,163,74,0.12)_50%,rgba(22,163,74,0.12)_75%,transparent_75%,transparent)] bg-[length:28px_28px] opacity-60"></div>

                <div class="relative">
                    <Transition name="page-swap" mode="out-in">
                        <div :key="page.url" class="page-refresh-enter">
                            <slot />
                        </div>
                    </Transition>
                </div>
            </main>
        </div>
    </div>
</template>
