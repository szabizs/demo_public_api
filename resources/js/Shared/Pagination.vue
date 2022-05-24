<template>
    <div v-if="links.length > 3">
        <div class="flex flex-wrap">
            <template v-for="(link, key) in links">
                <div v-if="link.url === null" :key="key" class="my-2 mr-1 px-4 py-3 text-gray-400 text-sm leading-4 border rounded" v-html="link.label" />
                <Link v-else :key="`link-${key}`" class="my-2 mr-1 px-4 py-3 bg-green-50 focus:text-indigo-500 text-sm leading-4 hover:bg-white border focus:border-indigo-500 rounded" :class="{ 'bg-white': link.active }" :href="link.url" v-html="link.label" />
            </template>
        </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'
export default {
    components: {
        Link,
    },
    props: {
        links: Array,
    },
}
</script>
