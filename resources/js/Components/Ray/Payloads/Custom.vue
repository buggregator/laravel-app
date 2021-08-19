<template>
    <div ref="trace">
        <div class="break-all" v-html="payload.content.content"></div>
    </div>
</template>

<script>
export default {
    props: {
        payload: Object
    }
}
</script>
