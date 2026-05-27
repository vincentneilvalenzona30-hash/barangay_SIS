<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    residents: Array,
});

const search = ref('');
const editingResidentId = ref(null);

const form = useForm({
    first_name: '',
    last_name: '',
    birth_date: '',
    gender: '',
    civil_status: 'Single',
    address: '',
    contact_number: '',
});

const editForm = useForm({
    first_name: '',
    last_name: '',
    birth_date: '',
    gender: '',
    civil_status: 'Single',
    address: '',
    contact_number: '',
});

const filteredResidents = computed(() => {
    const term = search.value.toLowerCase().trim();

    if (!term) {
        return props.residents;
    }

    return props.residents.filter((resident) => [
        resident.first_name,
        resident.last_name,
        resident.gender,
        resident.civil_status,
        resident.address,
        resident.contact_number,
    ].filter(Boolean).join(' ').toLowerCase().includes(term));
});

const submitForm = () => {
    form.post(route('residents.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset('first_name', 'last_name', 'birth_date', 'gender', 'address', 'contact_number'),
    });
};

const beginEdit = (resident) => {
    editingResidentId.value = resident.id;
    editForm.clearErrors();
    editForm.first_name = resident.first_name;
    editForm.last_name = resident.last_name;
    editForm.birth_date = resident.birth_date;
    editForm.gender = resident.gender;
    editForm.civil_status = resident.civil_status;
    editForm.address = resident.address;
    editForm.contact_number = resident.contact_number || '';
};

const cancelEdit = () => {
    editingResidentId.value = null;
    editForm.clearErrors();
    editForm.reset();
};

const updateResident = (id) => {
    editForm.patch(route('residents.update', id), {
        preserveScroll: true,
        onSuccess: cancelEdit,
    });
};

const deleteResident = (id) => {
    if (confirm('Remove this resident record? Related blotter records will also be removed.')) {
        router.delete(route('residents.destroy', id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="Residents" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2 class="text-2xl font-bold leading-tight text-slate-900">Resident Records</h2>
                <p class="mt-1 text-sm text-slate-500">Register, search, and manage barangay resident profiles.</p>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-[0.34fr_0.66fr] lg:px-8">
                <section class="h-fit rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">Add Resident</h3>
                    <p class="mt-1 text-sm text-slate-500">Complete the basic profile information.</p>

                    <form @submit.prevent="submitForm" class="mt-6 space-y-4">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-medium text-slate-700">First Name</label>
                                <input v-model="form.first_name" type="text" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
                                <p v-if="form.errors.first_name" class="mt-1 text-xs text-rose-600">{{ form.errors.first_name }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-700">Last Name</label>
                                <input v-model="form.last_name" type="text" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
                                <p v-if="form.errors.last_name" class="mt-1 text-xs text-rose-600">{{ form.errors.last_name }}</p>
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-medium text-slate-700">Birth Date</label>
                                <input v-model="form.birth_date" type="date" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
                                <p v-if="form.errors.birth_date" class="mt-1 text-xs text-rose-600">{{ form.errors.birth_date }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-700">Contact Number</label>
                                <input v-model="form.contact_number" type="text" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Optional" />
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-medium text-slate-700">Gender</label>
                                <select v-model="form.gender" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="" disabled>Select gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-700">Civil Status</label>
                                <select v-model="form.civil_status" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Widowed">Widowed</option>
                                    <option value="Separated">Separated</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Address</label>
                            <textarea v-model="form.address" rows="3" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
                            <p v-if="form.errors.address" class="mt-1 text-xs text-rose-600">{{ form.errors.address }}</p>
                        </div>

                        <button type="submit" :disabled="form.processing" class="w-full rounded-md bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 disabled:opacity-50">
                            {{ form.processing ? 'Saving...' : 'Save Resident' }}
                        </button>
                    </form>
                </section>

                <section class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="flex flex-col gap-4 border-b border-slate-200 p-6 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Resident Directory</h3>
                            <p class="mt-1 text-sm text-slate-500">{{ filteredResidents.length }} of {{ residents.length }} records shown</p>
                        </div>
                        <input v-model="search" type="search" class="w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:w-72" placeholder="Search residents..." />
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Resident</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Details</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Address</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                <tr v-for="resident in filteredResidents" :key="resident.id" class="align-top hover:bg-slate-50">
                                    <td class="px-6 py-4">
                                        <template v-if="editingResidentId === resident.id">
                                            <div class="grid gap-3 sm:grid-cols-2">
                                                <div>
                                                    <label class="text-xs font-medium text-slate-500">First Name</label>
                                                    <input v-model="editForm.first_name" type="text" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
                                                    <p v-if="editForm.errors.first_name" class="mt-1 text-xs text-rose-600">{{ editForm.errors.first_name }}</p>
                                                </div>
                                                <div>
                                                    <label class="text-xs font-medium text-slate-500">Last Name</label>
                                                    <input v-model="editForm.last_name" type="text" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
                                                    <p v-if="editForm.errors.last_name" class="mt-1 text-xs text-rose-600">{{ editForm.errors.last_name }}</p>
                                                </div>
                                            </div>
                                            <label class="mt-3 block text-xs font-medium text-slate-500">Contact Number</label>
                                            <input v-model="editForm.contact_number" type="text" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Optional" />
                                        </template>
                                        <template v-else>
                                            <p class="font-semibold text-slate-900">{{ resident.last_name }}, {{ resident.first_name }}</p>
                                            <p class="mt-1 text-xs text-slate-500">{{ resident.contact_number || 'No contact number' }}</p>
                                        </template>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        <template v-if="editingResidentId === resident.id">
                                            <div class="grid gap-3">
                                                <div>
                                                    <label class="text-xs font-medium text-slate-500">Birth Date</label>
                                                    <input v-model="editForm.birth_date" type="date" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
                                                    <p v-if="editForm.errors.birth_date" class="mt-1 text-xs text-rose-600">{{ editForm.errors.birth_date }}</p>
                                                </div>
                                                <div class="grid gap-3 sm:grid-cols-2">
                                                    <div>
                                                        <label class="text-xs font-medium text-slate-500">Gender</label>
                                                        <select v-model="editForm.gender" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                                            <option value="" disabled>Select gender</option>
                                                            <option value="Male">Male</option>
                                                            <option value="Female">Female</option>
                                                        </select>
                                                        <p v-if="editForm.errors.gender" class="mt-1 text-xs text-rose-600">{{ editForm.errors.gender }}</p>
                                                    </div>
                                                    <div>
                                                        <label class="text-xs font-medium text-slate-500">Civil Status</label>
                                                        <select v-model="editForm.civil_status" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                                            <option value="Single">Single</option>
                                                            <option value="Married">Married</option>
                                                            <option value="Widowed">Widowed</option>
                                                            <option value="Separated">Separated</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                        <template v-else>
                                            {{ resident.gender }} / {{ resident.civil_status }}<br />
                                            <span class="text-xs text-slate-500">Born {{ resident.birth_date }}</span>
                                        </template>
                                    </td>
                                    <td class="max-w-xs px-6 py-4 text-sm text-slate-600">
                                        <template v-if="editingResidentId === resident.id">
                                            <textarea v-model="editForm.address" rows="4" class="w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
                                            <p v-if="editForm.errors.address" class="mt-1 text-xs text-rose-600">{{ editForm.errors.address }}</p>
                                        </template>
                                        <template v-else>
                                            {{ resident.address }}
                                        </template>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div v-if="editingResidentId === resident.id" class="flex justify-end gap-3">
                                            <button @click="updateResident(resident.id)" :disabled="editForm.processing" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 disabled:opacity-50">
                                                {{ editForm.processing ? 'Saving...' : 'Save' }}
                                            </button>
                                            <button @click="cancelEdit" type="button" class="text-sm font-semibold text-slate-500 hover:text-slate-700">
                                                Cancel
                                            </button>
                                        </div>
                                        <div v-else class="flex justify-end gap-3">
                                            <button @click="beginEdit(resident)" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                                                Edit
                                            </button>
                                            <button @click="deleteResident(resident.id)" class="text-sm font-semibold text-rose-600 hover:text-rose-800">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="filteredResidents.length === 0">
                                    <td colspan="4" class="px-6 py-10 text-center text-sm text-slate-500">No resident records found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
