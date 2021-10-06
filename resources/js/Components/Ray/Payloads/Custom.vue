<template>
    <Dump :value="payload.content.content"/>
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
