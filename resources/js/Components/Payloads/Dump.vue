<template>
    <div v-html="value" ref="code" class="text-blue-700 font-semibold w-2/3"></div>
</template>

<script>

export default {
    props: {
        value: String
    },
    mounted() {
        let scripts = this.$refs.code.getElementsByTagName("script")
        scripts[0].remove()
        eval(scripts[0].innerHTML)
    }
}
</script>
