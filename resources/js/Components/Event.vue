<template>
    <div class="flex border-b p-5 gap-8">
        <div class="w-1/4">
            <time class="text-gray-600 font-semibold text-sm flex justify-between items-center">
                <div class="w-3 h-3 rounded-full" :class="color"></div>

                <span class="border py-1 px-3 rounded-smtext-sm inline-block">
                    {{ date }}
                </span>
            </time>

            <div v-if="hasLabels" class="flex flex-row-reverse mt-2">
                <span v-for="label in labels" class="border py-1 px-3 text-gray-500 rounded-sm text-sm font-semibold inline-block ml-1">
                    {{ label }}
                </span>
            </div>

        </div>
        <div class="w-3/4">
            <EventPayload v-for="payload in event.payloads" :payload="payload" />
        </div>
    </div>
</template>

<script>
import EventPayload from "./Payloads/EventPayload";

export default {
    components: {EventPayload},
    props: {
        event: Object
    },
    computed: {
        date() {
            return this.event.date
        },
        color() {
            const color = this.event.color

            switch (color) {
                case 'gray':
                    return 'bg-gray-300';
            }

            return `bg-${color}-500`
        },
        labels() {
            return this.event.labels
        },
        hasLabels() {
            return this.labels.length > 0
        }
    }
}
</script>
