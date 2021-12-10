<template>
    <div class="event-ray__view">
        <h3 class="event-ray__view-title">
            View: <code class="event-ray__view-code">{{ payload.content.view_path_relative_to_project_root }}</code>
        </h3>
        <Dump :value="payload.content.data"/>
    </div>
</template>

<script>
import Dump from "@/Components/UI/Dump";

export default {
    components: {Dump},
    props: {
        payload: Object
    }
}
</script>
