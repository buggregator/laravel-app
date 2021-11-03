<template>
    <div class="bg-gray-200 flex flex-col justify-between">
        <div>
            <Link
                v-for="link in links"
                :href="link.href"
                class="p-3 md:p-4 lg:p-5 block hover:bg-blue-500 hover:text-white"
                :class="{'bg-blue-500 text-white': isActive(link), 'text-blue-500': !isActive(link)}"
            >
                <span v-html="link.icon"></span>
                <div v-if="link.counter && link.counter() > 0">test</div>
            </Link>
        </div>

        <WsConnectionIcon class="p-3 md:p-4 lg:p-5"/>
    </div>
</template>

<script>
import WsConnectionIcon from "@/Components/UI/WsConnectionIcon";
import {Link} from '@inertiajs/inertia-vue3'
import {useStore} from "vuex";
import {computed} from "vue";

export default {
    components: {Link, WsConnectionIcon},
    // setup() {
    //     const store = useStore();
    //
    //     const totalMails = computed(()  => store.state.smtp.events.length)
    //
    //     return {
    //         totalMails
    //     }
    // },
    data() {
        return {
            links: [
                {
                    href: '/',
                    state: (url) => this.$page.url == url,
                    icon: '<svg class="fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m19 1h-14a5.006 5.006 0 0 0 -5 5v12a5.006 5.006 0 0 0 5 5h14a5.006 5.006 0 0 0 5-5v-12a5.006 5.006 0 0 0 -5-5zm-14 2h14a3 3 0 0 1 3 3v1h-20v-1a3 3 0 0 1 3-3zm14 18h-14a3 3 0 0 1 -3-3v-9h20v9a3 3 0 0 1 -3 3zm0-8a1 1 0 0 1 -1 1h-12a1 1 0 0 1 0-2h12a1 1 0 0 1 1 1zm-4 4a1 1 0 0 1 -1 1h-8a1 1 0 0 1 0-2h8a1 1 0 0 1 1 1zm-12-12a1 1 0 1 1 1 1 1 1 0 0 1 -1-1zm3 0a1 1 0 1 1 1 1 1 1 0 0 1 -1-1zm3 0a1 1 0 1 1 1 1 1 1 0 0 1 -1-1z"/></svg>'
                },
                {
                    href: '/mail',
                    state: (url) => this.$page.url.startsWith(url),
                    icon: '<svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19,1H5A5.006,5.006,0,0,0,0,6V18a5.006,5.006,0,0,0,5,5H19a5.006,5.006,0,0,0,5-5V6A5.006,5.006,0,0,0,19,1ZM5,3H19a3,3,0,0,1,2.78,1.887l-7.658,7.659a3.007,3.007,0,0,1-4.244,0L2.22,4.887A3,3,0,0,1,5,3ZM19,21H5a3,3,0,0,1-3-3V7.5L8.464,13.96a5.007,5.007,0,0,0,7.072,0L22,7.5V18A3,3,0,0,1,19,21Z"/></svg>'
                },
                {
                    href: '/terminal',
                    state: (url) => this.$page.url.startsWith(url),
                    icon: '<svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 22"><path fill-rule="evenodd" d="M19 2H5a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V5a3 3 0 0 0-3-3ZM5 0a5 5 0 0 0-5 5v12a5 5 0 0 0 5 5h14a5 5 0 0 0 5-5V5a5 5 0 0 0-5-5H5Z"/><path fill-rule="evenodd" d="m11.1 15 .3-.6.6-.2h5.1c.3 0 .5 0 .6.2.2.2.3.4.3.7 0 .2 0 .5-.3.6-.1.2-.3.3-.6.3H12a.8.8 0 0 1-.6-.3 1 1 0 0 1-.3-.6ZM6.3 6.4a.9.9 0 0 1 .6-.3.8.8 0 0 1 .6.3l3.4 3.6a1 1 0 0 1 .2.6 1 1 0 0 1-.2.7l-3.4 3.6-.6.3a.8.8 0 0 1-.6-.3 1 1 0 0 1-.3-.6c0-.3 0-.5.3-.7l2.8-3-2.8-3A1 1 0 0 1 6 7a1 1 0 0 1 .3-.6Z" /></svg>'
                }
            ]
        }
    },
    methods: {
        isActive(link) {
            return link.state.bind(this)(link.href)
        }
    }
}
</script>
