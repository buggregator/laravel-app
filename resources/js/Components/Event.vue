<template>
    <div ref="event" :class="{'collapsed': event.collapsed, 'open': !event.collapsed}">
        <div class="sidebar">
            <div class="flex items-center space-x-2">
                <button @click="toggle" class="collapse-button" :class="color">
                    <span>{{ !event.collapsed ? '-' : '+' }}</span>
                </button>
                <button class="delete-button" @click="deleteEvent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 24 24" fill="currentColor"><g id="close"><path id="x" d="M18.717 6.697l-1.414-1.414-5.303 5.303-5.303-5.303-1.414 1.414 5.303 5.303-5.303 5.303 1.414 1.414 5.303-5.303 5.303 5.303 1.414-1.414-5.303-5.303z"/></g></svg>
                </button>
            </div>

            <div class="event-labels">
                <Label :color="event.color">
                    {{ date }}
                </Label>
                <Label :color="event.color">
                    {{ event.app }}
                </Label>

                <div v-if="hasLabels" class="flex gap-2">
                    <Label v-for="label in labels" :color="event.color">
                        {{ label }}
                    </Label>
                </div>
            </div>
        </div>
        <div class="body">
            <slot></slot>
        </div>
    </div>
</template>

<script>
import Label from "@/Components/UI/Label";
import {useStore} from "vuex";

export default {
    components: {Label},
    props: {
        event: Object
    },
    data() {
        return {
            open: true
        }
    },
    setup() {
        const store = useStore();

        return {
            store
        }
    },
    methods: {
        toggle() {
            this.event.setCollapsed(!this.event.collapsed)
        },
        deleteEvent() {
            this.store.dispatch('deleteEvent', this.event.uuid)
        }
    },
    computed: {
        date() {
            return this.event.date.format('HH:mm:ss')
        },
        color() {
            const color = this.event.color

            switch (color) {
                case 'gray':
                    return 'bg-gray-400 ring-gray-300';
            }

            return `bg-${color}-600 ring-${color}-300`
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

