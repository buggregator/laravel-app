<template>
    <div ref="trace">
        <h3 class="text-gray-800 text-sm md:text-base mb-2">
            View: <code class="font-semibold">{{ payload.content.view_path_relative_to_project_root }}</code>
        </h3>
        <Dump :value="payload.content.data"/>
    </div>
</template>

<script>
import Dump from "./Dump";

export default {
    components: {Dump},
    props: {
        payload: Object
    }
}
</script>
