<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { nextTick, onMounted, ref } from 'vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const showPassword = ref(false);
const showPasswordConfirmation = ref(false);

const clearAutofill = () => {
    form.email = '';
    form.password = '';
    form.password_confirmation = '';

    nextTick(() => {
        ['register_email_entry', 'register_password_entry', 'register_password_confirmation_entry'].forEach((id) => {
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
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <div class="min-h-screen bg-emerald-50 text-emerald-950">
        <Head title="Register" />

        <div class="grid min-h-screen lg:grid-cols-[0.95fr_1.05fr]">
            <section class="relative hidden overflow-hidden bg-emerald-900 px-12 py-10 text-white lg:flex lg:flex-col lg:justify-between">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(250,204,21,0.24),transparent_34%),radial-gradient(circle_at_bottom_left,rgba(187,247,208,0.15),transparent_30%),linear-gradient(135deg,rgba(20,83,45,0.18),rgba(6,78,59,0.26))]"></div>
                <div class="absolute inset-x-0 top-0 h-28 bg-[linear-gradient(135deg,rgba(255,255,255,0.08)_25%,transparent_25%,transparent_50%,rgba(255,255,255,0.08)_50%,rgba(255,255,255,0.08)_75%,transparent_75%,transparent)] bg-[length:28px_28px]"></div>
                <div class="animate-float-slow absolute left-16 top-28 h-24 w-24 rounded-full border border-emerald-100/20 bg-emerald-100/10 shadow-2xl shadow-emerald-950/20"></div>
                <div class="animate-float-slow absolute bottom-28 right-16 h-32 w-32 rounded-full border border-yellow-200/20 bg-yellow-200/10 shadow-2xl shadow-yellow-950/10 animate-delay-200"></div>

                <div class="animate-slide-fade-up relative z-10">
                    <Link href="/" class="inline-flex items-center gap-3">
                        <ApplicationLogo class="h-12 w-12 ring-white/20" />
                        <span class="text-xl font-bold">Barangay Bacani</span>
                    </Link>
                </div>

                <div class="animate-slide-fade-up relative z-10 max-w-xl animate-delay-100">
                    <p class="mb-5 inline-flex rounded-full bg-white/10 px-4 py-2 text-sm font-medium ring-1 ring-white/15">
                        Resident portal registration
                    </p>
                    <h1 class="text-5xl font-bold leading-tight">
                        Create your Barangay Bacani portal account.
                    </h1>
                    <p class="mt-6 text-lg leading-8 text-emerald-50">
                        Register to verify your residency, submit service requests, and receive barangay updates online.
                    </p>
                </div>

                <div class="animate-slide-fade-up relative z-10 space-y-3 animate-delay-200">
                    <div class="flex items-center gap-3 rounded-lg bg-white/10 p-4 ring-1 ring-white/15 transition duration-300 hover:-translate-y-1 hover:bg-white/15">
                        <span class="flex h-9 w-9 items-center justify-center rounded-md bg-yellow-300/20 text-yellow-100">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <p class="text-sm text-emerald-50">Resident verification</p>
                    </div>
                    <div class="flex items-center gap-3 rounded-lg bg-white/10 p-4 ring-1 ring-white/15 transition duration-300 hover:-translate-y-1 hover:bg-white/15">
                        <span class="flex h-9 w-9 items-center justify-center rounded-md bg-emerald-300/20 text-emerald-100">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </span>
                        <p class="text-sm text-emerald-50">Online requests and updates</p>
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
                            <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">Create account</p>
                            <h2 class="mt-2 text-3xl font-bold text-emerald-950">Register for Barangay Bacani</h2>
                            <p class="mt-3 text-sm leading-6 text-emerald-950/60">
                                Use a Gmail address and a strong password to start using the barangay portal.
                            </p>
                        </div>

                        <form @submit.prevent="submit" class="space-y-5">
                            <div>
                                <InputLabel for="name" value="Name" />

                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-2 block w-full border-slate-300 px-4 py-3 text-slate-900 placeholder:text-slate-400"
                                    v-model="form.name"
                                    required
                                    autofocus
                                    autocomplete="name"
                                    placeholder="Type your full name"
                                />

                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div>
                                <InputLabel for="register_email_entry" value="Email" />

                                <TextInput
                                    id="register_email_entry"
                                    name="register_email_entry"
                                    type="email"
                                    class="mt-2 block w-full border-slate-300 px-4 py-3 text-slate-900 placeholder:text-slate-400"
                                    v-model="form.email"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Type your Gmail address"
                                />

                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>

                            <div>
                                <InputLabel for="register_password_entry" value="Password" />

                                <TextInput
                                    id="register_password_entry"
                                    name="register_password_entry"
                                    :type="showPassword ? 'text' : 'password'"
                                    class="mt-2 block w-full border-slate-300 px-4 py-3 text-slate-900 placeholder:text-slate-400"
                                    v-model="form.password"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Type your password"
                                />

                                <p class="mt-2 text-xs leading-5 text-slate-500">
                                    Use uppercase and lowercase letters, a number, and a symbol.
                                </p>
                                <label class="mt-3 flex items-center text-sm text-slate-600">
                                    <input
                                        v-model="showPassword"
                                        type="checkbox"
                                        class="rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-emerald-500"
                                    />
                                    <span class="ms-2">Show password</span>
                                </label>
                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>

                            <div>
                                <InputLabel for="register_password_confirmation_entry" value="Confirm Password" />

                                <TextInput
                                    id="register_password_confirmation_entry"
                                    name="register_password_confirmation_entry"
                                    :type="showPasswordConfirmation ? 'text' : 'password'"
                                    class="mt-2 block w-full border-slate-300 px-4 py-3 text-slate-900 placeholder:text-slate-400"
                                    v-model="form.password_confirmation"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Type your password again"
                                />

                                <label class="mt-3 flex items-center text-sm text-slate-600">
                                    <input
                                        v-model="showPasswordConfirmation"
                                        type="checkbox"
                                        class="rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-emerald-500"
                                    />
                                    <span class="ms-2">Show password</span>
                                </label>

                                <InputError class="mt-2" :message="form.errors.password_confirmation" />
                            </div>

                            <button
                                type="submit"
                                class="button-sheen flex w-full items-center justify-center rounded-md bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-200 transition hover:-translate-y-0.5 hover:bg-emerald-700 hover:shadow-xl hover:shadow-emerald-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                :disabled="form.processing"
                            >
                                <span v-if="form.processing" class="mr-2 h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                                {{ form.processing ? 'Creating account...' : 'Register' }}
                            </button>
                        </form>

                        <p class="mt-7 text-center text-sm text-slate-600">
                            Already registered?
                            <Link :href="route('login')" class="font-semibold text-emerald-700 hover:text-emerald-900">
                                Log in here
                            </Link>
                        </p>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
