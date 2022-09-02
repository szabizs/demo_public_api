require('./bootstrap');

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import PublicLayout from "@/Layouts/PublicLayout";
import GlobalAlert from '@/Components/GlobalAlert.vue'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: name => {
        const page = require(`./Pages/${name}`).default
        // page.layout = page.layout || PublicLayout
        return page
    },
    setup({ el, app, props, plugin }) {

        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .component('GlobalAlert', GlobalAlert)
            .mixin({ methods: { route } })
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
