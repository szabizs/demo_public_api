import { store } from "@/store";

export function useSelectCategory(category) {

    if(store.selectedParentCategory === category.id) {
        store.selectedParentCategory = {}
    } else {
        if(category.children_recursive.length > 0) {
            store.selectedParentCategory = category
        }
    }
    if(!store.selectedCategoryNodes.includes(category.id)) {
        store.selectedCategoryNodes.push(category.id)
    } else {
        let index = store.selectedCategoryNodes.indexOf(category.id)
        store.selectedCategoryNodes.splice(index, store.selectedCategoryNodes.length);
    }

    store.selectedCategory = category
}
