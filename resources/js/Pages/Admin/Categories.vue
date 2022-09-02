<template>
    <Head title="Categories" />

    <BreezeAuthenticatedAdminLayout>
        <template #header>


            <h2 class="font-semibold text-xl text-gray-800 leading-tight flow-root" v-if="props.categoryCount !== undefined">
                Category management
                <div class="float-right">Total categories: {{ props.categoryCount }}</div>
            </h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flow-root">
                <breeze-button class="float-right">Add new</breeze-button>
                {{ editing }}
            </div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-start justify-between space-x-2">
                <div class="w-5/12 lg:w-3/12 p-2 bg-white overflow-hidden shadow-sm sm:rounded-lg bg-white text-xs">
                    <h3 class="p-2 border-b text-base my-2">Overview</h3>
                    <span v-if="categories.length === 0" class="text-gray-400">There are no categories defined yet, <span class="font-bold underline">create one</span>.</span>
                    <ul>
                    <li v-for="category in props.categories" :key="category.id">
                        <div class="flex items-center w-full font-normal text-gray-900 p-1 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                            <a @click="useSelectCategory(category)" class="h-6 flex items-center justify-between w-full hover:font-bold cursor-pointer" :class="{'font-bold rounded-sm': storeSelectedParentCategory.id === category.id}">
                                {{ category.name }}
                                <div>
                                    <!-- down -->
                                    <icon-down class="absolute -mt-3 -ml-6" v-if="!storeSelectedCategoryNodes.includes(category.id) && category.children_recursive.length > 0"></icon-down>
                                    <!-- up -->
                                    <icon-up class="absolute -mt-3 -ml-5" v-if="storeSelectedCategoryNodes.includes(category.id) && category.children_recursive.length > 0"></icon-up>
                                </div>
                            </a>
                        </div>
                        <category-tree v-if="storeSelectedCategoryNodes.includes(category.id)" :tree="category" :indent="newIndent" />
                    </li>
                    </ul>
                </div>
                <div class="w-7/12 lg:w-9/12 p-2 bg-white overflow-hidden shadow-sm sm:rounded-lg bg-white text-xs">
                    <h3 v-if="Object.keys(storeSelectedCategory).length === 0" class="p-2 border-b text-base my-2">Overview</h3>
                    <h3 v-if="Object.keys(storeSelectedCategory).length !== 0" class="p-2 border-b text-base my-2">Overview: <span class="text-teal-600 font-bold">{{ storeSelectedCategory.name }}</span></h3>
                    <div class="flex items-center space-x-2 py-2" v-if="Object.keys(storeSelectedCategory).length > 1">
                        <breeze-button v-if="!state.editing" class="bg-teal-600 text-white" @click="edit(storeSelectedCategory)">edit</breeze-button>
                        <breeze-button v-if="!state.editing" class="bg-red-600 text-white">delete</breeze-button>
                        <breeze-button v-if="state.editing" @click="save" class="bg-green-600 text-white">save</breeze-button>
                        <breeze-button v-if="state.editing" @click="cancelEdit" class="bg-gray-600 text-white">cancel</breeze-button>
                    </div>
                    <div>
                        <span v-if="Object.keys(storeSelectedCategory).length === 0" class="text-gray-400">Please select a category for further actions.</span>
                        <span v-if="Object.keys(storeSelectedCategory).length !== 0" class="text-gray-400">Please select an action for the selected category.</span>
                    </div>
                    <div v-if="state.categoryData.id !== undefined">
                        <BreezeInput id="name" type="text" class="mt-1 block w-full" v-model="state.categoryData.name" required autofocus autocomplete="name" />
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedAdminLayout>
</template>

<script setup>
import { Head } from '@inertiajs/inertia-vue3';
import {store} from "@/store";
import BreezeAuthenticatedAdminLayout from '@/Layouts/AuthenticatedAdmin.vue';
import BreezeButton from '@/Components/Button';
import BreezeInput from '@/Components/Input';
import IconDown from "@/Components/Icons/Down";
import IconUp from "@/Components/Icons/Up";
import {useSelectCategory} from "@/Helpers/SelectCategory";
import {watch, computed, ref, reactive} from "vue";
import {Inertia} from '@inertiajs/inertia'

import CategoryTree from "@/Components/Recursive/CategoryTree";

const state = reactive({
    lastSelectedCategory: {},
    editing: false,
    categoryData: {},
})

const props = defineProps({
    categoryCount: {
        type: Number,
        required: true,
        default: 0
    },
    categories: {
        type: Array,
        required: true,
        default: []
    }
})

const storeSelectedCategory = computed(() => {
    state.lastSelectedCategory = store.selectedCategory
    return store.selectedCategory;
});

const storeSelectedParentCategory = computed(() => {
    state.lastSelectedCategory = store.selectedCategory
    return store.selectedParentCategory;
});

const storeSelectedCategoryNodes = computed(() => {
    return store.selectedCategoryNodes;
});

const clearEdit = () => {
    state.editing = false
    state.categoryData = {}
}

const cancelEdit = () => {
    state.editing = false
    state.categoryData = {}
}

const edit = (category) => {
    state.editing = true
    state.categoryData =  Object.assign({}, category)
}

const save = () => {

    Inertia.patch(route('admin.categories.update', state.categoryData), {
        name: state.categoryData.name,
    }, {
        onSuccess() {
            useSelectCategory(state.categoryData)
            clearEdit()
        }
    })
}

watch(storeSelectedCategory, (currentValue, oldValue) => {
    state.lastSelectedCategory = currentValue
    clearEdit()
},{ deep: true });

watch(storeSelectedParentCategory, (currentValue, oldValue) => {
    state.lastSelectedCategory = currentValue
    clearEdit()
},{ deep: true });

const indent = ref(15)

const newIndent = computed(() => {
    return indent.value
})

const style = computed(() => {
    return {
        'margin-left': '5px'
    }
})

</script>
