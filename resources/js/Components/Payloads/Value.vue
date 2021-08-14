<template>
    <Dump v-for="value in payload.content.values" :value="value" :class="textSize"/>
</template>

<script>
import Dump from "./Dump";
export default {
    components: {Dump},
    props: {
        payload: Object
    },
    computed: {
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
