<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';
import BreezeDropdown from '@/Components/Dropdown.vue';
import BreezeDropdownLink from '@/Components/DropdownLink.vue';
import BreezeNavLink from '@/Components/NavLink.vue';
import BreezeResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import PublicLayout from "@/Layouts/PublicLayout";
</script>

<template>
    <Head title="Dashboard" />
    <PublicLayout>

        <div class="relative bg-gray-900 -mt-10">
            <!-- Decorative image and overlay -->
            <div aria-hidden="true" class="absolute inset-0 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1618004912476-29818d81ae2e?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1964&q=80" alt="" class="w-full h-full object-center object-cover" />
            </div>
            <div aria-hidden="true" class="absolute inset-0 bg-gray-900 opacity-50" />

            <div class="relative max-w-3xl mx-auto py-32 px-6 flex flex-col items-center text-center sm:py-64 lg:px-0">
                <h1 class="text-4xl font-bold tracking-tight text-white lg:tracking-tight lg:text-6xl">New arrivals are here</h1>
                <p class="mt-4 text-xl text-white">The new arrivals have, well, newly arrived. Check out the latest options from our summer small-batch release while they're still in stock.</p>
                <a href="#" class="mt-8 inline-block bg-white border border-transparent rounded-md py-3 px-8 text-base font-medium text-gray-900 hover:bg-gray-100">Shop New Arrivals</a>
            </div>
        </div>

    </PublicLayout>
</template>
