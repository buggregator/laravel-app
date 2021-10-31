<template>
    <div class="bg-gray-200 flex flex-col justify-between">
        <div>
            <Link href="/" class="p-3 md:p-4 lg:p-5 block hover:bg-blue-500 hover:text-white" :class="{'bg-blue-500 text-white': this.$page.url == '/', 'text-blue-500': this.$page.url != '/'}">
                <svg class="fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m19 1h-14a5.006 5.006 0 0 0 -5 5v12a5.006 5.006 0 0 0 5 5h14a5.006 5.006 0 0 0 5-5v-12a5.006 5.006 0 0 0 -5-5zm-14 2h14a3 3 0 0 1 3 3v1h-20v-1a3 3 0 0 1 3-3zm14 18h-14a3 3 0 0 1 -3-3v-9h20v9a3 3 0 0 1 -3 3zm0-8a1 1 0 0 1 -1 1h-12a1 1 0 0 1 0-2h12a1 1 0 0 1 1 1zm-4 4a1 1 0 0 1 -1 1h-8a1 1 0 0 1 0-2h8a1 1 0 0 1 1 1zm-12-12a1 1 0 1 1 1 1 1 1 0 0 1 -1-1zm3 0a1 1 0 1 1 1 1 1 1 0 0 1 -1-1zm3 0a1 1 0 1 1 1 1 1 1 0 0 1 -1-1z"/></svg>
            </Link>
            <Link href="/mail" class="p-3 md:p-4 lg:p-5 block hover:bg-blue-500 hover:text-white" :class="{'bg-blue-500 text-white': this.$page.url.startsWith('/mail'), 'text-blue-500': !this.$page.url.startsWith('/mail')}">
                <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19,1H5A5.006,5.006,0,0,0,0,6V18a5.006,5.006,0,0,0,5,5H19a5.006,5.006,0,0,0,5-5V6A5.006,5.006,0,0,0,19,1ZM5,3H19a3,3,0,0,1,2.78,1.887l-7.658,7.659a3.007,3.007,0,0,1-4.244,0L2.22,4.887A3,3,0,0,1,5,3ZM19,21H5a3,3,0,0,1-3-3V7.5L8.464,13.96a5.007,5.007,0,0,0,7.072,0L22,7.5V18A3,3,0,0,1,19,21Z"/></svg>
            </Link>
            <Link href="/terminal" class="p-3 md:p-4 lg:p-5 block hover:bg-blue-500 hover:text-white" :class="{'bg-blue-500 text-white': this.$page.url.startsWith('/terminal'), 'text-blue-500': !this.$page.url.startsWith('/terminal')}">
                <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"><path d="M486.4 21.3H25.6C11.5 21.4 0 32.8 0 46.9V465c0 14.1 11.5 25.6 25.6 25.6h460.8c14.1 0 25.6-11.5 25.6-25.6V46.9c0-14.1-11.5-25.5-25.6-25.6zm-8.5 435.2H34.1v-401h443.7v401z"/><path d="M106.6 280.1 185 192l-78.4-88.1-25.5 22.7 58.2 65.4-58.2 65.5zM213.3 234.7h136.5v34.1H213.3z"/></svg>
            </Link>
        </div>

        <WsConnectionIcon class="p-3 md:p-4 lg:p-5" />
    </div>
</template>

<script>
import WsConnectionIcon from "@/Components/UI/WsConnectionIcon";
import {Link} from '@inertiajs/inertia-vue3'
export default {
    components: {Link, WsConnectionIcon}
}
</script>
