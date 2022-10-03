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
                            <div v-if="data.activeTab === 'attribute'" class="p-6 bg-white border-b border-gray-200">
                                <form @submit.prevent="submit">
                                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                                                        Update
                                                    </LoadingButton>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <ProductImages :product="product" v-if="data.activeTab === 'images'"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </BreezeAuthenticatedAdminLayout>
</template>

<script setup>

import {Link, useForm} from '@inertiajs/inertia-vue3';
import LoadingButton from "@/Shared/LoadingButton";
import ProductImages from "@/Pages/Admin/Products/ProductImages";

import BreezeAuthenticatedAdminLayout from '@/Layouts/AuthenticatedAdmin.vue';
import {reactive, ref, onMounted, onBeforeMount} from "vue";
import {CameraIcon, HomeIcon} from "@heroicons/vue/solid";


const props = defineProps([
    'product',
    'brands',
    'categories',
])

const data = reactive({
    activeTab: 'attribute',
})

const navigation = [
    { name: 'Product', icon: HomeIcon, href: '#', code: 'attribute' },
    { name: 'Images', icon: CameraIcon, href: '#', code: 'images' },
]

const form = useForm({
    product_id: props.product.id,
    name: props.product.name,
    sku: props.product.sku,
    description: props.product.description,
    price: props.product.price,
    sale_price: props.product.sale_price,
    quantity: props.product.quantity,
    featured: props.product.featured,
    active: props.product.active,
    brand_id: props.product.brand_id,
    category_id: props.product.categories[0].id,
});

const submit = () => {
    form.patch(route('admin.products.update', props.product.id), {
        preserveScroll: true,
        onSuccess: () => {
        }
    });
};
</script>
