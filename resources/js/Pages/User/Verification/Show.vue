<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, ref } from 'vue';

const props = defineProps({
    verification: Object,
});

const video = ref(null);
const canvas = ref(null);
const cameraError = ref('');
const idPreview = ref(props.verification?.valid_id_url || '');
const cameraStream = ref(null);

const form = useForm({
    first_name: props.verification?.first_name || '',
    middle_name: props.verification?.middle_name || '',
    last_name: props.verification?.last_name || '',
    birth_date: props.verification?.birth_date || '',
    gender: props.verification?.gender || '',
    civil_status: props.verification?.civil_status || '',
    contact_number: props.verification?.contact_number || '',
    address: props.verification?.address || '',
    purok: props.verification?.purok || '',
    years_of_residency: props.verification?.years_of_residency || '',
    valid_id_type: props.verification?.valid_id_type || 'Philippine National ID',
    valid_id_number: props.verification?.valid_id_number || '',
    valid_id: null,
    face_photo: '',
});

const statusClass = computed(() => ({
    Submitted: 'bg-sky-50 text-sky-700 ring-sky-200',
    Verified: 'bg-emerald-50 text-emerald-700 ring-emerald-200',
    Rejected: 'bg-rose-50 text-rose-700 ring-rose-200',
}[props.verification?.status] || 'bg-slate-50 text-slate-700 ring-slate-200'));

const handleIdUpload = (event) => {
    const file = event.target.files?.[0];

    if (!file) {
        return;
    }

    form.valid_id = file;

    if (file.type.startsWith('image/')) {
        idPreview.value = URL.createObjectURL(file);
    } else {
        idPreview.value = '';
    }
};

const startCamera = async () => {
    cameraError.value = '';

    try {
        cameraStream.value = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'user' },
            audio: false,
        });

        video.value.srcObject = cameraStream.value;
    } catch (error) {
        cameraError.value = 'Camera access is blocked or unavailable. Please allow camera permission and try again.';
    }
};

const captureFace = () => {
    if (!video.value || !canvas.value) {
        return;
    }

    canvas.value.width = video.value.videoWidth;
    canvas.value.height = video.value.videoHeight;
    canvas.value.getContext('2d').drawImage(video.value, 0, 0);
    form.face_photo = canvas.value.toDataURL('image/png');
};

const stopCamera = () => {
    cameraStream.value?.getTracks().forEach((track) => track.stop());
};

const submit = () => {
    form.post(route('user.verification.store'), {
        forceFormData: true,
        preserveScroll: true,
    });
};

onBeforeUnmount(stopCamera);
</script>

<template>
    <Head title="Resident Verification" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-2xl font-bold leading-tight text-slate-900">Resident Verification</h2>
                    <p class="mt-1 text-sm text-slate-500">Confirm that you are a legitimate resident of the barangay.</p>
                </div>
                <span v-if="verification" class="w-fit rounded-full px-3 py-1 text-xs font-semibold ring-1" :class="statusClass">
                    {{ verification.status }}
                </span>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-[0.62fr_0.38fr] lg:px-8">
                <form @submit.prevent="submit" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Personal & Residency Information</h3>
                        <p class="mt-1 text-sm text-slate-500">
                            Fill this exactly as it appears on your valid ID. The upload is ready for OCR integration, but fields remain editable for accuracy.
                        </p>
                    </div>

                    <div class="mt-6 grid gap-4 sm:grid-cols-3">
                        <div>
                            <label class="text-sm font-medium text-slate-700">First Name</label>
                            <input v-model="form.first_name" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
                            <p v-if="form.errors.first_name" class="mt-1 text-xs text-rose-600">{{ form.errors.first_name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-700">Middle Name</label>
                            <input v-model="form.middle_name" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-700">Last Name</label>
                            <input v-model="form.last_name" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
                            <p v-if="form.errors.last_name" class="mt-1 text-xs text-rose-600">{{ form.errors.last_name }}</p>
                        </div>
                    </div>

                    <div class="mt-4 grid gap-4 sm:grid-cols-4">
                        <div>
                            <label class="text-sm font-medium text-slate-700">Birth Date</label>
                            <input v-model="form.birth_date" type="date" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-700">Gender</label>
                            <select v-model="form.gender" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="" disabled>Select</option>
                                <option>Male</option>
                                <option>Female</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-700">Civil Status</label>
                            <select v-model="form.civil_status" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="" disabled>Select</option>
                                <option>Single</option>
                                <option>Married</option>
                                <option>Widowed</option>
                                <option>Separated</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-700">Contact Number</label>
                            <input v-model="form.contact_number" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
                        </div>
                    </div>

                    <div class="mt-4 grid gap-4 sm:grid-cols-[1fr_160px_160px]">
                        <div>
                            <label class="text-sm font-medium text-slate-700">Complete Barangay Address</label>
                            <input v-model="form.address" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-700">Purok/Sitio</label>
                            <input v-model="form.purok" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-700">Years Resident</label>
                            <input v-model="form.years_of_residency" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>
                    </div>

                    <div class="mt-6 border-t border-slate-200 pt-6">
                        <h3 class="text-lg font-semibold text-slate-900">Valid ID</h3>
                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-medium text-slate-700">Valid ID Type</label>
                                <select v-model="form.valid_id_type" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option>Philippine National ID</option>
                                    <option>Driver's License</option>
                                    <option>Passport</option>
                                    <option>Voter's ID</option>
                                    <option>UMID</option>
                                    <option>Other Government ID</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-700">ID Number</label>
                                <input v-model="form.valid_id_number" class="mt-2 w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="text-sm font-medium text-slate-700">Upload Valid ID</label>
                            <input type="file" accept="image/*,.pdf" @change="handleIdUpload" class="mt-2 block w-full rounded-md border border-slate-300 bg-white text-sm file:mr-4 file:border-0 file:bg-indigo-50 file:px-4 file:py-3 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100" :required="!verification" />
                            <p v-if="form.errors.valid_id" class="mt-1 text-xs text-rose-600">{{ form.errors.valid_id }}</p>
                        </div>
                    </div>

                    <div class="mt-6 border-t border-slate-200 pt-6">
                        <h3 class="text-lg font-semibold text-slate-900">Face Camera Verification</h3>
                        <p class="mt-1 text-sm text-slate-500">Use a phone camera or laptop webcam. This works on mobile browsers that allow camera access.</p>
                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <div class="overflow-hidden rounded-lg bg-slate-950">
                                <video ref="video" autoplay playsinline muted class="aspect-video w-full object-cover"></video>
                            </div>
                            <div class="overflow-hidden rounded-lg bg-slate-100">
                                <img v-if="form.face_photo" :src="form.face_photo" class="aspect-video w-full object-cover" alt="Captured face" />
                                <img v-else-if="verification?.face_photo_url" :src="verification.face_photo_url" class="aspect-video w-full object-cover" alt="Saved face" />
                                <div v-else class="flex aspect-video items-center justify-center text-sm text-slate-500">Captured photo preview</div>
                            </div>
                        </div>
                        <p v-if="cameraError" class="mt-2 text-sm text-rose-600">{{ cameraError }}</p>
                        <p v-if="form.errors.face_photo" class="mt-1 text-xs text-rose-600">{{ form.errors.face_photo }}</p>
                        <div class="mt-4 flex flex-wrap gap-3">
                            <button type="button" @click="startCamera" class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Open Camera</button>
                            <button type="button" @click="captureFace" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Capture Face</button>
                            <button type="button" @click="stopCamera" class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Stop Camera</button>
                        </div>
                    </div>

                    <button type="submit" :disabled="form.processing" class="mt-8 w-full rounded-md bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 disabled:opacity-50">
                        {{ form.processing ? 'Submitting...' : 'Submit Verification' }}
                    </button>
                </form>

                <aside class="space-y-6">
                    <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-slate-900">Verification Status</h3>
                        <div v-if="verification" class="mt-4">
                            <span class="rounded-full px-3 py-1 text-xs font-semibold ring-1" :class="statusClass">{{ verification.status }}</span>
                            <p class="mt-3 text-sm text-slate-500">Submitted: {{ verification.submitted_at ? new Date(verification.submitted_at).toLocaleString() : 'Not submitted' }}</p>
                            <p v-if="verification.admin_notes" class="mt-3 rounded-md bg-slate-50 p-3 text-sm text-slate-700">Admin note: {{ verification.admin_notes }}</p>
                        </div>
                        <p v-else class="mt-3 text-sm text-slate-500">No verification submitted yet.</p>
                    </section>

                    <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-slate-900">ID Preview</h3>
                        <img v-if="idPreview" :src="idPreview" class="mt-4 rounded-lg border border-slate-200" alt="Valid ID preview" />
                        <div v-else class="mt-4 rounded-lg bg-slate-50 p-6 text-sm text-slate-500">PDF or no preview selected.</div>
                    </section>

                    <canvas ref="canvas" class="hidden"></canvas>
                </aside>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
