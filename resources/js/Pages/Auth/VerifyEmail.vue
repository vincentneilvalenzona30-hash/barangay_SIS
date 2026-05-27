<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);

const welcomeRegistration = computed(
    () => props.status === 'welcome-registration',
);
</script>

<template>
    <GuestLayout>
        <Head title="Email Verification" />

        <div class="space-y-6">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">
                    Email verification
                </p>
                <h1 class="mt-2 text-2xl font-bold text-slate-950">
                    Check your Gmail inbox
                </h1>
                <p class="mt-3 text-sm leading-6 text-slate-600">
                    Open the verification email from Barangay SIS and click
                    <strong>Verify Email Address</strong>. After verification, you can continue to resident
                    verification, document requests, and barangay services.
                </p>
            </div>

            <div
                v-if="welcomeRegistration"
                class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"
            >
                Welcome to Barangay SIS. Your account was created successfully.
            </div>

            <div
                v-if="verificationLinkSent"
                class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800"
            >
                A new verification email has been sent to your Gmail account.
            </div>

            <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                <p class="text-sm font-semibold text-slate-900">Before resending</p>
                <ul class="mt-3 space-y-2 text-sm leading-6 text-slate-600">
                    <li>Check your Spam or Promotions folder.</li>
                    <li>Use the latest verification email if you requested more than one.</li>
                    <li>Keep this page open, then return after clicking the email link.</li>
                </ul>
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <button
                    type="submit"
                    class="flex w-full items-center justify-center rounded-md bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60"
                    :disabled="form.processing"
                >
                    {{ form.processing ? 'Sending...' : 'Resend Verification Email' }}
                </button>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="flex w-full items-center justify-center rounded-md border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                >
                    Log Out
                </Link>
            </form>
        </div>
    </GuestLayout>
</template>
