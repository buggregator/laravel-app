import {createStore} from "vuex";
import moment from "moment";
import axios from "axios";
import route from "ziggy-js/src/js";

function generateScreenName() {
    return 'Debug session ' + moment().format('hh:mm:ss')
}

const theme = {
    namespaced: true,
    state: () => ({
        darkMode: false
    }),
    actions: {
        detect(context) {
            context.commit(
                'toggle',
                (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches))
            )
        }
    },
    mutations: {
        toggle(state, value) {
            if (value === true) {
                localStorage.theme = 'dark'
                state.darkMode = true
                document.documentElement.classList.add('dark')
            } else {
                localStorage.theme = 'light'
                state.darkMode = false
                document.documentElement.classList.remove('dark')
            }
        }
    }
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

const httpdump = {
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

const sentry = {
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

const sentryTransaction = {
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

const inspector = {
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
        ws, smtp, sentry, sentryTransaction, terminal, inspector, theme, httpdump
    },

    state() {
        return {
            currentScreen: generateScreenName(),
            screens: [],
            availableColors: ['gray', 'purple', 'green', 'orange', 'red', 'blue', 'pink'],
            selectedLabels: [],
            selectedColors: [],
            events: {},
            unReadEvents: [],
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
        getUnreadEvents:(state) => {
          return state.unReadEvents;
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
        eventsByType: state => type => {
            let events = [];

            Object.keys(state.events).forEach(key => {
                state.events[key].filter(e => e.app != type).forEach(e => [
                    events.push(e)
                ])
            })

            return events
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
    actions: {
        clearEvents({}, type) {
            axios.delete(route('events.clear'), {data: {type}})
        },
        deleteEvent({}, event) {
            axios.delete(route('event.delete', event.id))
        },
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
        clearEvents(state, type) {
            if (type) {
                Object.keys(state.events).forEach(key => {
                    state.events[key] = state.events[key].filter(e => e.app != type)
                })

                return
            }

            state.events = {}
        },
        deleteEvent(state, uuid) {
            state.events[state.currentScreen] = state.events[state.currentScreen].filter(e => e.uuid != uuid)
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
        pushUnreadEvent(state, type) {
            state.unReadEvents.push(type)
        },
        deleteUnreadEvent(state, type) {
            state.unReadEvents = state.unReadEvents.filter(e =>e !== type)
        },
        clearUnreadEvents(state) {
            state.unReadEvents = []
        },
        pushEvent(state, event) {
            if (!event) {
                return;
            }

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
