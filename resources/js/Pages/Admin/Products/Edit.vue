<template>
    <BreezeAuthenticatedAdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <Link :href="route('admin.products.index')" class="text-teal-800">Products / </Link>{{ product.sku }}
                </h2>
                <a :href="route('admin.products.create')" class="right-0 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 hover:shadow-md hover:cursor-pointer">add new</a>
            </div>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flow-root">
                <breeze-button v-if="!addNew" class="float-right" @click="add">Add new</breeze-button>
                <breeze-button v-if="addNew" class="float-right bg-red-700" @click="addNew = false">Cancel</breeze-button>
            </div>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <form @submit.prevent="submit">
                        <template v-for="group in product.attribute_family.attribute_groups" :key="group.id">
                            <template v-if="group.custom_attributes.length > 0">
                                <BreezeLabel for="name" :value="group.name"/>
                                <div class="bg-white rounded-md shadow overflow-x-auto p-2 my-5">
                                    <template v-for="attribute in group.custom_attributes" :key="attribute.id">
                                        <div>
                                            <BreezeLabel for="name" :value="attribute.admin_name"/>
                                            <BreezeInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocomplete="name" />
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </template>
                    </form>
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
import {ref} from "vue";

const addNew = ref(false)

const props = defineProps([
    'product',
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
