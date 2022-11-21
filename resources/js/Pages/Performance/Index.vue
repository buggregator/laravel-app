<template>
    <MainLayout title="Performance">
        <div ref="header" class="breadcrumbs">
            <div class="text-muted">Performance</div>
        </div>
        <main class="m-3">
            <div class="flex justify-between">
                <h1 class="text-2xl font-bold mb-3">Transactions</h1>
            </div>
            <ag-grid-vue
                v-if="transactionsProjects.length > 0"
                domLayout='autoHeight'
                class="ag-theme-alpine"
                :columnDefs="columnDefs"
                :rowData="transactionsProjects">
            </ag-grid-vue>
        </main>
    </MainLayout>
</template>

<script>
import MainLayout from "@/Components/Layout/Main"
import "ag-grid-community/styles/ag-grid.css";
import "ag-grid-community/styles/ag-theme-alpine.css";
import { AgGridVue } from "ag-grid-vue3";
import {TransactionProjectFactory} from "@/TransactionProjectFactory";

export default {
    components: {
        MainLayout,
        AgGridVue
    },
    data() {
        return {
            transactionsProjects: this.$page.props.transactionsProjects.map((item) => {
                return TransactionProjectFactory.create(item)
            }),
            columnDefs: [
                {headerName: "Transaction", field: "transactionName"},
                {headerName: "Project", field: "projectName"},
                {headerName: "Total events", field: "totalEvents"},
                {
                    headerName: 'Events',
                    field: 'eventsRoute',
                    cellRenderer: function (route) {
                        return `<a href="${route.value}">Show events</a>`;
                    }
                }
            ]
        }
    }
}
</script>
