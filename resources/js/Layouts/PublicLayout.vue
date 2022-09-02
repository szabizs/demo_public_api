<template>
    <Popover class="sticky top-0 z-10 shadow-lg">
        <Disclosure as="header" class="bg-white dark:bg-gray-900 shadow relative" v-slot="{ open }">
            <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:divide-y lg:divide-gray-200 lg:px-8">
                <div class="relative h-16 flex justify-between">
                    <div class="relative z-10 px-2 flex lg:px-0">
                        <div class="flex-shrink-0 flex items-center">
                            <Link :href="route('home')">
                                <div class="flex space-x-2 items-center">
                                    <CubeIcon class="block h-8 w-auto text-emerald-600" />
                                    <div class="text-black dark:text-white">B2B Shop</div>
                                </div>
                            </Link>
                        </div>
                    </div>
                    <div v-if="$page.props.auth.user" class="relative z-0 flex-1 px-2 flex items-center justify-center sm:absolute sm:inset-0">
                        <div class="w-full sm:max-w-xs">
                            <label for="search" class="sr-only">Search</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                                    <SearchIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                                </div>
                                <input id="search" name="search" class="block w-full bg-white dark:bg-gray-800 border border-gray-300 rounded-md py-2 pl-10 pr-3 text-sm placeholder-gray-500 focus:outline-none focus:text-gray-900 focus:placeholder-gray-400 focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm dark:focus:text-white dark:text-gray-500" placeholder="Search" type="search" />
                            </div>
                        </div>
                    </div>
                    <div class="relative z-10 flex items-center lg:hidden">
                        <!-- Mobile menu button -->
                        <DisclosureButton class="rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-emerald-500">
                            <span class="sr-only">Open menu</span>
                            <MenuIcon v-if="!open" class="block h-6 w-6" aria-hidden="true" />
                            <XIcon v-else class="block h-6 w-6" aria-hidden="true" />
                        </DisclosureButton>
                    </div>
                    <div class="hidden lg:relative lg:z-10 lg:ml-4 lg:flex lg:items-center">
                        <button type="button" class="flex-shrink-0 bg-white rounded-full p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            <span class="sr-only">View notifications</span>
                            <BellIcon class="h-6 w-6" aria-hidden="true" />
                        </button>

                        <!-- Profile dropdown -->
                        <Menu v-if="$page.props.auth.user" as="div" class="flex-shrink-0 relative ml-4">
                            <div>
                                <MenuButton class="bg-white rounded-full flex focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                    <span class="sr-only">Open user menu</span>
                                    <img class="h-8 w-8 rounded-full" :src="user.imageUrl" alt="" />
                                </MenuButton>
                            </div>
                            <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                                <MenuItems class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-black ring-1 ring-black ring-opacity-5 py-1 focus:outline-none">
                                    <MenuItem as="div" v-for="item in computedUserNavigation" :key="item.name" v-slot="{ active }">
                                            <a v-if="item.name !== 'Sign out'" :href="item.href" :class="[active ? 'bg-gray-100 dark:bg-emerald-800 dark:text-white' : '', 'block py-2 px-4 text-sm text-gray-700 dark:text-white']">{{ item.name }}</a>
                                            <Link v-if="item.name === 'Sign out'" :href="item.href" method="post" as="a" :class="[active ? 'bg-gray-100 dark:bg-emerald-800 dark:text-white' : '', 'block py-2 px-4 text-sm text-gray-700 dark:text-white']">
                                                Log out
                                            </Link>
                                    </MenuItem>
                                </MenuItems>
                            </transition>
                        </Menu>
                        <template v-else>
                            <Link :href="route('login')" class="text-sm text-gray-700 underline">
                                Log in
                            </Link>

                            <Link v-if="canRegister" :href="route('register')" class="ml-4 text-sm text-gray-700 underline">
                                Register
                            </Link>
                        </template>
                    </div>
                </div>
                <nav v-if="$page.props.auth.user" class="hidden lg:py-2 lg:flex lg:space-x-8" aria-label="Global">
                    <template v-for="item in navigation" :key="item.name" >
                        <a :href="route(item.route)" :class="[item.route === currentRoute ? 'bg-emerald-800 text-white' : 'text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-emerald-800 hover:text-gray-900 dark:hover:text-white', 'rounded-md py-2 px-3 inline-flex items-center text-sm font-medium']" :aria-current="item.current ? 'page' : undefined">
                            {{ item.name }}
                        </a>
                        <FlyoutMenu v-if="item.route === 'home'"></FlyoutMenu>
                    </template>
                </nav>
            </div>

            <DisclosurePanel as="nav" class="lg:hidden" aria-label="Global">
                <div class="pt-2 pb-3 px-2 space-y-1">
                    <DisclosureButton v-for="item in navigation" :key="item.name" as="a" :href="item.href" :class="[item.route === currentRoute ? 'bg-emerald-800 text-gray-900' : 'text-gray-900 hover:bg-gray-50 dark:hover:bg-emerald-800 hover:text-gray-900 dark:hover:text-white', 'block rounded-md py-2 px-3 text-base font-medium']" :aria-current="item.current ? 'page' : undefined">{{ item.name }}</DisclosureButton>
                </div>
                <div class="border-t border-gray-200 pt-4 pb-3">
                    <div class="px-4 flex items-center">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full" :src="user.imageUrl" alt="" />
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-gray-800">{{ user.name }}</div>
                            <div class="text-sm font-medium text-gray-500">{{ user.email }}</div>
                        </div>
                        <button type="button" class="ml-auto flex-shrink-0 bg-white rounded-full p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            <span class="sr-only">View notifications</span>
                            <BellIcon class="h-6 w-6" aria-hidden="true" />
                        </button>
                    </div>
                    <div class="mt-3 px-2 space-y-1">
                        <DisclosureButton v-for="item in userNavigation" :key="item.name" as="a" :href="item.href" class="block rounded-md py-2 px-3 text-base font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-900">{{ item.name }}</DisclosureButton>
                    </div>
                </div>
            </DisclosurePanel>
        </Disclosure>
    </Popover>
    <!-- Page Heading -->
    <!-- Page Heading -->
    <header class="bg-white shadow" v-if="$slots.header">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <slot name="header" />
        </div>
    </header>

    <div v-if="$page.props.flash.success !== null || $page.props.flash.error !== null" class="py-2 md:flex-1 md:p-8 md:overflow-y-auto" scroll-region>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <flash-messages />
        </div>
    </div>

    <!-- Page Content -->
    <main class="bg-slate-100 dark:bg-gray-800">
        <div class="py-10">
            <slot/>
        </div>
    </main>

    <Footer class="border-t-2 dark:border-black"></Footer>
</template>

<script setup>

import { computed, ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/inertia-vue3'

import { Link } from '@inertiajs/inertia-vue3';
import FlashMessages from "@/Shared/FlashMessages";
import { CubeIcon } from '@heroicons/vue/outline';


import { Popover, Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { SearchIcon } from '@heroicons/vue/solid'
import { BellIcon, MenuIcon, XIcon } from '@heroicons/vue/outline'
import FlyoutMenu from "@/Components/Frontend/FlyoutMenu";
import Footer from "@/Components/Frontend/Footer";

const user = {
    name: 'Tom Cook',
    email: 'tom@example.com',
    imageUrl:
        'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
}
const navigation = [
    { name: 'Home', route: 'home'},
    { name: 'Team', route: 'dashboard' },
    { name: 'Projects', route: 'dashboard' },
    { name: 'Contact', route: 'contact' },
]
let userNavigation = [
    { name: 'Your Profile', href: '#' },
    { name: 'Settings', href: '#' },
    { name: 'Sign out', href: route('logout') },
]

defineProps({
    acl: Object,
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
})

const isAdmin = usePage().props.value.acl.is_admin
const currentRoute = usePage().props.value.currentRoute

    const computedUserNavigation = computed(() => {

    if(user !== undefined) {
        userNavigation.unshift(
            {name: 'Dashboard', href: route('dashboard'), route: 'dashboard'},
        )
    }

    if (isAdmin) {
        userNavigation.unshift(
            {name: 'Admin Panel', href: route('admin.dashboard'), route: 'admin.dashboard'},
        )
    }

    return userNavigation
})


</script>
