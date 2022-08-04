<template>
    <div class="event-var-dump__footer">
        <span class="event-var-dump__footer-text" :title="`${origin.file}:${origin.line_number}`">
            {{ origin.name }}:{{ origin.line_number }}
        </span>
        <JsonChip :href="json" class="ml-auto" />
    </div>
</template>

<script>
import Label from "@/Components/UI/Label";
import JsonChip from "@/Components/UI/JsonChip";
export default {
    components: {JsonChip, Label},
    props: {
        origin: Object,
        json: String,
    }
}
</script>
