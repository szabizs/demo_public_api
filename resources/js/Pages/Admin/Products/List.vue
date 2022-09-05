<template>
    <BreezeAuthenticatedAdminLayout>
        <template #header>


            <h2 class="font-semibold text-xl text-gray-800 leading-tight flow-root">
                Product management
                <div class="float-right">Total products: {{ productsCount }}</div>
            </h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flow-root">
                <breeze-button v-if="!addNew" class="float-right" @click="add">Add new</breeze-button>
                <breeze-button v-if="addNew" class="float-right bg-red-700" @click="addNew = false">Cancel</breeze-button>
            </div>
        </div>

        <div v-if="addNew" class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-4 gap-4">
                <div class="col-start-2 col-span-2 p-2 bg-white overflow-hidden shadow-md hover:shadow-lg sm:rounded-lg bg-white text-xs">
                    <h3 class="p-2 border-b text-base my-2">Add product</h3>
                    <form @submit.prevent="submit">
                        <BreezeValidationErrors class="mb-4" />
                        <div class="mt-2">
                            <BreezeLabel for="product_type" value="Product Type" />
                            <BreezeSelect class="mt-2 block w-full" :items="productTypes" v-model="form.type" />
                        </div>
                        <div class="mt-2">
                            <BreezeLabel for="attribute_family" value="Attribute Family" />
                            <BreezeSelect class="mt-2 block w-full" :items="attributeFamilies" v-model="form.attribute_family_id" />
                        </div>
                        <div class="mt-2">
                            <BreezeLabel for="sku" value="SKU" />
                            <BreezeInput id="sku" type="text" class="mt-2 block w-full" v-model="form.sku" required />
                        </div>
                        <div class="flex items-center justify-end mt-10">
                            <LoadingButton @click="submit" class="ml-4 btn-api bg-green-700" :class="{ 'opacity-25 bg-teal-800': form.processing }" :loading="form.processing" :disabled="form.processing">
                                Add
                            </LoadingButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div v-if="!addNew" class="py-12">
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
                            <th class="pb-4 pt-6 px-6" colspan="2">Name</th>
                        </tr>
                        <tr v-for="product in products.data" :key="product.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                            <td class="border-t">
                                <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="route('admin.products.edit', {product: product})">
                                    {{ product.sku }}
                                    <icon v-if="product.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
                                </Link>
                            </td>
                            <td class="w-px border-t">
                                <Link class="flex items-center px-4" :href="route('admin.products.edit', {product: product})" tabindex="-1">
                                    <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="products.data.length === 0">
                            <td class="px-6 py-4 border-t" colspan="4">No products found.</td>
                        </tr>
                    </table>
                </div>
                <pagination class="mt-6" :links="products.links" />
            </div>
        </div>

    </BreezeAuthenticatedAdminLayout>
</template>

<script setup>

import {Head, useForm, Link} from '@inertiajs/inertia-vue3';
import BreezeAuthenticatedAdminLayout from '@/Layouts/AuthenticatedAdmin.vue';
import BreezeLabel from '@/Components/Label';
import BreezeButton from '@/Components/Button';
import BreezeInput from '@/Components/Input';
import BreezeSelect from '@/Components/Select'
import SearchFilter from '@/Shared/SearchFilter'
import Pagination from '@/Shared/Pagination'
import throttle from 'lodash/throttle'
import pickBy from 'lodash/pickBy'
import mapValues from "lodash/mapValues"
import icon from "@/Shared/Icon";

import {ref} from "vue";

const addNew = ref(false)

const props = defineProps([
    'productsCount',
    'products',
    'productTypes',
    'attributeFamilies'
])

const add = () => {
    addNew.value = !addNew.value
}

const form = useForm({
    type: '',
    attribute_family_id: null,
    sku: null,
});

const submit = () => {
    form.post(route('admin.products.store'));
};

</script>

<style scoped>

</style>
