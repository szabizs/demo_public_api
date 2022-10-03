<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedAdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight text-teal-800">
                    Brands
                </h2>
                <a :href="route('admin.products.create')" class="right-0 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 hover:shadow-md hover:cursor-pointer">add new</a>
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
                            <th class="pb-4 pt-6 px-6">#</th>
                            <th class="pb-4 pt-6 px-6">SKU</th>
                            <th class="pb-4 pt-6 px-6">Name</th>
                            <th class="pb-4 pt-6 px-6">Brand</th>
                            <th class="pb-4 pt-6 px-6">Categories</th>
                            <th class="pb-4 pt-6 px-6">Price</th>
                            <th class="pb-4 pt-6 px-6">Status</th>
                        </tr>
                        <tr v-for="product in props.products" :key="product.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                            <td class="border-t text-right py-1">#{{ product.id }}</td>
                            <td class="border-t">
                                <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="route('admin.products.edit', {product: product})">
                                    {{ product.name }}
                                    <icon v-if="product.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
                                </Link>
                            </td>
                            <td class="border-t truncate">{{ product.sku }}</td>
                            <td class="border-t">{{ product.brand.name }}</td>
                            <td class="border-t">categories</td>
                            <td class="border-t text-right">{{ product.price }}</td>

                            <td class="border-t text-center text-xs text-white">
                                <div class="p-1 bg-green-700 w-full rounded-md" v-if="product.active === true">Active</div>
                                <div class="p-1 bg-red-700 w-full rounded-md" v-if="product.active === false">Not active</div>
                            </td>
                            <td class="w-px border-t">
                                <Link class="flex items-center px-4" :href="route('admin.products.edit', {product: product})" tabindex="-1">
                                    <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="products.length === 0">
                            <td class="px-6 py-4 border-t" colspan="4">No products found.</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedAdminLayout>
</template>


<script setup>

import { reactive, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia'
import {Head, Link} from '@inertiajs/inertia-vue3';
import BreezeAuthenticatedAdminLayout from '@/Layouts/AuthenticatedAdmin.vue';
import throttle from "lodash/throttle";
import SearchFilter from '@/Shared/SearchFilter'
import Icon from '@/Shared/Icon';
import pickBy from "lodash/pickBy";
import mapValues from "lodash/mapValues";


const props = defineProps({
    products: {
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
    Inertia.get('products', pickBy(data.form), {preserveState: true})
}, 1000), {
    deep: true
})

</script>
