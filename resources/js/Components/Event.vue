<template>
    <div class="event" ref="event" :class="{'collapsed': event.collapsed, 'open': !event.collapsed}">
        <div class="event__sidebar sidebar">
            <div class="sidebar__container">
                <button @click="toggle" class="button button__collapse" :class="color">
                    <PlusIcon v-if="event.collapsed" />
                    <MinusIcon v-else />
                </button>
                <button class="button button__delete" @click="deleteEvent">
                    <TimesIcon />
                </button>
            </div>

            <div class="event__labels">
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
        <div class="event__body">
            <slot></slot>
        </div>
    </div>
</template>

<script>
import Label from "@/Components/UI/Label";
import {useStore} from "vuex";
import PlusIcon from "@/Components/UI/Icons/PlusIcon";
import MinusIcon from "@/Components/UI/Icons/MinusIcon";
import TimesIcon from "@/Components/UI/Icons/TimesIcon";

export default {
    components: {MinusIcon, PlusIcon, TimesIcon, Label},
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

