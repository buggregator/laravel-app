import moment from "moment";
import {createStore} from "vuex";
import SfdumpFunc from './Utils/dumper'
import RayEvent from "./Ray/event";
import SentryEvent from "./Sentry/event";
import SlackEvent from "./Slack/event";
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
        let json = JSON.parse(e.data);

        if (json.type == 'ray') {
            const event = new RayEvent(json.data);

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
        } else if (json.type == 'sentry') {
            const event = new SentryEvent(json.data);
            store.commit('pushEvent', event)
        } else if (json.type == 'slack') {
            const event = new SlackEvent(json.data);
            store.commit('pushEvent', event)
        }
    })
}

export const store = createStore({
    state() {
        return {
            wsConnected: false,
            currentScreen: generateScreenName(),
            screens: [],
            availableColors: ['gray', 'purple', 'green', 'orange', 'red', 'blue', 'pink'],
            selectedLabels: [],
            selectedColors: [],
            events: {}
        }
    },
    getters: {
        availableLabels: state => {
            let labels = [];

            _.each(state.events[state.currentScreen] || [], event => {
                labels = [...labels, ...event.labels]
            })


            return labels.filter((item, index) => labels.indexOf(item) == index)
        },
        filteredEvents: state => {
            let events = state.events[state.currentScreen] || []

            let filteredByColor = _.filter(events, event => {
                if (state.selectedColors.length > 0) {
                    return state.selectedColors.includes(event.color)
                }

                return true
            });

            return _.filter(filteredByColor, event => {
                if (state.selectedLabels.length > 0) {
                    if (event.labels.length === 0) {
                        return false
                    }

                    return state.selectedLabels.filter(
                        value => event.labels.includes(value)
                    ).length > 0
                }

                return true
            });
        }
    },
    mutations: {
        disconnectWs(state) {
            state.wsConnected = false
        },
        connectWs(state) {
            state.wsConnected = true
        },
        clearSelectedColors(state) {
            state.selectedColors = []
        },
        selectColor(state, color) {
            if (state.selectedColors.includes(color)) {
                state.selectedColors = state.selectedColors.filter(c => color != c)
            } else {
                state.selectedColors.push(color)
            }
        },
        selectLabel(state, label) {
            if (state.selectedLabels.includes(label)) {
                state.selectedLabels = state.selectedLabels.filter(l => label != l)
            } else {
                state.selectedLabels.push(label)
            }
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

        }
    }
})
