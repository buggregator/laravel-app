<template>
    <div ref="event"
         class="md:flex md:space-y-0 md:space-x-3 lg:space-x-5 p-3 md:p-0 hover:bg-gray-50"
         :class="{'overflow-hidden shadow-bottom h-20 md:h-12 lg:h-16': event.collapsed}">
        <div
            :class="{'md:bg-gray-50': !event.collapsed}"
            class="w-full md:w-1/4 pb-3 flex justify-between sm:items-center md:items-start md:px-3 md:py-3 lg:px-5 lg:py-5 md:border-r">
            <div class="flex items-center space-x-2">
                <button @click="toggle"
                        class="w-5 h-5 md:w-4 md:h-4 leading-none rounded-full opacity-90 hover:opacity-100 transition transition-all hover:ring-4 ring-offset-1 flex items-center justify-center"
                        :class="color">
                    <span class="text-sm text-white font-bold leading-none">{{ !event.collapsed ? '-' : '+' }}</span>
                </button>
                <button
                    class="w-5 h-5 p-1 rounded-full text-red-700 bg-white hover:bg-red-700 hover:text-white transition transition-all"
                    @click="deleteEvent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 24 24"
                         fill="currentColor">
                        <g id="close">
                            <path id="x"
                                  d="M18.717 6.697l-1.414-1.414-5.303 5.303-5.303-5.303-1.414 1.414 5.303 5.303-5.303 5.303 1.414 1.414 5.303-5.303 5.303 5.303 1.414-1.414-5.303-5.303z"/>
                        </g>
                    </svg>
                </button>
            </div>

            <div class="flex flex-wrap flex-row-reverse gap-2 justify-start">
                <Label :class="`border-${event.color}-600 text-${event.color}-600`">
                    {{ date }}
                </Label>
                <Label class="border-blue-600 text-blue-600">
                    {{ event.app }}
                </Label>

                <div v-if="hasLabels" class="flex gap-2">
                    <Label v-for="label in labels" class="text-gray-500 ">
                        {{ label }}
                    </Label>
                </div>
            </div>
        </div>
        <div class="w-full md:w-3/4 flex-col space-y-5 md:pr-3 lg:pr-5 md:py-3 lg:py-5">
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

