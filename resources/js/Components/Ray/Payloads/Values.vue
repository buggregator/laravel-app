<template>
    <Dump :value="value" :class="textSize"/>
</template>

<script>
import Dump from "@/Components/UI//Dump";

export default {
    components: {Dump},
    props: {
        payload: Object
    },
    computed: {
        dumpId() {
            const matches = this.value.match(/(sf\-dump\-[0-9]+)/i)
            if (matches) {
                return matches[0]
            }

            return null
        },
        value() {
            return this.payload.content.values[0] || ''
        },
        textSize() {
            let size = '';

            if (this.payload.content.size) {
                size = `text-${this.payload.content.size}`
            }

            return size
        }
    }
}
</script>
