<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedAdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <Link :href="route('admin.brands.index')" class="text-teal-800">Brands / </Link>
                    {{ brand.name }}
                </h2>
                <a :href="route('admin.brands.create')" class="right-0 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 hover:shadow-md hover:cursor-pointer">add new</a>
            </div>
        </template>

        <div class="py-12">

            <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
                <aside class="py-6 px-2 sm:px-6 lg:py-0 lg:px-0 lg:col-span-3">
                    <nav class="space-y-1">
                        <a v-for="item in navigation" :key="item.name" :href="item.href" :class="[data.activeTab === item.code ? 'bg-gray-50 text-indigo-700 hover:text-indigo-700 hover:bg-white' : 'text-gray-900 hover:text-gray-900 hover:bg-gray-50', 'group rounded-md px-3 py-2 flex items-center text-sm font-medium']" :aria-current="data.activeTab === item.code ? 'page' : undefined">
                            <div @click="data.activeTab = item.code" class="flex flex-row space-x-2">
                                <component :is="item.icon" :class="[data.activeTab === item.code ? 'bg-gray-50 text-indigo-700 hover:text-indigo-700 hover:bg-white' : 'text-gray-400 group-hover:text-gray-500', 'flex-shrink-0 -ml-1 mr-3 h-6 w-6']" aria-hidden="true" />
                                <span class="truncate">
                                {{ item.name }}
                            </span>
                            </div>
                        </a>
                    </nav>
                </aside>

                <div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-9">
                    <div class="mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                            <div v-if="data.activeTab === 'brand'" class="p-6 bg-white border-b border-gray-200">
                                <BreezeValidationErrors class="mb-4" />

                                <form @submit.prevent="submit">
                                    <div class="mt-4">
                                        <BreezeLabel for="name" value="Name" />
                                        <BreezeInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autocomplete="name" />
                                    </div>

                                    <div v-if="form.logo !== null && typeof form.logo !== 'object'" class="mt-4">
                                        <BreezeLabel for="logo" value="Logo" />
                                        <div class="p-4 bg-stone-100 w-36 rounded-md shadow-md text-center">
                                            <img :src="form.logo" class="object-contain mx-auto"/>
                                            <span
                                                @click="deleteLogo"
                                                class="text-red-700 cursor-pointer">
                                                remove
                                            </span>
                                        </div>
                                    </div>
                                    <div v-if="form.logo === null || typeof form.logo === 'object'" class="mt-4">
                                        <BreezeLabel for="frontend_type" value="Select type of brand" />
                                        <input type="file" @input="form.logo = $event.target.files[0]" class="mt-1 block w-full" id="logo" name="logo"/>
                                        <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                                            {{ form.progress.percentage }}%
                                        </progress>
                                    </div>


                                    <div class="flex items-center justify-end mt-4">
                                        <BreezeButton type="button" @click="destroy" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 ml-4 bg-red-600" :class="{ 'opacity-25 bg-red-800': form.processing }" :disabled="form.processing">
                                            Delete
                                        </BreezeButton>
                                        <LoadingButton class="ml-4 btn-api bg-green-700" :class="{ 'opacity-25 bg-teal-800': form.processing }" :loading="form.processing" :disabled="form.processing">
                                            Update
                                        </LoadingButton>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </BreezeAuthenticatedAdminLayout>
</template>

<script setup>

import { ref, reactive } from 'vue'

import BreezeAuthenticatedAdminLayout from '@/Layouts/AuthenticatedAdmin.vue';
import LoadingButton from "@/Shared/LoadingButton";
import BreezeButton from '@/Components/Button.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import {Head, useForm, Link, usePage} from '@inertiajs/inertia-vue3';
import { computed } from "vue";
import useNotification from "@/Reusables/useNotification";
const { showNotification, notificationContent } = useNotification()

import { HomeIcon } from '@heroicons/vue/solid'

const errors = computed(() => usePage().props.value.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const data = reactive({
    activeTab: 'brand',
})

const navigation = [
    { name: 'Brand', icon: HomeIcon, href: '#', code: 'brand' },
]


const form = useForm({
    name: props.brand.name,
    logo: props.brand.logo,
});

const props = defineProps({
    brand: {
      type: Object,
    }
})

function deleteLogo() {
    axios.delete(route('admin.brands.logo.destroy', {brand: props.brand}))
        .then(() => {
            form.logo = null;
            showNotification.value = true
            notificationContent.value.type = 'success'
            notificationContent.value.timeoutInSeconds = 3
            notificationContent.value.title = 'Information'
            notificationContent.value.description = 'Logo has been deleted successfully'
        })
}


const destroy = () => {
    if (confirm('Are you sure you want to delete this brand?')) {
        form.delete(route('admin.brands.destroy', {brand: props.brand}), {
            onSuccess: () => {
                showNotification.value = true
                notificationContent.value.type = 'success'
                notificationContent.value.timeoutInSeconds = 3
                notificationContent.value.title = 'Information'
                notificationContent.value.description = 'Brand has been deleted successfully'
            }
        })
    }
}

const submit = () => {

    let formData = new FormData();
    formData.append('_method', 'PUT')
    formData.append('name', form.name)

    if(typeof form.logo === 'object' && form.logo !== null) {
        formData.append("logo", form.logo)
    }

    axios.post(route('admin.brands.update', {brand: props.brand}), formData, {
        headers: {
            "Content-Type": "multipart/form-data",
        }
    }).then((response) => {
        form.logo = response.data.brand.logo
        showNotification.value = true
        notificationContent.value.type = 'success'
        notificationContent.value.timeoutInSeconds = 3
        notificationContent.value.title = 'Information'
        notificationContent.value.description = 'Brand has been updated successfully'
    })
}

</script>
