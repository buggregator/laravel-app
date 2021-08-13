<template>
    <span class="border py-1 px-3 rounded-sm text-xs md:text-sm inline-block">
        {{ text }}
    </span>
</template>

<script>
export default {
    props: {
        text: String
    }
}
</script>
