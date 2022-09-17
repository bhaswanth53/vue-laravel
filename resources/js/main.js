import Vue from 'vue'

import DataTable from './components/DataTable.vue'

const app = new Vue({
    el: "#app",
    components: {
        'datatable' : DataTable
    }
})