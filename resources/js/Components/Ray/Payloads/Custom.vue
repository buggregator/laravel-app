<template>
    <Dump :value="payload.content.content" class="event-ray__custom"/>
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
