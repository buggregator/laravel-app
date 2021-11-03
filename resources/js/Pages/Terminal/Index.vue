<template>
    <MainLayout title="Terminal">
        <main class="flex flex-grow w-full">
            <div class="w-full h-full" id="terminal"></div>
        </main>
    </MainLayout>
</template>

<script>
import MainLayout from "@/Components/Layout/Main"
import {Terminal} from 'xterm'
import {FitAddon} from 'xterm-addon-fit'
import {useStore} from "vuex";
import {computed} from "vue";

const settings = {
    fontFamily: '"Cascadia Code", Menlo, monospace',
    fontSize: 12,
    theme: {
        foreground: '#F8F8F8',
        background: '#0C0C0C',
        selection: '#5DA5D533',
        black: '#1E1E1D',
        brightBlack: '#262625',
        red: '#CE5C5C',
        brightRed: '#FF7272',
        green: '#288b28',
        brightGreen: '#72FF72',
        yellow: '#CCCC5B',
        brightYellow: '#FFFF72',
        blue: '#5D5DD3',
        brightBlue: '#7279FF',
        magenta: '#BC5ED1',
        brightMagenta: '#E572FF',
        cyan: '#5DA5D5',
        brightCyan: '#72F0FF',
        white: '#F8F8F8',
        brightWhite: '#FFFFFF'
    },
    cursorBlink: true
}

const commands = {
    help: {
        f() {
            this.term.writeln([
                'Try some of the commands below.',
                '',
                ...Object.keys(commands).map(e => `  ${e.padEnd(10)} ${commands[e].description}`)
            ].join('\n\r'));
            this.prompt();
        },
        description: 'Prints this help message',
    },
    clear: {
        f() {
            this.term.clear();
            this.store.commit('terminal/clear')
            this.term.prompt();
        },
        description: 'Clears console'
    }
}

export default {
    components: {
        MainLayout
    },
    setup() {
        const store = useStore();
        const messages = computed(() => store.state.terminal.messages)
        const terminalInit = false

        return {
            messages, store, terminalInit
        }
    },
    destroyed() {
        this.term.clear()
    },
    mounted() {
        this.initTerminal();
        this.sendMessages(this.messages)
    },

    methods: {
        initTerminal() {
            if (!this.terminalInit) {
                this.term = new Terminal(settings)

                const fitAddon = new FitAddon()
                this.term.loadAddon(fitAddon)

                this.term.open(document.getElementById('terminal'))
                fitAddon.fit()

                this.term.prompt = () => {
                    this.term.write('\r\n$ ');
                };

                let command = '';

                this.term.onData(e => {
                    switch (e) {
                        case '\u0003': // Ctrl+C
                            this.term.write('^C');
                            this.prompt();
                            break;
                        case '\r': // Enter
                            this.runCommand(command);
                            command = '';
                            break;
                        case '\u007F': // Backspace (DEL)
                            // Do not delete the prompt
                            if (this.term._core.buffer.x > 2) {
                                this.term.write('\b \b');
                                if (command.length > 0) {
                                    command = command.substr(0, command.length - 1);
                                }
                            }
                            break;
                        default: // Print all other characters for demo
                            if (e >= String.fromCharCode(0x20) && e <= String.fromCharCode(0x7B) || e >= '\u00a0') {
                                command += e;
                                this.term.write(e);
                            }
                    }
                });

                ws.listen('terminal', 'Websocket\\TerminalWrite', (payload) => {
                    this.sendMessages(payload.payload.message.split("\n"))
                })

                this.terminalInit = true;
            }
        },
        runCommand(text) {
            const command = text.trim().split(' ')[0];
            if (command.length > 0) {
                this.term.writeln('');
                if (command in commands) {
                    const cmd = commands[command].f;
                    cmd.bind(this)();
                    return;
                }
                this.term.writeln(`${command}: command not found`);
            }
            this.prompt();
        },
        prompt() {
            this.term.write('\r\n$ ');
        },
        sendMessages(messages) {
            const isEmpty = messages.filter(line => line !== '').length === 0

            messages.forEach(msg => {
                this.term.write('\b\b')
                this.term.writeln(msg)
            })

            if (!isEmpty) {
                this.prompt()
            }
        }
    }
}
</script>

<style>
.xterm {
    height: 100%;
    padding: 15px;
    overflow: hidden;
}

.xterm-viewport {
    width: 100% !important;
}
</style>
