<template>
    <Head :title="title"></Head>
    <div class="main-layout">
        <div class="main-layout__sidebar-wrap">
            <LeftSidebar class="main-layout__sidebar">
                <slot name="left-sidebar"></slot>
            </LeftSidebar>
        </div>

        <div class="main-layout__content" :class="classes">
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
        title: String,
        classes: String
    }
}
</script>
