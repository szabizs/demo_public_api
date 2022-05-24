<script setup>
import BreezeAuthenticatedAdminLayout from '@/Layouts/AuthenticatedAdmin.vue';
import LoadingButton from "@/Shared/LoadingButton";
import BreezeCheckbox from '@/Components/Checkbox.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import {Head, useForm, Link} from '@inertiajs/inertia-vue3';


const form = useForm({
    name: '',
    permissions: []
});

const props = defineProps({
    permissions: {
        type: Array
    },
})

const submit = () => {
    form.post(route('admin.roles.store'));
};
</script>

<template>
    <Head title="Create new role" />

    <BreezeAuthenticatedAdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <Link :href="route('admin.roles.index')" class="text-teal-800">Roles / </Link>New
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
                            <div>
                                <BreezeLabel for="name" value="Name" />
                                <BreezeInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocomplete="name" />
                            </div>

                            <div class="mt-4" v-if="permissions.length > 0">
                                <BreezeLabel for="permission" value="Permissions" class="pb-5"/>
                                <div class="grid grid-cols-4">
                                    <label class="flex items-center" v-for="permission in permissions" :key="permission.id">
                                        <BreezeCheckbox name="permission" v-model:checked="form.permissions" :value="permission.id" />
                                        <span class="ml-2 text-sm text-gray-600">{{ permission.name }}</span>
                                    </label>
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <LoadingButton class="ml-4 btn-api bg-green-700" :class="{ 'opacity-25 bg-teal-800': form.processing }" :loading="form.processing" :disabled="form.processing">
                                    Create new role
                                </LoadingButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedAdminLayout>
</template>
