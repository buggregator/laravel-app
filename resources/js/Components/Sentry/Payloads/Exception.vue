<template>
    <div>
        <Link as="div" :href="event.route.show" class="cursor-pointer pb-2">
            <h3 class="text-blue-800 mb-1 font-semibold">
                {{ event.payload.type }}
            </h3>
            <div class="text-gray-600 text-sm break-all">
                {{ event.payload.value }}
            </div>
        </Link>
        <div class="border border-purple-200 flex-col justify-center" v-if="frames > 0">
            <File :file="file" v-for="(file, i) in stacktrace" :collapsed="i !== 0"/>
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
