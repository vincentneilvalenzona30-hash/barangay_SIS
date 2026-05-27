<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, reactive, ref } from 'vue';

const props = defineProps({
    items: Array,
});

const search = ref('');
const editing = reactive({});

const form = useForm({
    type: 'News',
    title: '',
    tag: '',
    date_label: '',
    schedule: '',
    location: '',
    description: '',
    image: null,
    is_published: true,
});

const filteredItems = computed(() => {
    const term = search.value.toLowerCase().trim();

    if (!term) {
        return props.items;
    }

    return props.items.filter((item) => [
        item.type,
        item.title,
        item.tag,
        item.date_label,
        item.schedule,
        item.location,
        item.description,
        item.is_published ? 'published' : 'draft',
    ].filter(Boolean).join(' ').toLowerCase().includes(term));
});

const resetForm = () => {
    form.reset();
    form.type = 'News';
    form.is_published = true;
};

const handleImage = (event) => {
    form.image = event.target.files[0] ?? null;
};

const submit = () => {
    form.post(route('admin.news-events.store'), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: resetForm,
    });
};

const startEditing = (item) => {
    editing[item.id] = {
        type: item.type,
        title: item.title,
        tag: item.tag ?? '',
        date_label: item.date_label ?? '',
        schedule: item.schedule ?? '',
        location: item.location ?? '',
        description: item.description,
        image: null,
        is_published: item.is_published,
    };
};

const handleEditImage = (item, event) => {
    editing[item.id].image = event.target.files[0] ?? null;
};

const updateItem = (item) => {
    router.post(route('admin.news-events.update', item.id), {
        ...editing[item.id],
        _method: 'patch',
    }, {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => delete editing[item.id],
    });
};

const deleteItem = (id) => {
    if (confirm('Delete this news or event item?')) {
        router.delete(route('admin.news-events.destroy', id), {
            preserveScroll: true,
        });
    }
};

const typeClass = (type) => type === 'Event'
    ? 'bg-sky-50 text-sky-700 ring-sky-200'
    : 'bg-emerald-50 text-emerald-700 ring-emerald-200';
</script>

<template>
    <Head title="News & Events" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2 class="text-2xl font-bold leading-tight text-slate-900">News & Events</h2>
                <p class="mt-1 text-sm text-slate-500">Publish barangay announcements and activities for the public page.</p>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-[380px_1fr] lg:px-8">
                <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">Add Item</h3>
                    <p class="mt-1 text-sm text-slate-500">Create a public news post or scheduled event.</p>

                    <form @submit.prevent="submit" class="mt-5 space-y-4">
                        <div>
                            <InputLabel for="type" value="Type" />
                            <select id="type" v-model="form.type" class="mt-1 block w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option>News</option>
                                <option>Event</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.type" />
                        </div>

                        <div>
                            <InputLabel for="title" value="Title" />
                            <TextInput id="title" v-model="form.title" class="mt-1 block w-full" required />
                            <InputError class="mt-2" :message="form.errors.title" />
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
                            <div>
                                <InputLabel for="tag" value="Tag" />
                                <TextInput id="tag" v-model="form.tag" class="mt-1 block w-full" placeholder="Announcement" />
                                <InputError class="mt-2" :message="form.errors.tag" />
                            </div>

                            <div>
                                <InputLabel for="date_label" value="Date Label" />
                                <TextInput id="date_label" v-model="form.date_label" class="mt-1 block w-full" placeholder="May 2026" />
                                <InputError class="mt-2" :message="form.errors.date_label" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="schedule" value="Schedule" />
                            <TextInput id="schedule" v-model="form.schedule" class="mt-1 block w-full" placeholder="Office hours" />
                            <InputError class="mt-2" :message="form.errors.schedule" />
                        </div>

                        <div>
                            <InputLabel for="location" value="Location" />
                            <TextInput id="location" v-model="form.location" class="mt-1 block w-full" placeholder="Barangay Office" />
                            <InputError class="mt-2" :message="form.errors.location" />
                        </div>

                        <div>
                            <InputLabel for="description" value="Description" />
                            <textarea id="description" v-model="form.description" rows="5" required class="mt-1 block w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>

                        <div>
                            <InputLabel for="image" value="Picture" />
                            <input id="image" type="file" accept="image/*" @change="handleImage" class="mt-1 block w-full rounded-md border border-slate-300 bg-white text-sm text-slate-700 file:mr-4 file:border-0 file:bg-indigo-50 file:px-4 file:py-3 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100" />
                            <p class="mt-1 text-xs text-slate-500">Optional JPG, PNG, or WEBP image up to 4MB.</p>
                            <InputError class="mt-2" :message="form.errors.image" />
                        </div>

                        <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                            <input v-model="form.is_published" type="checkbox" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                            Published
                        </label>

                        <button type="submit" :disabled="form.processing" class="w-full rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-50">
                            Add News/Event
                        </button>
                    </form>
                </section>

                <section class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="flex flex-col gap-4 border-b border-slate-200 p-6 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Published Content</h3>
                            <p class="mt-1 text-sm text-slate-500">{{ filteredItems.length }} of {{ items.length }} items shown</p>
                        </div>
                        <input v-model="search" type="search" class="w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:w-80" placeholder="Search news and events..." />
                    </div>

                    <div class="divide-y divide-slate-100">
                        <article v-for="item in filteredItems" :key="item.id" class="p-6">
                            <div v-if="editing[item.id]" class="space-y-4">
                                <div class="grid gap-4 md:grid-cols-2">
                                    <select v-model="editing[item.id].type" class="rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option>News</option>
                                        <option>Event</option>
                                    </select>
                                    <TextInput v-model="editing[item.id].title" />
                                    <TextInput v-model="editing[item.id].tag" placeholder="Tag" />
                                    <TextInput v-model="editing[item.id].date_label" placeholder="Date label" />
                                    <TextInput v-model="editing[item.id].schedule" placeholder="Schedule" />
                                    <TextInput v-model="editing[item.id].location" placeholder="Location" />
                                </div>
                                <textarea v-model="editing[item.id].description" rows="4" class="w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                <div class="grid gap-3 md:grid-cols-[160px_1fr] md:items-center">
                                    <img v-if="item.image_url" :src="item.image_url" :alt="item.title" class="h-24 w-full rounded-md object-cover ring-1 ring-slate-200" />
                                    <div v-else class="flex h-24 items-center justify-center rounded-md bg-slate-50 text-xs font-medium text-slate-400 ring-1 ring-slate-200">
                                        No picture
                                    </div>
                                    <div>
                                        <InputLabel :for="`image-${item.id}`" value="Replace Picture" />
                                        <input :id="`image-${item.id}`" type="file" accept="image/*" @change="handleEditImage(item, $event)" class="mt-1 block w-full rounded-md border border-slate-300 bg-white text-sm text-slate-700 file:mr-4 file:border-0 file:bg-indigo-50 file:px-4 file:py-3 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100" />
                                    </div>
                                </div>
                                <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                                    <input v-model="editing[item.id].is_published" type="checkbox" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                                    Published
                                </label>
                                <div class="flex flex-wrap gap-2">
                                    <button @click="updateItem(item)" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                                        Save Changes
                                    </button>
                                    <button @click="delete editing[item.id]" class="rounded-md border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                        Cancel
                                    </button>
                                </div>
                            </div>

                            <div v-else class="grid gap-5 lg:grid-cols-[1fr_auto]">
                                <div>
                                    <img v-if="item.image_url" :src="item.image_url" :alt="item.title" class="mb-4 h-44 w-full rounded-lg object-cover ring-1 ring-slate-200" />
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold ring-1" :class="typeClass(item.type)">
                                            {{ item.type }}
                                        </span>
                                        <span v-if="item.tag" class="rounded-full bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600 ring-1 ring-slate-200">{{ item.tag }}</span>
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold ring-1" :class="item.is_published ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : 'bg-amber-50 text-amber-700 ring-amber-200'">
                                            {{ item.is_published ? 'Published' : 'Draft' }}
                                        </span>
                                    </div>
                                    <h4 class="mt-3 text-lg font-semibold text-slate-950">{{ item.title }}</h4>
                                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ item.description }}</p>
                                    <div class="mt-3 flex flex-wrap gap-x-5 gap-y-1 text-xs text-slate-500">
                                        <span v-if="item.date_label">Date: {{ item.date_label }}</span>
                                        <span v-if="item.schedule">Schedule: {{ item.schedule }}</span>
                                        <span v-if="item.location">Location: {{ item.location }}</span>
                                    </div>
                                </div>

                                <div class="flex gap-2 lg:flex-col">
                                    <button @click="startEditing(item)" class="rounded-md border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                        Edit
                                    </button>
                                    <button @click="deleteItem(item.id)" class="rounded-md border border-rose-200 px-4 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </article>

                        <div v-if="filteredItems.length === 0" class="px-6 py-12 text-center text-sm text-slate-500">
                            No news or events found.
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
