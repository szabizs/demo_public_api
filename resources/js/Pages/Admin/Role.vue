<script setup>

import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeButton from '@/Components/Button.vue';
import LoadingButton from "@/Shared/LoadingButton";
import BreezeCheckbox from '@/Components/Checkbox.vue';
import BreezeAuthenticatedAdminLayout from '@/Layouts/AuthenticatedAdmin.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import {useForm, usePage, Link, Head} from "@inertiajs/inertia-vue3";
import {computed} from "vue";

const props = defineProps({
    role: {
        type: Object
    },
    users: {
        type: Array
    },
    allPermissions: {
        type: Array
    },
    rolePermissions: {
        type: Array
    },
})

const form = useForm({
    name: props.role.name,
    users: props.role.users,
    permissions: props.rolePermissions
});

const errors = computed(() => usePage().props.value.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const destroy = (user) => {
    if (confirm('Are you sure you want to delete this role?')) {
        form.delete(route('admin.roles.destroy', {role: props.role}))
    }
}

const submit = () => {
    form.patch(route('admin.roles.update', {role: props.role}))
}

const title = () => {
    return `Roles / ${props.role.name}`
}

</script>

<template>
    <Head :title="title()" />
    <BreezeAuthenticatedAdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <Link :href="route('admin.roles.index')" class="text-teal-800">Roles / </Link>{{ role.name }}
                </h2>
                <a :href="route('admin.roles.create')" class="right-0 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 hover:shadow-md hover:cursor-pointer">add new</a>
            </div>
        </template>

        <div class="py-2" v-if="hasErrors">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-md shadow overflow-x-auto p-2">
                    <BreezeValidationErrors class="mb-4" />
                </div>
            </div>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-md shadow overflow-x-auto p-2">
                    <form @submit.prevent="submit">
                        <div>
                            <BreezeLabel for="name" value="Name"/>
                            <BreezeInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocomplete="name" />
                        </div>
                        <div class="mt-4" v-if="users.length > 0">
                            <BreezeLabel for="user" value="Available users with current role" class="pb-5"/>
                            <div class="column-list-to-four">
                                <label class="flex items-center" v-for="user in users" :key="user.id">
                                    <BreezeCheckbox name="user" v-model:checked="form.users" :value="user.id" />
                                    <span class="ml-2 text-sm text-gray-600">{{ user.name }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-4" v-if="allPermissions.length > 0">
                            <BreezeLabel for="permission" value="Permissions" class="pb-5"/>
                            <div class="column-list-to-four">
                                <label class="flex items-center" v-for="permission in allPermissions" :key="permission.id">
                                    <BreezeCheckbox name="permission" v-model:checked="form.permissions" :value="permission.id" />
                                    <span class="ml-2 text-sm text-gray-600">{{ permission.name }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <BreezeButton type="button" @click="destroy(user)" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 ml-4 bg-red-600" :class="{ 'opacity-25 bg-red-800': form.processing }" :disabled="form.processing">
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
    </BreezeAuthenticatedAdminLayout>
</template>
