<!-- This example requires Tailwind CSS v2.0+ -->
<template>
    <!-- Global notification live region, render this permanently at the end of the document -->
    <div v-if="showNotification" aria-live="assertive" class="fixed bottom-0 left-0 mt-12 md:mt-24 lg:mt-28 w-full z-20 flex items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start">
        <div class="w-full flex flex-col items-center space-y-4 sm:items-start">
            <!-- Notification panel, dynamically insert this into the live region when it needs to be displayed -->
            <transition enter-active-class="transform ease-out duration-300 transition" enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" enter-to-class="translate-y-0 opacity-100 sm:translate-x-0" leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div
                    class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
                    :class="themeClassObject"
                >
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <InformationCircleIcon v-if="notificationContent.type === 'info'" class="h-6 w-6" aria-hidden="true" />
                                <CheckCircleIcon v-if="notificationContent.type === 'success'" class="h-6 w-6" aria-hidden="true" />
                                <XIcon v-if="notificationContent.type === 'error'" class="h-6 w-6" aria-hidden="true" />
                            </div>
                            <div class="ml-3 w-0 flex-1 pt-0.5">
                                <p class="text-sm font-medium">{{ notificationContent.title }}</p>
                                <p class="mt-1 text-sm" v-html="notificationContent.description"></p>
                            </div>
                            <div class="ml-4 flex-shrink-0 flex">
                                <button type="button" @click="showNotification = false" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <span class="sr-only">Close</span>
                                    <XIcon class="h-5 w-5" aria-hidden="true" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script setup>

import { computed, watch } from 'vue'

import useNotification from "@/Reusables/useNotification";

import { CheckCircleIcon, XIcon, InformationCircleIcon } from '@heroicons/vue/outline'

let { showNotification } = useNotification()
const { notificationContent } = useNotification()

let timer

watch(showNotification, (newVal) => {
    if(newVal) {
        clearTimeout(timer)
        timer = setTimeout(() => {
            showNotification.value = false
        }, notificationContent.value.timeoutInSeconds * 1000)
    }
})

let themeClassObject = computed(() => ({
    'bg-blue-200 text-blue-800': notificationContent.value.type === 'info',
    'bg-green-200 text-green-800': notificationContent.value.type === 'success',
    'bg-red-200 text-red-800': notificationContent.value.type === 'error',
}))

</script>
