<template>
    <div v-html="value" ref="code" class="rounded-sm text-blue-700 font-semibold"></div>
</template>

<script>

export default {
    props: {
        value: String
    },
    mounted() {
        let scripts = this.$refs.code.getElementsByTagName("script")
        if (scripts.length > 0) {
            scripts[0].remove()
            eval(scripts[0].innerHTML)
        }
    }
}
</script>
