<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, nextTick, onMounted, ref } from 'vue';

const props = defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);

const statusMessage = computed(() => {
    if (props.status === 'registration-success') {
        return 'You are now registered in Barangay SIS. You may now log in to continue.';
    }

    return props.status;
});

const statusTitle = computed(() => (
    props.status === 'registration-success' ? 'Registration successful' : 'Notice'
));

const isRegistrationSuccess = computed(() => props.status === 'registration-success');


const clearAutofill = () => {
    form.email = '';
    form.password = '';

    nextTick(() => {
        ['login_email_entry', 'login_password_entry'].forEach((id) => {
            const input = document.getElementById(id);

            if (input) {
                input.value = '';
                input.dispatchEvent(new Event('input', { bubbles: true }));
            }
        });
    });
};

onMounted(() => {
    clearAutofill();
    window.setTimeout(clearAutofill, 150);
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <div class="min-h-screen bg-emerald-50 text-emerald-950">
        <Head title="Log in" />

        <div class="grid min-h-screen lg:grid-cols-[1.05fr_0.95fr]">
            <section class="relative hidden overflow-hidden bg-emerald-800 px-12 py-10 text-white lg:flex lg:flex-col lg:justify-between">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(250,204,21,0.22),transparent_30%),radial-gradient(circle_at_bottom_right,rgba(187,247,208,0.18),transparent_34%),linear-gradient(135deg,rgba(6,78,59,0.18),rgba(22,101,52,0.22))]"></div>
                <div class="absolute inset-x-0 top-0 h-28 bg-[linear-gradient(135deg,rgba(255,255,255,0.08)_25%,transparent_25%,transparent_50%,rgba(255,255,255,0.08)_50%,rgba(255,255,255,0.08)_75%,transparent_75%,transparent)] bg-[length:28px_28px]"></div>
                <div class="animate-float-slow absolute right-14 top-24 h-28 w-28 rounded-full border border-white/20 bg-white/10 shadow-2xl shadow-emerald-950/20"></div>
                <div class="animate-float-slow absolute bottom-32 left-16 h-20 w-20 rounded-full border border-yellow-200/20 bg-yellow-200/10 shadow-2xl shadow-yellow-900/10 animate-delay-200"></div>

                <div class="animate-slide-fade-up relative z-10">
                    <Link href="/" class="inline-flex items-center gap-3">
                        <ApplicationLogo class="h-12 w-12 ring-white/25" />
                        <span class="text-xl font-bold">Barangay Bacani</span>
                    </Link>
                </div>

                <div class="animate-slide-fade-up relative z-10 max-w-xl animate-delay-100">
                    <p class="mb-5 inline-flex rounded-full bg-white/15 px-4 py-2 text-sm font-medium ring-1 ring-white/20">
                        Barangay Bacani online portal
                    </p>
                    <h1 class="text-5xl font-bold leading-tight">
                        Welcome back to Barangay Bacani.
                    </h1>
                    <p class="mt-6 text-lg leading-8 text-emerald-50">
                        Continue to resident verification, service requests, announcements, and barangay transactions for Bacani, Clarin, Bohol.
                    </p>
                </div>

                <div class="animate-slide-fade-up relative z-10 grid grid-cols-3 gap-3 animate-delay-200">
                    <div class="rounded-lg bg-white/12 p-4 ring-1 ring-white/15 transition duration-300 hover:-translate-y-1 hover:bg-white/16">
                        <p class="text-2xl font-bold">24/7</p>
                        <p class="mt-1 text-sm text-emerald-50">Portal access</p>
                    </div>
                    <div class="rounded-lg bg-white/12 p-4 ring-1 ring-white/15 transition duration-300 hover:-translate-y-1 hover:bg-white/16">
                        <p class="text-2xl font-bold">Fast</p>
                        <p class="mt-1 text-sm text-emerald-50">Requests</p>
                    </div>
                    <div class="rounded-lg bg-white/12 p-4 ring-1 ring-white/15 transition duration-300 hover:-translate-y-1 hover:bg-white/16">
                        <p class="text-2xl font-bold">Clear</p>
                        <p class="mt-1 text-sm text-emerald-50">Updates</p>
                    </div>
                </div>
            </section>

            <main class="flex items-center justify-center px-5 py-10 sm:px-8">
                <div class="w-full max-w-md">
                    <div class="mb-8 lg:hidden">
                        <Link href="/" class="inline-flex items-center gap-3">
                            <ApplicationLogo class="h-11 w-11" />
                            <span class="text-lg font-bold text-emerald-950">Barangay Bacani</span>
                        </Link>
                    </div>

                    <div class="animate-slide-fade-up rounded-lg border border-emerald-100 bg-white p-6 shadow-xl shadow-emerald-900/10 transition duration-300 hover:shadow-2xl hover:shadow-emerald-900/15 sm:p-8">
                        <div class="mb-7">
                            <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">Sign in</p>
                            <h2 class="mt-2 text-3xl font-bold text-emerald-950">Log in to your account</h2>
                            <p class="mt-3 text-sm leading-6 text-emerald-950/60">
                                Use your registered email and password to continue.
                            </p>
                        </div>

                        <div
                            v-if="statusMessage"
                            class="mb-5 rounded-md px-4 py-3 text-sm font-medium shadow-sm"
                            :class="isRegistrationSuccess ? 'border border-emerald-300 bg-emerald-50 text-emerald-800 ring-1 ring-emerald-100' : 'border border-emerald-200 bg-emerald-50 text-emerald-700'"
                        >
                            <p class="font-semibold">{{ statusTitle }}</p>
                            <p class="mt-1 leading-6">{{ statusMessage }}</p>
                        </div>

                        <form @submit.prevent="submit" class="space-y-5">
                            <div>
                                <InputLabel for="login_email_entry" value="Email" />

                                <TextInput
                                    id="login_email_entry"
                                    name="login_email_entry"
                                    type="email"
                                    class="mt-2 block w-full border-slate-300 px-4 py-3 text-slate-900 placeholder:text-slate-400"
                                    v-model="form.email"
                                    required
                                    autofocus
                                    autocomplete="new-password"
                                    placeholder="Type your email"
                                />

                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>

                            <div>
                                <InputLabel for="login_password_entry" value="Password" />

                                <TextInput
                                    id="login_password_entry"
                                    name="login_password_entry"
                                    :type="showPassword ? 'text' : 'password'"
                                    class="mt-2 block w-full border-slate-300 px-4 py-3 text-slate-900 placeholder:text-slate-400"
                                    v-model="form.password"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Type your password"
                                />

                                <label class="mt-3 flex items-center text-sm text-slate-600">
                                    <Checkbox v-model:checked="showPassword" />
                                    <span class="ms-2">Show password</span>
                                </label>

                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <label class="flex items-center">
                                    <Checkbox name="remember" v-model:checked="form.remember" />
                                    <span class="ms-2 text-sm text-slate-600">Remember me</span>
                                </label>

                                <Link
                                    v-if="canResetPassword"
                                    :href="route('password.request')"
                                    class="text-sm font-medium text-emerald-700 hover:text-emerald-900"
                                >
                                    Forgot password?
                                </Link>
                            </div>

                            <button
                                type="submit"
                                class="button-sheen flex w-full items-center justify-center rounded-md bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-200 transition hover:-translate-y-0.5 hover:bg-emerald-700 hover:shadow-xl hover:shadow-emerald-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                :disabled="form.processing"
                            >
                                <span v-if="form.processing" class="mr-2 h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                                {{ form.processing ? 'Logging in...' : 'Log in' }}
                            </button>
                        </form>

                        <p class="mt-7 text-center text-sm text-slate-600">
                            Need an account?
                            <Link :href="route('register')" class="font-semibold text-emerald-700 hover:text-emerald-900">
                                Register here
                            </Link>
                        </p>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
