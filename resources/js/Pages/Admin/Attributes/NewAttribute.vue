<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedAdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <Link :href="route('admin.attributes.index')" class="text-teal-800">Attributes / </Link>New
                </h2>
                <div  class="right-0 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 hover:shadow-md hover:cursor-pointer">add new</div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
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
                                <LoadingButton class="ml-4 btn-api bg-green-700" :class="{ 'opacity-25 bg-teal-800': form.processing }" :loading="form.processing" :disabled="form.processing">
                                    Create new attribute
                                </LoadingButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedAdminLayout>
</template>

<script setup>
import BreezeAuthenticatedAdminLayout from '@/Layouts/AuthenticatedAdmin.vue';
import LoadingButton from "@/Shared/LoadingButton";
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import {Head, useForm, Link} from '@inertiajs/inertia-vue3';
import { RadioGroup, RadioGroupDescription, RadioGroupLabel, RadioGroupOption,
    Switch, SwitchDescription, SwitchGroup, SwitchLabel} from '@headlessui/vue'

const form = useForm({
    code: '',
    name: '',
    frontend_type: '',
    is_required: false,
    is_filterable: false,
});

defineProps({
    types: {
        type: Array,
    },
})

const submit = () => {
    form.post(route('admin.attributes.store'));
};
</script>
