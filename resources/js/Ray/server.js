import moment from "moment";
import {createStore} from "vuex";
import SfdumpFunc from '../dumper'
import {listenRayEvents} from "./websocket";
import {RayEvent} from "./event";

function generateScreenName() {
    return 'Debug at ' + moment().format('hh:mm:ss')
}

export function init() {
    window.Sfdump = SfdumpFunc(window.document)

    const host = window.location.hostname
    const port = process.env.MIX_WS_SERVER_PORT || 23517

    listenRayEvents(host, port, function (e) {
        const event = new RayEvent(JSON.parse(e.data));

        if (event.isType('clear_all')) {
            store.commit('clearEvents')
        } else if (event.isType('new_screen')) {
            store.commit('switchScreen', event.content('name'))
        } else {
            store.commit('pushEvent', event)
        }
    })
}

export const store = createStore({
    state() {
        return {
            currentScreen: generateScreenName(),
            screens: [],
            events: {}
        }
    },
    mutations: {
        clearEvents(state) {
            state.events = {}
            state.screens = []
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
        }
        ,
        switchScreen(state, screen) {
            if (_.isEmpty(screen)) {
                screen = generateScreenName()
            }

            state.currentScreen = screen

            this.commit('ensureScreenExists', screen)
        }
        ,
        pushEvent(state, event) {
            this.commit('ensureScreenExists', state.currentScreen)

            if (state.events[state.currentScreen].hasOwnProperty(event.uuid)) {
                state.events[state.currentScreen][event.uuid] = _.merge(event, state.events[state.currentScreen][event.uuid])
            } else {
                state.events[state.currentScreen][event.uuid] = event
            }
        }
    }
})
