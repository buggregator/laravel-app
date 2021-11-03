<template>
    <Dump :value="value" :class="textSize"/>
</template>

<script>
import Dump from "@/Components/UI/Dump";

export default {
    components: {Dump},
    props: {
        payload: Object
    },
    computed: {
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
