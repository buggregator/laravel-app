<template>
    <section class="py-5 px-4 md:px-6 lg:px-8 border-b">
        <h1 class="mb-1 text-lg bold">Request Body</h1>
        <p>
<code>
        {{ event.request.body }}
            </code>
        </p>
    </section>
</template>

<script>

export default {
    components: {
    },
    props: {
        event: Object
    }
}
</script>
