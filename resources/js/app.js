require('./bootstrap');
import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/inertia-vue3';
import Notifications from '@kyvg/vue3-notification'
import {InertiaProgress} from '@inertiajs/progress';
import {init as rayInit} from "./server";
import {store} from './store'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Ray server';

rayInit()

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({el, app, props, plugin}) {

        const vueApp = createApp({render: () => h(app, props)})

        vueApp.config.errorHandler = () => null;
        vueApp.config.warnHandler = () => null;

        return vueApp
            .use(plugin)
            .use(store)
            .use(Notifications)
            .mixin({methods: {route}})
            .mount(el);
    },
});

InertiaProgress.init({color: '#4B5563'});
