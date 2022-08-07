<template>
    <section class="py-5 px-4 md:px-6 lg:px-8 border-b">
        <h3 class="text-muted font-bold uppercase text-sm mb-5">spans</h3>

        <ag-grid-vue
            domLayout='autoHeight'
            class="ag-theme-alpine"
            :columnDefs="columnDefs"
            :rowData="event.spans">
        </ag-grid-vue>
    </section>
</template>

<script>
import Table from "@/Components/UI/Table";
import TableRow from "@/Components/UI/TableRow";
import "ag-grid-community/styles/ag-grid.css";
import "ag-grid-community/styles/ag-theme-alpine.css";
import { AgGridVue } from "ag-grid-vue3";
import moment from "moment";

export default {
    components: {
        AgGridVue, Table, TableRow
    },
    data() {
        return {
            columnDefs: [
                {headerName: "Description", field: "description"},
                {headerName: "Operation Name", field: "op"},
                {headerName: "Span", field: "span_id"},
                {headerName: "Parent span", field: "parent_span_id"},
                {headerName: "Trace", field: "trace_id"},
                {
                    headerName: 'Duration', valueGetter:
                        function (params) {
                            if (params.data.timestamp && params.data.start_timestamp) {
                                const duration = params.data.timestamp - params.data.start_timestamp
                                return moment.utc(duration * 1000).format("HH:mm:ss.SSS")
                            } else {
                                return ''
                            }
                        }
                }
            ]
        }
    },
    props: {
        event: Object
    }
}
</script>
