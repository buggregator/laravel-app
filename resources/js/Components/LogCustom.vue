<template>
    <div ref="trace" class="font-semibold">
        <h3 class="text-gray-800">{{ payload.content.content }}</h3>
    </div>
</template>

<script>
export default {
    props: {
        payload: Object
    }
}
</script>
