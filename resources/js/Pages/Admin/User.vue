<template>
    <Head :title="title" />
    <BreezeAuthenticatedAdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <Link :href="route('admin.users.index')" class="text-teal-800">Users / </Link>{{ user.name }}
                </h2>
                <a :href="route('admin.users.create')" class="right-0 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 hover:shadow-md hover:cursor-pointer">add new</a>
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
                            <BreezeInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocomplete="off" />
                        </div>

                        <div class="mt-4">
                            <BreezeLabel for="email" value="Email"/>
                            <BreezeInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autocomplete="off" />
                        </div>

                        <div class="mt-4">
                            <BreezeLabel for="password" value="Password"/>
                            <BreezeInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" autocomplete="off" />
                        </div>

                        <div class="mt-4" v-if="allPermissions.length > 0">
                            <BreezeLabel for="permission" value="Permissions" class="pb-5"/>
                            <div class="grid grid-cols-4">
                                <label class="flex items-center" v-for="permission in allPermissions" :key="permission.id">
                                    <BreezeCheckbox name="permission" v-model:checked="form.permissions" :value="permission.id" />
                                    <span class="ml-2 text-sm text-gray-600">{{ permission.name }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="mt-4" v-if="allRoles !== undefined && allRoles.length > 0">
                            <BreezeLabel for="role" value="Roles" class="pb-5"/>
                            <div class="grid grid-cols-4">
                                <label class="flex items-center" v-for="role in allRoles" :key="role.id">
                                    <BreezeCheckbox name="role" v-model:checked="form.roles" :value="role.id" />
                                    <span class="ml-2 text-sm text-gray-600">{{ role.name }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="mt-4">
                            <BreezeLabel for="token" value="API Token"/>
                            <div class="flex items-center space-x-2">
                                <LoadingButton @click="generateToken" class="btn-api h-10 bg-red-700" :class="{ 'opacity-25 bg-red-800': form.processing, 'bg-green-600': apiGeneratorLabel === 'generate' }" :loading="form.processing" :disabled="form.processing">
                                    {{ apiGeneratorLabel }}
                                </LoadingButton>
                                <BreezeInput id="Token" type="text" class="mt-1 block w-full" autocomplete="new-password" aria-disabled="true" disabled :value="user.api_token" />
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

<script setup>

import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeButton from '@/Components/Button.vue';
import BreezeCheckbox from '@/Components/Checkbox.vue';
import BreezeAuthenticatedAdminLayout from '@/Layouts/AuthenticatedAdmin.vue';
import LoadingButton from '@/Shared/LoadingButton';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import {useForm, usePage, Link, Head} from "@inertiajs/inertia-vue3";
import {computed} from "vue";

const props = defineProps({
    user: {
        type: Object
    },
    allPermissions: {
        type: Array
    },
    userPermissions: {
        type: Array
    },
    allRoles: {
        type: Array
    },
    userRoles: {
        type: Array
    },
})

const errors = computed(() => usePage().props.value.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const apiGeneratorLabel = computed(() => props.user.api_token !== null ? 'regenerate' : 'generate')

const title = computed(() => `Users / ${props.user.name}`)

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    permissions: props.userPermissions,
    roles: props.userRoles,
});

const destroy = () => {
    if (confirm('Are you sure you want to delete this user?')) {
        form.delete(route('admin.users.destroy', {user: props.user}))
    }
}

const submit = () => {
    form.patch(route('admin.users.update', {user: props.user}), {
        onFinish: () => form.reset('password')
    })
}

const generateToken = () => {
    form.post(route('admin.users.generate_token', {user: props.user}))
}


</script>


<style scoped>

</style>
