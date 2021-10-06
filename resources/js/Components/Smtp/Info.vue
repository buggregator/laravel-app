<template>
    <div class="flex justify-between">
        <h2 class="text-2xl font-bold">{{ event.subject }}</h2>
        <button class="h-5 w-5" @click="deleteEvent">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path
                    d="m338 197-19 221c-1 10 14 11 15 1l19-221a8 8 0 0 0-15-1zM166 190c-4 0-7 4-7 8l19 221c1 10 16 9 15-1l-19-221c0-4-4-7-8-7zM249 197v222a7 7 0 1 0 15 0V197a7 7 0 1 0-15 0z"/>
                <path
                    d="M445 58H327V32c0-18-14-32-31-32h-80c-17 0-31 14-31 32v26H67a35 35 0 0 0 0 69h8l28 333c2 29 27 52 57 52h192c30 0 55-23 57-52l4-46a8 8 0 0 0-15-2l-4 46c-2 22-20 39-42 39H160c-22 0-40-17-42-39L90 127h22a7 7 0 1 0 0-15H67a20 20 0 0 1 0-39h378a20 20 0 0 1 0 39H147a7 7 0 1 0 0 15h275l-21 250a8 8 0 0 0 15 2l21-252h8a35 35 0 0 0 0-69zm-133 0H200V32c0-10 7-17 16-17h80c9 0 16 7 16 17v26z"/>
            </svg>
        </button>
    </div>

    <div class="text-xs font-semibold mt-3">
        <div class="mb-1">
            <span>From:</span>
            <span class="text-gray-500 inline-block ml-2 border rounded px-2"
                  v-for="email in event.event.from">{{ email.name }} [{{ email.email }}]</span>
        </div>
        <div>
            <span>To:</span>
            <span class="text-gray-500 inline-block ml-2 border rounded px-2"
                  v-for="email in event.event.to">{{ email.name }} [{{ email.email }}]
            </span>
        </div>
    </div>

    <TabGroup>
        <TabList class="flex justify-start mt-3 border-b">
            <Tab>Preview</Tab>
            <Tab>HTML</Tab>
            <Tab>Raw</Tab>
            <Tab>Tech Info</Tab>
        </TabList>
        <TabPanels class="flex-grow mt-3">
            <TabPanel class="h-full">
                <HtmlPreview>
                    <iframe :src="`/mail/${event.uuid}/html`"/>
                </HtmlPreview>
            </TabPanel>
            <TabPanel>
                <CodeSnippet language="html" class="max-w-full">
                    {{ event.event.html }}
                </CodeSnippet>
            </TabPanel>
            <TabPanel>
                <CodeSnippet language="html">
                    {{ event.event.raw }}
                </CodeSnippet>
            </TabPanel>
            <TabPanel>
                <div>
                    <h3 class="mb-3 font-bold">Email Headers</h3>
                    <Table>
                        <TableRow title="Id">
                            {{ event.event.id }}
                        </TableRow>
                        <TableRow title="Subject">
                            {{ event.subject }}
                        </TableRow>
                        <TableRow title="From">
                            <Addresses :addresses="event.event.from"/>
                        </TableRow>
                        <TableRow title="To">
                            <Addresses :addresses="event.event.to"/>
                        </TableRow>
                        <TableRow v-if="event.event.cc.length" title="Cc">
                            <Addresses :addresses="event.event.cc"/>
                        </TableRow>
                        <TableRow v-if="event.event.bcc.length" title="Bcc">
                            <Addresses :addresses="event.event.bcc"/>
                        </TableRow>
                        <TableRow v-if="event.event.reply_to.length" title="Reply to">
                            <Addresses :addresses="event.event.reply_to"/>
                        </TableRow>
                        <TableRow v-if="event.event.attachments.length" title="Attachments">
                            <div class="flex flex-col space-y-2">
                                <div v-for="(attachment, i) in event.event.attachments">
                                    <span>{{ i + 1 }}.</span> {{ attachment.name }}
                                </div>
                            </div>
                        </TableRow>
                        <TableRow title="Content-Type">
                            {{ event.event.content_type }}
                        </TableRow>
                    </Table>
                </div>
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

export default {
    components: {
        CodeSnippet, Dump, Collapsed, HtmlPreview,
        TabGroup, TabList, Tab, TabPanels, TabPanel, Table, TableRow, Addresses
    },
    props: {
        event: Object
    },
    methods: {
        async deleteEvent() {
            try {
                await this.$inertia.delete(`/mail/${this.event.uuid}`)
            } catch (e) {

            }
        }
    }
}
</script>
