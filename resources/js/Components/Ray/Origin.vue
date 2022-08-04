<template>
    <div class="event-footer justify-between">
        <span class="event-footer__name" :title="`${origin.file}:${origin.line_number}`">
            {{ filename }}:{{ origin.line_number }}
        </span>
        <Host v-if="origin.hostname" :name="origin.hostname" class="event-footer__host"/>
    </div>
</template>

<script>
import Host from "@/Components/UI/Host";
import Label from "@/Components/UI/Label";
export default {
    components: {Label, Host},
    props: {
        origin: Object
    },
    computed: {
        filename() {
            return this.origin.file.replace(/^.*[\\\/]/, '')
        }
    }
}
</script>
