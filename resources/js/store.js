import {createStore} from "vuex";
import moment from "moment";
import axios from "axios";

function generateScreenName() {
    return 'Debug session ' + moment().format('hh:mm:ss')
}

const ws = {
    namespaced: true,
    state: () => ({
        connected: false
    }),
    mutations: {
        disconnect(state) {
            state.connected = false
        },
        connect(state) {
            state.connected = true
        }
    }
}

const terminal = {
    namespaced: true,
    state: () => ({
        messages: []
    }),
    mutations: {
        clear(state) {
            state.messages = []
        },
        push(state, message) {
            state.messages.push(message)
        },
    }
}

const smtp = {
    namespaced: true,
    state: () => ({
        events: [],
        event: null
    }),
    mutations: {
        clearEvents(state) {
            state.events = []
        },
        pushEvent(state, event) {
            if (state.events.find(e => event.uuid == e.uuid)) {
                return
            }

            state.events.unshift(event)
        },
        openEvent(state, event) {
            state.event = event
        },
        deleteEvent(state, event) {
            state.events = state.events.filter(e => event.uuid == e.uuid)
        },
        closeEvent(state) {
            state.event = null
        }
    }
}

export const store = createStore({
    modules: {
        ws, smtp, terminal
    },

    state() {
        return {
            currentScreen: generateScreenName(),
            screens: [],
            availableColors: ['gray', 'purple', 'green', 'orange', 'red', 'blue', 'pink'],
            selectedLabels: [],
            selectedColors: [],
            events: {},
        }
    },
    getters: {
        screens: state => {
            let screens = state.screens

            if (screens.length === 0) {
                screens.push(state.currentScreen)
            }

            return screens
        },
        eventByUuid: (state) => (uuid) => {
            return state.events[state.currentScreen].find(event => event.uuid == uuid)
        },
        totalEvents: (state, getters) => {
            return getters.filteredEvents.length
        },
        availableLabels: state => {
            let labels = [];
            let events = state.events[state.currentScreen] || []

            events.forEach(event => {
                labels = [...labels, ...event.labels]
            })

            return labels.filter((item, index) => labels.indexOf(item) == index)
        },
        filteredEvents: state => {
            let events = state.events[state.currentScreen] || []

            return events
                // Filter by color
                .filter(event => {
                    if (state.selectedColors.length > 0) {
                        return state.selectedColors.includes(event.color)
                    }

                    return true
                })
                // Filter by labels
                .filter(event => {
                    if (state.selectedLabels.length > 0) {
                        if (event.labels.length === 0) {
                            return false
                        }

                        return state.selectedLabels.filter(
                            value => event.labels.includes(value)
                        ).length > 0
                    }

                    return true
                })
        }
    },
    mutations: {
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
        toggleEventState(state, data) {
            const event = this.getters.eventByUuid(data[0])

            if (event) {
                event.setCollapsed(data[1])
            }
        },
        clearEvents(state) {
            state.events = {}
            state.screens = []
            axios.delete(`/events`)
        },
        deleteEvent(state, uuid) {
            state.events[state.currentScreen] = state.events[state.currentScreen].filter(e => e.uuid != uuid)
            axios.delete(`/event/${uuid}`)
        },
        switchScreen(state, screen) {
            if (_.isEmpty(screen)) {
                screen = generateScreenName()
            }

            state.currentScreen = screen

            this.commit('ensureScreenExists', screen)
        },
        ensureScreenExists(state, screen) {
            if (!state.screens.includes(screen)) {
                state.screens.unshift(screen)
            }

            if (!state.events.hasOwnProperty(screen)) {
                state.events[screen] = []
            }
        },
        pushEvent(state, event) {
            this.commit('ensureScreenExists', state.currentScreen)

            const existsEvent = this.getters.eventByUuid(event.uuid)

            if (existsEvent) {
                // Merge events into one by uuid (colors|labels|...)
                existsEvent.merge(event)
            } else {
                state.events[state.currentScreen].unshift(event)
            }
        }
    }
})
