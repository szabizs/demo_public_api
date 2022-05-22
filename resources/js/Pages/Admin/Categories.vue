<script setup>
import { Head } from '@inertiajs/inertia-vue3';
import {store} from "@/store";
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import BreezeButton from '@/Components/Button';
import BreezeInput from '@/Components/Input';
import IconDown from "@/Components/Icons/Down";
import IconUp from "@/Components/Icons/Up";
import {useSelectCategory} from "@/Helpers/SelectCategory";

</script>

<template>
    <Head title="Categories" />

    <BreezeAuthenticatedLayout>
        <template #header>


            <h2 class="font-semibold text-xl text-gray-800 leading-tight flow-root">
                Category management
                <div class="float-right">Total categories: {{ categoryCount }}</div>
            </h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flow-root">
                <breeze-button class="float-right">Add new</breeze-button>
            </div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-start justify-between space-x-2">
                <div class="w-5/12 lg:w-3/12 p-2 bg-white overflow-hidden shadow-sm sm:rounded-lg bg-white text-xs">
                    <h3 class="p-2 border-b text-base my-2">Overview</h3>
                    <span v-if="categories.length === 0" class="text-gray-400">There are no categories defined yet, <span class="font-bold underline">create one</span>.</span>
                    <ul>
                    <li v-for="category in categories" :key="category.id">
                        <div class="flex items-center w-full font-normal text-gray-900 p-1 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <a @click="useSelectCategory(category)" class="h-6 flex items-center justify-between w-full hover:font-bold cursor-pointer" :class="{'font-bold rounded-sm': store.selectedParentCategoryId === category.id}">
                                {{ category.name }}
                                <div>
                                    <!-- down -->
                                    <icon-down class="absolute -mt-3 -ml-6" v-if="!store.selectedCategoryNodes.includes(category.id) && category.children_recursive.length > 0"></icon-down>
                                    <!-- up -->
                                    <icon-up class="absolute -mt-3 -ml-5" v-if="store.selectedCategoryNodes.includes(category.id) && category.children_recursive.length > 0"></icon-up>
                                </div>
                            </a>
                        </div>
                        <category-tree v-if="store.selectedCategoryNodes.includes(category.id)" :tree="category" :indent="newIndent" />
                    </li>
                    </ul>
                </div>
                <div class="w-7/12 lg:w-9/12 p-2 bg-white overflow-hidden shadow-sm sm:rounded-lg bg-white text-xs">
                    <h3 v-if="Object.keys(lastSelectedCategory).length === 0" class="p-2 border-b text-base my-2">Overview</h3>
                    <h3 v-if="Object.keys(lastSelectedCategory).length !== 0" class="p-2 border-b text-base my-2">Overview: <span class="text-teal-600 font-bold">{{ lastSelectedCategory.name }}</span></h3>
                    <div class="flex items-center space-x-2 py-2" v-if="Object.keys(lastSelectedCategory).length !== 0">
                        <breeze-button v-if="!editing" class="bg-teal-600 text-white" @click="edit">edit</breeze-button>
                        <breeze-button v-if="!editing" class="bg-red-600 text-white">delete</breeze-button>
                        <breeze-button v-if="editing" @click="save" class="bg-green-600 text-white">save</breeze-button>
                        <breeze-button v-if="editing" @click="cancelEdit" class="bg-gray-600 text-white">cancel</breeze-button>
                    </div>
                    <div>
                        <span v-if="Object.keys(lastSelectedCategory).length === 0" class="text-gray-400">Please select a category for further actions.</span>
                        <span v-if="Object.keys(lastSelectedCategory).length !== 0" class="text-gray-400">Please select an action for the selected category.</span>
                    </div>
                    <div v-if="Object.keys(categoryData).length > 0">
                        <BreezeInput id="name" type="text" class="mt-1 block w-full" v-model="categoryData.name" required autofocus autocomplete="name" />
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import CategoryTree from "@/Components/Recursive/CategoryTree";

export default {
    props: ['categoryCount','categories'],
    components: [CategoryTree],
    computed: {
        newIndent() {
            return this.indent
        },
        style() {
            return {
                'margin-left': '5px'
            }
        },
    },
    data() {
        return {
            indent: 15,
            categoryData: {},
            lastSelectedCategory: {},
            editing: false,
        }
    },
    watch: {
        'store.selectedCategory'(newVal, oldVal) {
            this.lastSelectedCategory = store.selectedCategory
            this.clearEdit()
        },
        'store.selectedParentCategory'(newVal, oldVal) {
            this.lastSelectedCategory = store.selectedParentCategory
            this.clearEdit()
        }
    },
    methods: {
        edit() {
            this.editing = true
            this.categoryData =  Object.assign({}, this.lastSelectedCategory)
        },
        clearEdit() {
            this.editing = false
            this.categoryData = {}
        },
        cancelEdit() {
            this.clearEdit()
        },
        save() {
            this.$inertia.patch(route('admin.categories.update', this.categoryData.id), {
                name: this.categoryData.name,
            }, {
                onSuccess() {
                    this.clearEdit()
                }
            })
        }
    }
}
</script>
