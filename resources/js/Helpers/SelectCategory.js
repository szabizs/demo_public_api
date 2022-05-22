import { store } from "@/store";

export function useSelectCategory(category) {

    if(store.selectedParentCategory === category.id) {
        store.selectedParentCategory = {}
    } else {
        if(category.children_recursive.length > 0) {
            store.selectedParentCategory = category
        }
    }

    if(store.selectedCategory.id === category.id) {
        store.selectedCategory = {}
    } else {
        if(category.children_recursive.length === 0) {
            store.selectedCategory = category
        }
    }

    if(!store.selectedCategoryNodes.includes(category.id)) {
        store.selectedCategoryNodes.push(category.id)
    } else {
        let index = store.selectedCategoryNodes.indexOf(category.id)
        store.selectedCategoryNodes.splice(index, store.selectedCategoryNodes.length);
    }
}
