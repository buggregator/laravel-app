<template>
    <div class="md:flex border-b p-5 space-y-10 md:space-y-0 md:space-x-10">
        <div class="w-full md:w-1/4 relative">
            <div class="w-3 h-3 rounded-full absolute top-0 left-0" :class="color"></div>

            <div class="flex md:flex-col md:items-end md:space-y-3 space-x-3 justify-end">
                <Label :text="date"></Label>

                <div v-if="hasLabels" class="flex space-x-4 md:mt-2">
                    <Label v-for="label in labels" :text="label" class="font-semibold"></Label>
                </div>
            </div>
        </div>
        <div class="w-full md:w-3/4 flex-col space-y-5">
            <EventPayload v-for="payload in event.payloads" :payload="payload"/>
        </div>
    </div>
</template>

<script>
import EventPayload from "./Payloads/EventPayload";
import Label from "./UI/Label";

export default {
    components: {Label, EventPayload},
    props: {
        event: Object
    },
    computed: {
        date() {
            return this.event.date.format('MMMM Do, HH:mm:ss')
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
