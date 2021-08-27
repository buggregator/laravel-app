<template>
    <Event :event="event">
        <h3 class="text-gray-800 font-bold">
            {{ event.event.subject }}
        </h3>
        <Table>
            <TableRow title="From">
                <template v-for="user in event.event.from">
                    {{ user.email }} <span class="text-gray-600 font-bold" v-if="user.name">[{{ user.name }}]</span>
                </template>
            </TableRow>
            <TableRow title="To">
                <template v-for="user in event.event.to">
                    {{ user.email }} <span class="text-gray-600 font-bold" v-if="user.name">[{{ user.name }}]</span>
                </template>
            </TableRow>
            <TableRow v-if="event.event.cc.length" title="Cc">
                <template v-for="user in event.event.cc">
                    {{ user.email }} <span class="text-gray-600 font-bold" v-if="user.name">[{{ user.name }}]</span>
                </template>
            </TableRow>
            <TableRow v-if="event.event.bcc.length" title="Bcc">
                <template v-for="user in event.event.bcc">
                    {{ user.email }} <span class="text-gray-600 font-bold" v-if="user.name">[{{ user.name }}]</span>
                </template>
            </TableRow>
        </Table>

        <div>
            <TabGroup>
                <TabList class="flex justify-start ml-3 mb-3 border-b">
                    <Tab v-slot="{ selected }" as="template">
                        <button class="px-4 py-2 font-bold border-b-4 text-gray-700" :class="[selected ? 'border-blue-500 text-gray-900' : 'border-white']">
                            -
                        </button>
                    </Tab>
                    <Tab v-slot="{ selected }" as="template">
                        <button class="px-4 py-2 font-bold border-b-4 text-gray-700" :class="[selected ? 'border-blue-500 text-gray-900' : 'border-white']">
                            Preview
                        </button>
                    </Tab>
                    <Tab v-slot="{ selected }" as="template">
                        <button class="px-4 py-2 font-bold border-b-4 text-gray-700" :class="[selected ? 'border-blue-500 text-gray-900' : 'border-white']">
                            HTML
                        </button>
                    </Tab>
                    <Tab v-slot="{ selected }" as="template">
                        <button class="px-4 py-2 font-bold border-b-4 text-gray-700" :class="[selected ? 'border-blue-500 text-gray-900' : 'border-white']">
                            Raw
                        </button>
                    </Tab>
                </TabList>
                <TabPanels>
                    <TabPanel></TabPanel>
                    <TabPanel class="border rounded">
                        <div v-html="event.event.html"/>
                    </TabPanel>
                    <TabPanel class="border rounded">
                        <ssh-pre language="html">
                            {{ event.event.html }}
                        </ssh-pre>
                    </TabPanel>
                    <TabPanel class="border rounded">
                        <ssh-pre language="html">
                            {{ event.event.raw }}
                        </ssh-pre>
                    </TabPanel>
                </TabPanels>
            </TabGroup>
        </div>
    </Event>
</template>

<script>
import SshPre from 'simple-syntax-highlighter'
import Table from "@/Components/UI/Table";
import TableRow from "@/Components/UI/TableRow";
import Dump from "@/Components/UI//Dump";
import Collapsed from "@/Components/UI//Collapsed";
import Event from "../Event";
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'

export default {
    components: {
        Event, SshPre, Table, TableRow, Dump, Collapsed, TabGroup, TabList, Tab, TabPanels, TabPanel
    },
    props: {
        event: Object
    }
}
</script>
