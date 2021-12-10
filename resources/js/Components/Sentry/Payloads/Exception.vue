<template>
    <div class="sentry-exception">
        <Link as="div" :href="event.route.show" class="sentry-exception__link">
            <h3 class="sentry-exception__title">
                {{ event.payload.type }}
            </h3>
            <div class="sentry-exception__text">
                {{ event.payload.value }}
            </div>
        </Link>
        <div class="sentry-exception__files" v-if="frames > 0">
            <File :file="file" v-for="(file, i) in stacktrace" :collapsed="i !== 0" class="sentry-exception__file"/>
        </div>
    </div>
</template>

<script>
import {Link} from '@inertiajs/inertia-vue3'
import File from "../UI/File";

export default {
    components: {File, Link},
    props: {
        event: Object,
        frames: {
            type: Number,
            default: () => 5
        }
    },
    computed: {
        stacktrace() {
            return this.event.stacktrace.slice(0, this.frames)
        }
    }
}
</script>
