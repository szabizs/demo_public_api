<script setup>
import BreezeAuthenticatedAdminLayout from '@/Layouts/AuthenticatedAdmin.vue';
import {Head, Link} from '@inertiajs/inertia-vue3';
</script>

<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedAdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight text-teal-800">
                    Users
                </h2>
                <a :href="route('admin.users.create')" class="right-0 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 hover:shadow-md hover:cursor-pointer">add new</a>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <search-filter v-model="form.search" class="mr-4 w-full max-w-md mb-5" @reset="reset">
                    <label class="block text-gray-700">Trashed:</label>
                    <select v-model="form.trashed" class="form-select mt-1 w-full">
                        <option :value="null" />
                        <option value="with">With Trashed</option>
                        <option value="only">Only Trashed</option>
                    </select>
                </search-filter>
                <div class="bg-white rounded-md shadow overflow-x-auto">
                    <table class="w-full whitespace-nowrap">
                        <tr class="text-left font-bold">
                            <th class="pb-4 pt-6 px-6">Name</th>
                            <th class="pb-4 pt-6 px-6" colspan="2">Email</th>
                        </tr>
                        <tr v-for="user in users" :key="user.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                            <td class="border-t">
                                <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="route('admin.users.edit', {user: user})">
                                    {{ user.name }}
                                    <icon v-if="user.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
                                </Link>
                            </td>
                            <td class="border-t">
                                <Link class="flex items-center px-6 py-4" :href="route('admin.users.edit', {user: user})" tabindex="-1">
                                    {{ user.email }}
                                </Link>
                            </td>
                            <td class="w-px border-t">
                                <Link class="flex items-center px-4" :href="route('admin.users.edit', {user: user})" tabindex="-1">
                                    <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="users.length === 0">
                            <td class="px-6 py-4 border-t" colspan="4">No users found.</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedAdminLayout>
</template>


<script>
import SearchFilter from '@/Shared/SearchFilter'
import throttle from 'lodash/throttle'
import pickBy from 'lodash/pickBy'
import mapValues from "lodash/mapValues";

export default {
    components: {
        SearchFilter
    },
    props: {
        users: {
            type: Object
        },
        filters: {
            type: Object
        }
    },
    data() {
        return {
            form: {
                search: this.filters.search,
                trashed: this.filters.trashed,
            }
        }
    },
    watch: {
        form: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get('users', pickBy(this.form), { preserveState: true })
            }, 150),
        },
    },
    methods: {
        reset() {
            this.form = mapValues(this.form, () => null)
        }
    }
}
</script>
