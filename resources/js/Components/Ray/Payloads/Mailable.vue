<template>
    <div class="event-ray__mailable">
        <h3 v-if="payload.content.subject" class="font-bold">
            {{ payload.content.subject }}
        </h3>
        <div v-if="payload.content.mailable_class" class="event-ray__mailable-class">
            {{ payload.content.mailable_class }}
        </div>

        <div class="text-xs font-semibold mt-3 flex flex-wrap items-center">
            <div class="flex border border-purple-300 rounded items-center mr-3 mb-2">
                <div class="px-3 py-1 border-r">From</div>
                <div class="px-3 py-1 bg-gray-800 text-white font-semibold rounded-r" v-for="email in payload.content.from">{{ email.name }} [{{ email.email }}]</div>
            </div>

            <div class="flex border border-purple-300 rounded items-center mr-3 mb-2" v-for="email in payload.content.to">
                <div class="px-3 py-1 border-r">To</div>
                <div class="px-3 py-1 bg-blue-800 text-white font-semibold rounded-r">{{ email.name }} [{{email.email}}]</div>
            </div>

            <div class="flex border border-purple-300 rounded items-center mr-3 mb-2" v-for="email in payload.content.cc">
                <div class="px-3 py-1 border-r">CC</div>
                <div class="px-3 py-1 bg-red-800 text-white font-semibold rounded-r">{{ email.name }} [{{email.email}}]</div>
            </div>

            <div class="flex border border-purple-300 rounded items-center mr-3 mb-2" v-for="email in payload.content.bcc">
                <div class="px-3 py-1 border-r">BCC</div>
                <div class="px-3 py-1 bg-purple-800 text-white font-semibold rounded-r">{{ email.name }} [{{email.email}}]</div>
            </div>

            <div class="flex border border-purple-300 rounded items-center mr-3 mb-2" v-for="email in payload.content.reply_to">
                <div class="px-3 py-1 border-r">Reply to</div>
                <div class="px-3 py-1 bg-green-800 text-white font-semibold rounded-r">{{ email.name }} [{{email.email}}]</div>
            </div>
        </div>

        <Collapsed class="mt-3 event-ray__mailable-collapsed" title="HTML">
            <iframe :src="route('ray.show.html', id)" class="w-full" style="min-height: 500px"/>
        </Collapsed>
    </div>
</template>

<script>
import Dump from "@/Components/UI/Dump";
import Collapsed from "@/Components/UI/Collapsed";

export default {
    components: {Dump, Collapsed},
    props: {
        id: String,
        payload: Object
    }
}
</script>
