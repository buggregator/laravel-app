<template>
    <pre class="text-xs" v-html="payload.content.value"/>
</template>

<script>
export default {
    props: {
        payload: Object
    }
}
</script>
