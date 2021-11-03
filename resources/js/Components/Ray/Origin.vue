<template>
    <div class="flex space-x-4 items-center">
        <Host v-if="origin.hostname" :name="origin.hostname" />
        <span class="text-gray-400 underline text-2xs cursor-help" :title="`${origin.file}:${origin.line_number}`">
            {{ filename }}:{{ origin.line_number }}
        </span>
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
