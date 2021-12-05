<template>
    <div class="code-snippet">
        <ssh-pre :language="language">
            <slot></slot>
        </ssh-pre>

        <button type="button" @click="doCopy" class="code-snippet__btn-copy" :class="{'active': copied}">
            <CopyIcon class="w-2 h-2 " />
            copy
        </button>
    </div>
</template>

<script>
import SshPre from 'simple-syntax-highlighter'
import {copyText} from 'vue3-clipboard'
import CopyIcon from "./Icons/CopyIcon";

export default {
    components: {CopyIcon, SshPre},
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
