<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedAdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <Link :href="route('admin.attributes.index')" class="text-teal-800">Attributes / </Link>
                    {{ attribute.name }}
                </h2>
                <a :href="route('admin.attributes.create')" class="right-0 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 hover:shadow-md hover:cursor-pointer">add new</a>
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
                                <BreezeValidationErrors class="mb-4" />

                                <form @submit.prevent="submit">
                                    <div>
                                        <BreezeLabel for="code" value="Code" />
                                        <BreezeInput id="code" type="text" class="mt-1 block w-full" v-model="form.code" required autofocus autocomplete="code" />
                                    </div>

                                    <div class="mt-4">
                                        <BreezeLabel for="name" value="Name" />
                                        <BreezeInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autocomplete="name" />
                                    </div>

                                    <div class="mt-4">
                                        <BreezeLabel for="frontend_type" value="Select type of attribute" />
                                        <RadioGroup v-model="form.frontend_type">
                                            <RadioGroupLabel class="sr-only"> Privacy setting </RadioGroupLabel>
                                            <div class="bg-white rounded-md -space-y-px">
                                                <RadioGroupOption as="template" v-for="(tp, idx) in types" :key="idx" :value="tp" v-slot="{ checked, active }">
                                                    <div :class="[idx === 0 ? 'rounded-tl-md rounded-tr-md' : '', idx === types.length - 1 ? 'rounded-bl-md rounded-br-md' : '', checked ? 'bg-indigo-50 border-indigo-200 z-10' : 'border-gray-200', 'relative border p-4 flex cursor-pointer focus:outline-none']">
                                              <span :class="[checked ? 'bg-indigo-600 border-transparent' : 'bg-white border-gray-300', active ? 'ring-2 ring-offset-2 ring-indigo-500' : '', 'h-4 w-4 mt-0.5 cursor-pointer shrink-0 rounded-full border flex items-center justify-center']" aria-hidden="true">
                                                <span class="rounded-full bg-white w-1.5 h-1.5" />
                                              </span>
                                                        <span class="ml-3 flex flex-col">
                                                <RadioGroupLabel as="span" :class="[checked ? 'text-indigo-900' : 'text-gray-900', 'block text-sm font-medium']">
                                                  Define the attribute with the Frontend Type of: <b>{{ tp }}</b>
                                                </RadioGroupLabel>
                                              </span>
                                                    </div>
                                                </RadioGroupOption>
                                            </div>
                                        </RadioGroup>
                                    </div>
                                    <div class="mt-4 w-6/12">
                                        <SwitchGroup as="div" class="flex items-center justify-between">
                                    <span class="flex-grow flex flex-col">
                                      <SwitchLabel as="span" class="text-sm font-medium text-gray-900" passive>Is the Attribute Required?</SwitchLabel>
                                      <SwitchDescription as="span" class="text-sm text-gray-500">Specify if the attribute will be required upon product creation.</SwitchDescription>
                                    </span>
                                            <Switch v-model="form.is_required" :class="[form.is_required ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500']">
                                                <span aria-hidden="true" :class="[form.is_required ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200']" />
                                            </Switch>
                                        </SwitchGroup>
                                    </div>
                                    <div class="mt-4 w-6/12">
                                        <SwitchGroup as="div" class="flex items-center justify-between">
                                                            <span class="flex-grow flex flex-col">
                                                              <SwitchLabel as="span" class="text-sm font-medium text-gray-900" passive>Is the Attribute Filterable?</SwitchLabel>
                                                              <SwitchDescription as="span" class="text-sm text-gray-500">Specify if the attribute will be a filter/facet on the frontend.</SwitchDescription>
                                                            </span>
                                            <Switch v-model="form.is_filterable" :class="[form.is_filterable ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500']">
                                                <span aria-hidden="true" :class="[form.is_filterable ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200']" />
                                            </Switch>
                                        </SwitchGroup>
                                    </div>

                                    <div class="flex items-center justify-end mt-4">
                                        <BreezeButton type="button" @click="destroy(attribute)" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 ml-4 bg-red-600" :class="{ 'opacity-25 bg-red-800': form.processing }" :disabled="form.processing">
                                            Delete
                                        </BreezeButton>
                                        <LoadingButton class="ml-4 btn-api bg-green-700" :class="{ 'opacity-25 bg-teal-800': form.processing }" :loading="form.processing" :disabled="form.processing">
                                            Update
                                        </LoadingButton>
                                    </div>
                                </form>
                            </div>

                            <AttributeValues :attribute-id="attribute.id" v-if="data.activeTab === 'attribute-values'" class="p-6 bg-white border-b border-gray-200"></AttributeValues>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </BreezeAuthenticatedAdminLayout>
</template>

<script setup>

import { ref, reactive } from 'vue'

import BreezeAuthenticatedAdminLayout from '@/Layouts/AuthenticatedAdmin.vue';
import LoadingButton from "@/Shared/LoadingButton";
import BreezeButton from '@/Components/Button.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import {Head, useForm, Link, usePage} from '@inertiajs/inertia-vue3';
import { RadioGroup, RadioGroupLabel, RadioGroupOption,
    Switch, SwitchDescription, SwitchGroup, SwitchLabel} from '@headlessui/vue'
import { computed } from "vue";

import { HomeIcon, UsersIcon, FilterIcon} from '@heroicons/vue/solid'
import AttributeValues from "@/Pages/Admin/Attributes/AttributeValues";

const errors = computed(() => usePage().props.value.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const data = reactive({
    activeTab: 'attribute',
})

const navigation = [
    { name: 'Attributes', icon: HomeIcon, href: '#', code: 'attribute' },
    { name: 'Option values', icon: FilterIcon, href: '#', code: 'attribute-values' },
]

const sidebarOpen = ref(false)

const form = useForm({
    code: props.attribute.code,
    name: props.attribute.name,
    frontend_type: props.attribute.frontend_type,
    is_required: props.attribute.is_required,
    is_filterable: props.attribute.is_filterable,
});

const props = defineProps({
    attribute: {
      type: Object,
    },
    types: {
        type: Object,
    },
})


const destroy = () => {
    if (confirm('Are you sure you want to delete this attribute?')) {
        form.delete(route('admin.attributes.destroy', {attribute: props.attribute}))
    }
}

const submit = () => {
    form.patch(route('admin.attributes.update', {attribute: props.attribute}), {
        onFinish: () => form.reset()
    })
}

</script>
