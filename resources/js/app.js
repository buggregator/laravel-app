require('./bootstrap');

import {createApp, h} from 'vue';
import {createStore} from 'vuex'
import {createInertiaApp} from '@inertiajs/inertia-vue3';
import {InertiaProgress} from '@inertiajs/progress';
import SfdumpFunc from './dumper'
import moment from 'moment';

window.Sfdump = SfdumpFunc(document)

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

const host = process.env.MIX_WS_SERVER_HOST || '127.0.0.1'
const port = process.env.MIX_WS_SERVER_PORT || 23517
let WebSocketClient = require('websocket').w3cwebsocket;
let client = new WebSocketClient(`ws://${host}:${port}/`);

client.onmessage = function (e) {
    const log = JSON.parse(e.data);

    if (log.payloads[0].type == 'clear_all') {
        store.commit('clearLogs');
    } else if (log.payloads[0].type == 'new_screen') {
        store.commit('switchScreen', log.payloads[0].content.name);
    } else {
        store.commit('pushLog', log);
    }
}

const store = createStore({
    state() {
        return {
            currentScreen: moment().format('hh:mm:ss'),
            screens: [],
            logs: {}
        }
    },
    mutations: {
        clearLogs(state) {
            state.logs = {}
            state.screens = []
        },
        switchScreen(state, screen) {
            if (screen.length === 0) {
                screen = moment().format('hh:mm:ss')
            }

            state.currentScreen = screen

            if (!state.screens.includes(state.currentScreen)) {
                state.screens.push(state.currentScreen)
            }

            if (!state.logs.hasOwnProperty(state.currentScreen)) {
                state.logs[state.currentScreen] = {}
            }
        },
        pushLog(state, log) {
            if (!state.screens.includes(state.currentScreen)) {
                state.screens.push(state.currentScreen)
            }

            if (!state.logs.hasOwnProperty(state.currentScreen)) {
                state.logs[state.currentScreen] = {}
            }

            if (state.logs[state.currentScreen].hasOwnProperty(log.uuid)) {
                state.logs[state.currentScreen][log.uuid] = _.merge(state.logs[state.currentScreen][log.uuid], log)
            } else {
                state.logs[state.currentScreen][log.uuid] = log
            }
        }
    }
})

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({el, app, props, plugin}) {
        return createApp({render: () => h(app, props)})
            .use(plugin)
            .use(store)
            .mixin({methods: {route}})
            .mount(el);
    },
});

InertiaProgress.init({color: '#4B5563'});
