<template>
    <div class="md:flex border-b border-gray-300 p-3 md:p-5 space-y-10 md:space-y-0 md:space-x-5 lg:space-x-10">
        <div class="w-full md:w-1/4 relative">
            <div class="w-3 h-3 rounded-full absolute top-2 left-0" :class="color"></div>
            <div class="w-5 h-5 absolute top-1 left-6 cursor-pointer text-red-700" @click="deleteEvent">
                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 24 24" fill="currentColor">
                    <g id="close">
                        <path id="x"
                              d="M18.717 6.697l-1.414-1.414-5.303 5.303-5.303-5.303-1.414 1.414 5.303 5.303-5.303 5.303 1.414 1.414 5.303-5.303 5.303 5.303 1.414-1.414-5.303-5.303z"/>
                    </g>
                </svg>
            </div>

            <div class="ml-12 flex flex-wrap gap-2 justify-end">
                <Label :text="date"></Label>

                <div v-if="hasLabels" class="flex gap-2">
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
import {useStore} from "vuex";

export default {
    components: {Label, EventPayload},
    props: {
        event: Object
    },
    setup() {
        const store = useStore();

        return {
            store
        }
    },
    methods: {
        deleteEvent() {
            this.store.commit('deleteEvent', this.event.uuid)
        }
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
