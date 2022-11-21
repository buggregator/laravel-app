<template>
    <MainLayout title="Sentry">
        <div ref="header" class="breadcrumbs">
            <div class="text-muted">Projects</div>
        </div>
        <main class="m-3">
            <div class="flex justify-between">
                <h1 class="text-2xl font-bold mb-3">Projects</h1>
            </div>
            <ag-grid-vue
                v-if="projects.length > 0"
                domLayout='autoHeight'
                class="ag-theme-alpine"
                :columnDefs="columnDefs"
                :rowData="projects">
            </ag-grid-vue>
            <div v-else class="h-full flex flex-col items-center justify-center my-10">
                <svg class="w-1/3 max-w-xs mb-5 fill-current text-blue-400 dark:text-gray-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 41 40"><path d="M 23.51,2.18 C 23.51,2.18 22.78,5.10 22.78,5.10 22.78,5.10 34.63,5.47 34.63,5.47 34.63,5.47 34.63,2.00 34.63,2.00 34.63,2.00 23.51,2.18 23.51,2.18 Z M 0.39,5.89 C 0.39,5.89 2.95,31.06 2.95,31.06 2.95,31.06 37.31,31.34 37.31,31.34 37.31,31.34 39.90,8.13 39.90,8.13 39.90,8.13 21.93,8.33 21.93,8.33 21.93,8.33 20.14,5.74 20.14,5.74 20.14,5.74 0.39,5.89 0.39,5.89 Z M 1.67,3.69 C 1.67,3.69 4.41,28.51 4.41,28.51 4.41,28.51 35.79,28.87 35.79,28.87 35.79,28.87 39.02,3.91 39.02,3.91 39.02,3.91 36.70,3.69 36.70,3.69 36.70,3.69 35.60,0.59 35.60,0.59 35.60,0.59 22.29,0.41 22.29,0.41 22.29,0.41 21.01,3.33 21.01,3.33 21.01,3.33 1.67,3.69 1.67,3.69 Z"/></svg>
                <h3 class="font-semibold text-muted text-2xl mt-2">No projects</h3>
            </div>
        </main>
    </MainLayout>
</template>

<script>
import MainLayout from "@/Components/Layout/Main"
import ProjectFactory from "@/ProjectFactory";
import "ag-grid-community/styles/ag-grid.css";
import "ag-grid-community/styles/ag-theme-alpine.css";
import { AgGridVue } from "ag-grid-vue3";

export default {
    components: {
        MainLayout,
        AgGridVue
    },
    data() {
        return {
            projects: this.$page.props.projects.data.map((project) => {
                return ProjectFactory.create(project)
            }),
            columnDefs: [
                {headerName: "Id", field: "id"},
                {headerName: "Name", field: "name"},
            ]
        }
    }
}
</script>
