import moment from "moment";
import {createStore} from "vuex";
import SfdumpFunc from '../dumper'
import {listenRayEvents} from "./websocket";
import {RayEvent} from "./event";

function generateScreenName() {
    return'Debug at ' + moment().format('hh:mm:ss')
}

export function init() {
    window.Sfdump = SfdumpFunc(window.document)

    const host = process.env.MIX_WS_SERVER_HOST || '127.0.0.1'
    const port = process.env.MIX_WS_SERVER_PORT || 23517

    listenRayEvents(host, port, function (e) {
        const event = new RayEvent(JSON.parse(e.data));

        if (event.isType('clear_all')) {
            store.commit('clearLogs')
        } else if (event.isType('new_screen')) {
            store.commit('switchScreen', event.content('name'))
        } else {
            store.commit('pushLog', event)
        }
    })
}

export const store = createStore({
    state() {
        return {
            currentScreen: generateScreenName(),
            screens: [],
            logs: {}
        }
    },
    mutations: {
        clearLogs(state) {
            state.logs = {}
            state.screens = []
        },
        ensureScreenExists(state, screen) {
            if (!state.screens.includes(screen)) {
                state.screens.push(screen)
            }

            if (!state.logs.hasOwnProperty(screen)) {
                state.logs[screen] = {}
            }
        },
        switchScreen(state, screen) {
            if (_.isEmpty(screen)) {
                screen = generateScreenName()
            }

            state.currentScreen = screen
            this.ensureScreenExists(state, screen)
        },
        pushLog(state, event) {
            this.ensureScreenExists(state, state.currentScreen)

            if (state.logs[state.currentScreen].hasOwnProperty(event.uuid)) {
                state.logs[state.currentScreen][event.uuid] = _.merge(state.logs[state.currentScreen][event.uuid], event)
            } else {
                state.logs[state.currentScreen][event.uuid] = event
            }
        }
    }
})
