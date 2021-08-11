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
            <LogPayload v-for="payload in log.payloads" :payload="payload" />
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import LogPayload from "./LogPayload";

export default {
    components: {LogPayload},
    props: {
        log: Object
    },
    computed: {
        date() {
            return moment().format('MMMM Do YYYY, hh:mm:ss');
        },
        color() {
            let color = 'bg-gray-300';

            this.log.payloads.forEach(function (payload) {
                if (payload.content.color) {
                    color = `bg-${payload.content.color}-500`
                }
            })

            return color
        },
        labels() {
            let labels = [];

            this.log.payloads.forEach(function (payload) {
                if (payload.content.label) {
                    labels.push(payload.content.label)
                }

                labels.push(payload.type)
            })

            return _.uniq(labels)
        },
        hasLabels() {
            return this.labels.length > 0
        }
    }
}
</script>
