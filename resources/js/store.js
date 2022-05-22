import {reactive} from "vue";

export const store = reactive({
    selectedParentCategory: {
        id: 0
    },
    selectedCategory: {},
    selectedCategoryNodes: [],
})
