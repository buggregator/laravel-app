<template>
    <div ref="trace" class="font-semibold">
        <div v-html="payload.content.content"></div>
    </div>
</template>

<script>
export default {
    props: {
        payload: Object
    }
}
</script>
