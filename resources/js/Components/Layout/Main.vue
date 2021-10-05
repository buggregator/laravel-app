<template>
    <Head :title="title"/>
    <div class="flex h-screen items-stretch">
        <div class="w-10 md:w-14 lg:w-16 flex-none">
            <LeftSidebar class="w-10 md:w-14 lg:w-16 fixed h-screen">
                <slot name="left-sidebar"></slot>
            </LeftSidebar>
        </div>

        <div class="flex flex-col h-full flex-1">
            <slot></slot>
        </div>
    </div>

    <slot name="footer"></slot>
    <notifications/>
</template>

<script>
import LeftSidebar from './Sidebar/Left'

export default {
    components: {LeftSidebar},
    props: {
        title: String
    }
}
</script>
