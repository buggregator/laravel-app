<template>
    <MainLayout title="Inspector">
        <main class="flex flex-grow w-full">
        </main>
    </MainLayout>
</template>

<script>
import MainLayout from "@/Components/Layout/Main"
import {PerfectScrollbar} from 'vue3-perfect-scrollbar'
import {computed} from 'vue';
import {useStore} from "vuex";
import NavItem from "@/Components/Smtp/NavItem";
import Info from "@/Components/Smtp/Info";
import EventFactory from "@/EventFactory";
import {Link} from '@inertiajs/inertia-vue3'

export default {}
</script>
