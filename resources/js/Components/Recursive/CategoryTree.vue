<script setup>
import {store} from "@/store";
import {useSelectCategory} from "@/Helpers/SelectCategory";
import IconDown from "@/Components/Icons/Down";
import IconUp from "@/Components/Icons/Up";

</script>

<template>
    <ul role="menu" :style="style">
        <li v-for="category in tree.children_recursive" :key="category.id" :class="{ 'text-blue-800': tree.children_recursive !== undefined }">
            <div class="flex items-center w-full font-normal text-gray-900 p-1 pl-2 m-1 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700" :class="{'p-1 font-bold w-full font-normal text-gray-900 p-1 rounded-lg transition duration-75 bg-gray-100': store.selectedCategory.id === category.id}">
                <a @click="useSelectCategory(category)" href="#" class="h-6 flex items-center justify-between w-full hover:font-bold">
                    {{ category.name }}
                    <!-- down -->
                    <icon-down v-if="!store.selectedCategoryNodes.includes(category.id) && category.children_recursive.length > 0"></icon-down>
                    <!-- up -->
                    <icon-up v-if="store.selectedCategoryNodes.includes(category.id) && category.children_recursive.length > 0"></icon-up>
                </a>
            </div>
            <CategoryTree v-if="store.selectedCategoryNodes.includes(category.id) && category.children_recursive.length > 0 " :tree="category" :indent="newIndent"></CategoryTree>
        </li>
    </ul>
</template>

<script>

export default {
    name: "CategoryTree",
    props: {
        tree: {
            type: Object
        },
        indent: {
            type: Number
        }
    },
    computed: {
        newIndent() {
            return this.indent
        },
        style() {
            return {
                'margin-left': this.indent + 'px'
            }
        }
    }
}
</script>
<style scoped>

</style>
