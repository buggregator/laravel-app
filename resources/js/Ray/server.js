import moment from "moment";
import {createStore} from "vuex";
import SfdumpFunc from '../dumper'
import {RayEvent} from "./event";
import {notify} from "@kyvg/vue3-notification";
import ReconnectingWebSocket from 'reconnecting-websocket';

function generateScreenName() {
    return 'Debug session ' + moment().format('hh:mm:ss')
}

function createWsConnection(host, port) {
    return new ReconnectingWebSocket(`ws://${host}:${port}/`, [], {
        connectionTimeout: 3000,
        maxRetries: 10,
    })
}

function listenRayEvents(host, port, callback) {
    const socket = createWsConnection(host, port)

    socket.onmessage = callback

    socket.addEventListener('open', () => {
        store.commit('connectWs')
    });

    socket.addEventListener('close', () => {
        store.commit('disconnectWs')
    });
}


export function init() {
    window.Sfdump = SfdumpFunc(window.document)
    const [host, port] = window.location.host.split(':')

    listenRayEvents(host, port, function (e) {
        const event = new RayEvent(JSON.parse(e.data));

        if (event.isType('clear_all')) {
            store.commit('clearEvents')
        } else if (event.isType('new_screen')) {
            store.commit('switchScreen', event.content('name'))
        } else if (event.isType('remove')) {
            store.commit('deleteEvent', event.uuid)
        } else if (event.isType('hide')) {
            store.commit('toggleEventState', [event.uuid, true])
        } else if (event.isType('show')) {
            store.commit('toggleEventState', [event.uuid, false])
        } else if (event.isType('notify')) {
            notify({
                title: "Hello from Ray",
                text: event.payloads[0].content.value,
                duration: -1
            })
        } else {
            store.commit('pushEvent', event)
        }
    })
}

const maxEvents = 10

export const store = createStore({
    state() {
        return {
            wsConnected: false,
            currentScreen: generateScreenName(),
            screens: [],
            events: {}
        }
    },
    mutations: {
        disconnectWs(state) {
            state.wsConnected = false
        },
        connectWs(state) {
            state.wsConnected = true
        },
        clearEvents(state) {
            state.events[state.currentScreen] = {}
            state.screens = state.screens.filter((screen) => {
                return screen != state.currentScreen;
            })

            if (state.screens.length > 0) {
                state.currentScreen = state.screens[state.screens.length - 1]
            }
        },
        toggleEventState(state, data) {
            if (state.events[state.currentScreen].hasOwnProperty(data[0])) {
                state.events[state.currentScreen][data[0]].setCollapsed(data[1])
            }
        },
        deleteEvent(state, uuid) {
            state.events[state.currentScreen] = _.keyBy(
                _.filter(state.events[state.currentScreen], (e) => e.uuid != uuid),
                'uuid'
            );
        },
        ensureScreenExists(state, screen) {
            if (!state.screens.includes(screen)) {
                state.screens.push(screen)
            }

            if (!state.events.hasOwnProperty(screen)) {
                state.events[screen] = {}
            }
        },
        switchScreen(state, screen) {
            if (_.isEmpty(screen)) {
                screen = generateScreenName()
            }

            state.currentScreen = screen

            this.commit('ensureScreenExists', screen)
        },
        pushEvent(state, event) {
            this.commit('ensureScreenExists', state.currentScreen)

            // Merge events into one by uuid (colors|labels|...)
            if (state.events[state.currentScreen].hasOwnProperty(event.uuid)) {
                state.events[state.currentScreen][event.uuid].merge(event)
            } else {
                state.events[state.currentScreen][event.uuid] = event
            }

            // Remain only last events
            // state.events[state.currentScreen] = _.keyBy(
            //     Object.entries(state.events[state.currentScreen]).slice(maxEvents * -1).map(entry => entry[1]),
            //     'uuid'
            // )

        }
    }
})
