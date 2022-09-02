<template>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900">Attribute Values</h1>
                <p class="mt-2 text-sm text-gray-700">A list of all the users in your account including their name, title, email and role.</p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <button @click="newValue.add = !newValue.add" type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">Add new</button>
            </div>
        </div>

        <div v-if="newValue.add === true || newValue.edit" class="py-6">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">New option value</label>
                <div class="mt-1 flex flex-row space-x-2">
                    <input type="text"
                           v-model="newValue.value"
                           name="value" id="value" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-[300px] sm:text-sm border-gray-300 rounded-md" placeholder="Example: Blue" />
                    <button
                        v-if="newValue.add"
                        @click="saveValue"
                        type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">Save</button>
                    <button
                        v-if="newValue.edit"
                        @click="updateValues"
                        type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">Update</button>
                </div>
            </div>
        </div>

        <div class="-mx-4 mt-8 overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:-mx-6 md:mx-0 md:rounded-lg">

            <table v-if="values.length > 0" class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Value</th>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Date</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                        <span>Edit</span>
                    </th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                <tr v-for="(attributeValue, idx) in values" :key="attributeValue.id">
                    <td class="w-full max-w-0 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:w-auto sm:max-w-none sm:pl-6">
                        {{ attributeValue.value }}
                        <dl class="font-normal lg:hidden">
                            <dt class="sr-only">Title</dt>
                            <dd class="mt-1 truncate text-gray-700">{{ attributeValue.created_at }}</dd>
                            <dt class="sr-only sm:hidden">Edit</dt>
                        </dl>
                    </td>
                    <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">{{ attributeValue.created_at }}</td>
                    <td class="py-4 pl-3 pr-4 text-center text-sm font-medium sm:pr-6 flex flex-col space-y-2">
                        <span @click="editAttributeValue(attributeValue)" class="text-indigo-600 hover:text-indigo-900">Edit</span>
                        <span @click="deleteAttributeValue(attributeValue, idx)" class="text-red-600 hover:text-indigo-900">Delete</span>
                    </td>
                </tr>
                </tbody>
            </table>

            <div v-if="values.length === 0" class="p-3 text-blue-500 text-center font-semibold bg-yellow-100/50">
                You have no attribute values defined yet.
            </div>
        </div>
    </div>
</template>

<script setup>

import { ref, reactive, onMounted, computed, watch } from 'vue'
import useNotification from "@/Reusables/useNotification";
const { showNotification, notificationContent } = useNotification()

let attributeValues = ref([])
const newValue = reactive({
    add: false,
    edit: false,
    value: '',
    key: '',
    currentId: ''
})

const props = defineProps({
    attributeId: {
        type: Number
    }
})

const values = computed(() => {
    return attributeValues.value
})

function getValues() {
    axios.post('/attribute-values/get-values', {
        id: props.attributeId
    }).then(response => {
        Object.entries(response.data).forEach(value => {
            attributeValues.value.push(value[1])
        })
    })
}

function saveValue() {
    axios.post('/attribute-values/add-values', {
        id: props.attributeId,
        value: newValue.value
    }).then((response) => {
        attributeValues.value.push(response.data.value)

        showNotification.value = true
        notificationContent.value.type = 'success'
        notificationContent.value.timeoutInSeconds = 3
        notificationContent.value.title = 'Information'
        notificationContent.value.description = 'The attribute option has been created successfully.'
        reset()
    })
}

function editAttributeValue(value) {
    newValue.add = false;
    newValue.edit = true;
    newValue.value = value.value;
    newValue.currentId = value.id;
    newValue.key = attributeValues.value.indexOf(value);
}

function updateValues() {
    axios.post('/attribute-values/update-values', {
        id: props.attributeId,
        value: newValue.value,
        valueId: newValue.currentId,
    }).then((response) => {
        attributeValues.value[newValue.key] = response.data.value

        showNotification.value = true
        notificationContent.value.type = 'success'
        notificationContent.value.timeoutInSeconds = 3
        notificationContent.value.title = 'Information'
        notificationContent.value.description = 'The attribute option has been successfully updated.'

        reset()
    })
}

function reset() {
    newValue.add = false;
    newValue.edit = false;
    newValue.value = '';
    newValue.currentId = '';
    newValue.key = '';
}

function deleteAttributeValue (attributeValue, idx) {
    if (confirm('Are you sure you want to delete this attribute  option?')) {
        axios.delete(route('admin.attribute-values.delete-values', {attributeValue: attributeValue.id}))

        attributeValues.value.splice(idx, 1)
    }
}

watch(attributeValues, (newVal, oldVal) => {
    console.log(newVal);
} )

onMounted(() => {
    getValues()
})

</script>

<style scoped>

</style>
