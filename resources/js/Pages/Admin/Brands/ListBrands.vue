<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedAdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight text-teal-800">
                    Brands
                </h2>
                <a :href="route('admin.brands.create')" class="right-0 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 hover:shadow-md hover:cursor-pointer">add new</a>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <search-filter v-model="data.form.search" class="mr-4 w-full max-w-md mb-5" @reset="reset">
                    <label class="block text-gray-700">Trashed:</label>
                    <select v-model="data.form.trashed" class="form-select mt-1 w-full">
                        <option :value="null" />
                        <option value="with">With Trashed</option>
                        <option value="only">Only Trashed</option>
                    </select>
                </search-filter>
                <div class="bg-white rounded-md shadow overflow-x-auto">
                    <table class="w-full whitespace-nowrap">
                        <tr class="text-left font-bold">
                            <th class="pb-4 pt-6 px-6">Name</th>
                            <th class="pb-4 pt-6 px-6">Slug</th>
                            <th class="pb-4 pt-6 px-6">Logo</th>
                        </tr>
                        <tr v-for="brand in props.brands" :key="brand.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                            <td class="border-t">
                                <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="route('admin.brands.edit', {brand: brand})">
                                    {{ brand.name }}
                                    <icon v-if="brand.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
                                </Link>
                            </td>
                            <td class="border-t">
                                {{ brand.slug }}
                            </td>
                            <td class="border-t">
                                <div v-if="brand.logo !== null">
                                    <img :src="brand.logo" class="w-10 sm:w-20 md:w-10 lg:w-16 -mt-4 sm:mt-0" />
                                </div>
                            </td>
                            <td class="w-px border-t">
                                <Link class="flex items-center px-4" :href="route('admin.brands.edit', {brand: brand})" tabindex="-1">
                                    <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="brands.length === 0">
                            <td class="px-6 py-4 border-t" colspan="4">No brands found.</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedAdminLayout>
</template>


<script setup>

import { reactive, watch } from 'vue';
import BreezeAuthenticatedAdminLayout from '@/Layouts/AuthenticatedAdmin.vue';
import {Head, Link} from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia'
import SearchFilter from '@/Shared/SearchFilter'
import throttle from "lodash/throttle";
import Icon from '@/Shared/Icon';
import pickBy from "lodash/pickBy";
import mapValues from "lodash/mapValues";


const props = defineProps({
    brands: {
        type: Array
    },
    filters: {
        type: Object
    }
})

const data = reactive({
    form: {
        search: props.filters.search,
        trashed: props.filters.trashed,
    }
})

function reset() {
    data.form = mapValues(data.form, () => null)
}

watch(data, throttle(() => {
    Inertia.get('brands', pickBy(data.form), {preserveState: true})
}, 1000), {
    deep: true
})

</script>
