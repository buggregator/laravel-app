<template>
    <div class="sentry-exception flex items-start justify-between flex-wrap">
        <Link as="div" :href="event.route.show" class="sentry-exception__link flex-grow">
            <span class="float-left">
                <h3 class="sentry-exception__title">
                    {{ event.payload.type }}
                </h3>
            </span>
            <span class="float-left margin-space-10">
                <h6>
                    {{ event.transactionName }}
                </h6>
            </span>
            <div class="sentry-exception__text clear-both">
                {{ event.payload.value }}
            </div>
        </Link>
        <JsonChip :href="event.route.json" class="ml-auto"/>
        <div class="sentry-exception__files w-full" v-if="frames > 0">
            <File :file="file" v-for="(file, i) in stacktrace" :collapsed="i !== 0" class="sentry-exception__file"/>
        </div>
    </div>
</template>

<script>
import {Link} from '@inertiajs/inertia-vue3'
import File from "../UI/File";
import JsonChip from "@/Components/UI/JsonChip";

export default {
    components: {JsonChip, File, Link},
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
