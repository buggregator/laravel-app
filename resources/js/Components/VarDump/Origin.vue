<template>
    <div class="flex space-x-4 items-center">
        <span class="text-gray-400 underline text-2xs cursor-help" :title="`${origin.file}:${origin.line_number}`">
            {{ origin.name }}:{{ origin.line_number }}
        </span>
    </div>
</template>

<script>
import Label from "@/Components/UI/Label";
export default {
    components: {Label},
    props: {
        origin: Object
    }
}
</script>
