<template>
    <div class="relative">
        <ssh-pre :language="language">
            <slot></slot>
        </ssh-pre>

        <button type="button" @click="doCopy"
                class="flex items-center gap-x-1 absolute top-2 right-2 px-1 bg-white hover:bg-blue-500 border border-blue-500 text-blue-500 hover:text-white transition-all text-xs font-bold"
                :class="{'transform scale-110 bg-green-500 hover:bg-green-500': copied}"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 460 460" class="w-2 h-2 fill-current">
                <path d="M426 0H172c-18 0-33 15-33 33v77h30V33c0-2 1-3 3-3h254c2 0 3 1 3 3v254c0 2-1 3-3 3h-75v30h75c18 0 33-15 33-33V33c0-18-15-33-33-33z"/>
                <path d="M288 140H34c-18 0-33 15-33 33v254c0 18 15 33 33 33h254c18 0 33-15 33-33V173c0-18-15-33-33-33zm0 290H34c-2 0-3-1-3-3V173c0-2 1-3 3-3h254c2 0 3 1 3 3v254c0 2-1 3-3 3z"/>
            </svg>
            copy
        </button>
    </div>
</template>

<script>
import SshPre from 'simple-syntax-highlighter'
import {copyText} from 'vue3-clipboard'

export default {
    components: {SshPre},
    props: {
        language: {
            type: String,
            default: () => null
        }
    },
    data() {
        return {
            copied: false
        }
    },
    methods: {
        doCopy() {
            this.copied = true
            setTimeout(() => this.copied = false, 100)

            let text = '';
            this.$slots.default().forEach(vnode => {
                text += vnode.children
            })

            copyText(text, undefined, (error, event) => {

            })
        }
    }
}
</script>
