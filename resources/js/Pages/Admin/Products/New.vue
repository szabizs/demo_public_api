<template>
    <BreezeAuthenticatedAdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <Link :href="route('admin.products.index')" class="text-teal-800">Products / </Link> New
                </h2>
                <a :href="route('admin.products.create')" class="right-0 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 hover:shadow-md hover:cursor-pointer">add new</a>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <form @submit.prevent="submit">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="bg-white px-4 py-5 shadow sm:rounded-lg sm:p-6">

                                <div v-if="Object.keys(form.errors).length > 0" class="my-5 p-3 bg-red-100 rounded-md">
                                    <ul>
                                        <li v-for="error in form.errors">{{ error }}</li>
                                    </ul>
                                </div>

                                <div class="md:grid md:grid-cols-3 md:gap-6">
                                    <div class="md:col-span-1">
                                        <h3 class="text-lg font-medium leading-6 text-gray-900">General</h3>
                                        <p class="mt-1 text-sm text-gray-500">Define the product's general informations.</p>
                                    </div>
                                    <div class="mt-5 md:col-span-2 md:mt-0">
                                        <div class="grid grid-cols-6 gap-6">
                                            <div class="col-span-3">
                                                <label for="product-name" class="block text-sm font-medium text-gray-700">Product Name</label>
                                                <input type="text" v-model="form.name" name="product-name" id="product-name" autocomplete="family-name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                            </div>

                                            <div class="col-span-3">
                                                <label for="product-sku" class="block text-sm font-medium text-gray-700">Product SKU</label>
                                                <input type="text" v-model="form.sku" name="product-sku" id="product-sku" autocomplete="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-6 gap-6 mt-5">
                                            <div class="col-span-3">
                                                <label for="product-brand" class="block text-sm font-medium text-gray-700">Brand</label>
                                                <select id="product-brand" v-model="form.brand_id" name="product-brand" autocomplete="product-brand" class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                                    <option v-for="(brand, idx) in brands" :value="brand.id">{{ brand.name }}</option>
                                                </select>
                                            </div>
                                            <div class="col-span-3">
                                                <label for="product-category" class="block text-sm font-medium text-gray-700">Category</label>
                                                <select id="product-category" v-model="form.category_id" name="product-category" autocomplete="product-category" class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                                    <option v-for="(category, idx) in categories" :value="category.id">{{ category.name }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-6 gap-6 mt-5">
                                            <div class="col-span-2">
                                                <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                                                <input type="number" name="price" v-model="form.price" id="price" autocomplete="address-level2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                            </div>

                                            <div class="col-span-2">
                                                <label for="sale_price" class="block text-sm font-medium text-gray-700">Sale Price</label>
                                                <input type="number" name="sale_price" v-model="form.sale_price" id="sale_price" autocomplete="address-level1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                            </div>

                                            <div class="col-span-2">
                                                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                                                <input type="number" step="1" name="quantity" v-model="form.quantity" id="quantity" autocomplete="postal-code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                            </div>
                                        </div>

                                        <div class="mt-5">
                                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                            <textarea rows="5" name="description" id="description" v-model="form.description" autocomplete="postal-code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                                        </div>

                                        <fieldset class="mt-5 flex flex-col space-y-5">
                                                <div class="relative flex items-start">
                                                    <div class="flex h-5 items-center">
                                                        <input id="featured" name="featured" v-model="form.featured" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-700 focus:ring-blue-500" />
                                                    </div>
                                                    <div class="ml-3 text-sm">
                                                        <label for="featured" class="font-medium text-gray-700">Featured</label>
                                                        <p class="text-gray-500">Specify if the product is featured.</p>
                                                    </div>
                                                </div>
                                                <div class="relative flex items-start">
                                                    <div class="flex h-5 items-center">
                                                        <input id="active" name="active" v-model="form.active" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-700 focus:ring-blue-500" />
                                                    </div>
                                                    <div class="ml-3 text-sm">
                                                        <label for="active" class="font-medium text-gray-700">Active(status)</label>
                                                        <p class="text-gray-500">Specify the status of the product(active/inactive)</p>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        <div class="flex items-center justify-end mt-4">
                                            <LoadingButton class="ml-4 btn-api bg-green-700" :class="{ 'opacity-25 bg-teal-800': form.processing }" :loading="form.processing" :disabled="form.processing">
                                                Create new product
                                            </LoadingButton>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
            </div>
        </div>


    </BreezeAuthenticatedAdminLayout>
</template>

<script setup>

import {Link, useForm} from '@inertiajs/inertia-vue3';
import LoadingButton from "@/Shared/LoadingButton";

import BreezeAuthenticatedAdminLayout from '@/Layouts/AuthenticatedAdmin.vue';
import {ref} from "vue";

const addNew = ref(false)
const product = ref({
    name: '',
    sku: '',
    description: '',
    price: '',
    sale_price: '',
    quantity: '',
    featured: false,
    active: false,
    brand_id: '',
    category_id: '',
})

const props = defineProps([
    'brands',
    'categories'
])

const add = () => {
    addNew.value = !addNew.value
}

const form = useForm({
    name: '',
    sku: '',
    description: '',
    price: '',
    sale_price: '',
    quantity: '',
    featured: false,
    active: false,
    brand_id: '',
    category_id: '',
});

const errors = ref(form.errors)

const submit = () => {
    form.post(route('admin.products.store'), {
        forceFormData: true,
        onError: (errors) => {
            form.errors = errors
        },
        onSuccess: () => {
            form.reset();
        },
    });
};

</script>

<style scoped>

</style>
