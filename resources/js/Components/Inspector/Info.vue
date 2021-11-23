<template>
    <div class="flex justify-between">
        <h2 class="text-2xl font-bold">{{ event.process.name}}</h2>
        <button class="h-5 w-5" @click="deleteEvent">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path
                    d="m338 197-19 221c-1 10 14 11 15 1l19-221a8 8 0 0 0-15-1zM166 190c-4 0-7 4-7 8l19 221c1 10 16 9 15-1l-19-221c0-4-4-7-8-7zM249 197v222a7 7 0 1 0 15 0V197a7 7 0 1 0-15 0z"/>
                <path
                    d="M445 58H327V32c0-18-14-32-31-32h-80c-17 0-31 14-31 32v26H67a35 35 0 0 0 0 69h8l28 333c2 29 27 52 57 52h192c30 0 55-23 57-52l4-46a8 8 0 0 0-15-2l-4 46c-2 22-20 39-42 39H160c-22 0-40-17-42-39L90 127h22a7 7 0 1 0 0-15H67a20 20 0 0 1 0-39h378a20 20 0 0 1 0 39H147a7 7 0 1 0 0 15h275l-21 250a8 8 0 0 0 15 2l21-252h8a35 35 0 0 0 0-69zm-133 0H200V32c0-10 7-17 16-17h80c9 0 16 7 16 17v26z"/>
            </svg>
        </button>
    </div>

    <div class="text-xs font-semibold mt-3 flex mt-0 justify-between items-start">
        <div class="pr-3 flex flex-col"><span class="text-gray-500 text-xs mb-1">Timestamp</span>{{event.processDate}}</div>
        <div class="pr-3 flex flex-col"><span class="text-gray-500 text-xs mb-1">Duration</span>{{event.process.duration}} ms</div>
        <div class="pr-3 flex flex-col text-white"><span class="text-gray-500 text-xs mb-1">Result</span>
            <span :class="`bg-${event.color}-700`" class="p-1 rounded text-2xs">{{event.processResult}}</span>
        </div>
    </div>

    <TabGroup>
        <TabList class="flex justify-start mt-3 border-b">
            <Tab>Timeline</Tab>
            <Tab>Url</Tab>
            <Tab>Request</Tab>
            <Tab>Body</Tab>
            <Tab>Response</Tab>
        </TabList>
        <TabPanels class="flex-grow mt-3">
            <TabPanel class="h-full">
               <TimelineChart :event="event" v-if="event && event.event.length > 0"/>
            </TabPanel>
            <TabPanel>
                <Table class="mt-3">
                    <TableRow :title="name" v-for="(value, name) in event.process.http.url">
                        {{ value }}
                    </TableRow>
                </Table>
            </TabPanel>
            <TabPanel>
                <Table class="mt-3">
                    <TableRow :title="name" v-for="(value, name) in event.process.http.request">
                        <template v-if="typeof value==='string'">
                            {{value}}
                        </template>
                        <template v-else-if="!Array.isArray(value)">
                            <TableRow  :title="n" v-for="(v, n) in value">
                                {{v}}
                            </TableRow>
                        </template>
                    </TableRow>
                </Table>
            </TabPanel>
            <TabPanel>
                  <h3 class="mb-3 font-bold">Body</h3>
            </TabPanel>
            <TabPanel>
                <h3 class="mb-3 font-bold">Response</h3>
            </TabPanel>
        </TabPanels>
    </TabGroup>
</template>

<script>
import CodeSnippet from "@/Components/UI/CodeSnippet"
import Table from "@/Components/UI/Table";
import TableRow from "@/Components/UI/TableRow";
import Dump from "@/Components/UI/Dump";
import Collapsed from "@/Components/UI/Collapsed";
import HtmlPreview from "@/Components/UI/HtmlPreview";
import Tab from "@/Components/UI/TabGroup/Tab";
import {TabGroup, TabList, TabPanels, TabPanel} from '@headlessui/vue'
import Addresses from "@/Components/Smtp/Addresses";
import TimelineChart from "./TimelineChart";

export default {
    components: {
        TimelineChart,
        CodeSnippet, Dump, Collapsed, HtmlPreview,
        TabGroup, TabList, Tab, TabPanels, TabPanel, Table, TableRow, Addresses
    },
    props: {
        event: Object
    },

    methods: {
        // TODO поправить метод удаления
        async deleteEvent() {
            try {
                await this.$inertia.delete(`/mail/${this.event.uuid}`)
            } catch (e) {

            }
        }
    },
    computed: {
        series() {
            const seriesData = this.event.segments.map((i) => {
                return {
                    x: i.label,
                    y: [i.start, i.start + i.duration],
                    fillColor: '#FEB019'
                }
            });
            console.log(seriesData)
            return [{data: seriesData.slice(0,5)}]
        }
    }
}
</script>
