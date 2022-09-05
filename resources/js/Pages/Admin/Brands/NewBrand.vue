<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedAdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <Link :href="route('admin.brands.index')" class="text-teal-800">Brands / </Link>New
                </h2>
                <div  class="right-0 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 hover:shadow-md hover:cursor-pointer">add new</div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <BreezeValidationErrors class="mb-4" />

                        <form @submit.prevent="submit">

                            <div class="mt-4">
                                <BreezeLabel for="name" value="Name" />
                                <BreezeInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autocomplete="name" />
                            </div>

                            <div class="mt-4">
                                <BreezeLabel for="frontend_type" value="Select type of brand" />
                                <input type="file" @input="form.logo = $event.target.files[0]" class="mt-1 block w-full" id="logo" name="logo"/>
                                <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                                    {{ form.progress.percentage }}%
                                </progress>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <LoadingButton class="ml-4 btn-api bg-green-700" :class="{ 'opacity-25 bg-teal-800': form.processing }" :loading="form.processing" :disabled="form.processing">
                                    Create new brand
                                </LoadingButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedAdminLayout>
</template>

<script setup>
import BreezeAuthenticatedAdminLayout from '@/Layouts/AuthenticatedAdmin.vue';
import LoadingButton from "@/Shared/LoadingButton";
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import {Head, useForm, Link} from '@inertiajs/inertia-vue3';
import { RadioGroup, RadioGroupDescription, RadioGroupLabel, RadioGroupOption,
    Switch, SwitchDescription, SwitchGroup, SwitchLabel} from '@headlessui/vue'

const form = useForm({
    name: '',
    logo: '',
});

defineProps({
    types: {
        type: Array,
    },
})

const submit = () => {
    form.post(route('admin.brands.store'), {
        forceFormData: true,
        onSuccess: () => {
            form.reset('name', 'logo');
        },
    });
};
</script>
