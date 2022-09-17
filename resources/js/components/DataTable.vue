<template>
    <div>
        <div class="table-responsive">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <select class="form-control" v-model="per_page" v-on:change="getData(request_url)">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-6">
                    <form class="search-form text-md-right" v-on:submit.prevent="getData(current_page_url)">
                        <Loader />
                        <button type="button" class="refresh" v-on:click="reload">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M370.72 133.28C339.458 104.008 298.888 87.962 255.848 88c-77.458.068-144.328 53.178-162.791 126.85-1.344 5.363-6.122 9.15-11.651 9.15H24.103c-7.498 0-13.194-6.807-11.807-14.176C33.933 94.924 134.813 8 256 8c66.448 0 126.791 26.136 171.315 68.685L463.03 40.97C478.149 25.851 504 36.559 504 57.941V192c0 13.255-10.745 24-24 24H345.941c-21.382 0-32.09-25.851-16.971-40.971l41.75-41.749zM32 296h134.059c21.382 0 32.09 25.851 16.971 40.971l-41.75 41.75c31.262 29.273 71.835 45.319 114.876 45.28 77.418-.07 144.315-53.144 162.787-126.849 1.344-5.363 6.122-9.15 11.651-9.15h57.304c7.498 0 13.194 6.807 11.807 14.176C478.067 417.076 377.187 504 256 504c-66.448 0-126.791-26.136-171.315-68.685L48.97 471.03C33.851 486.149 8 475.441 8 454.059V320c0-13.255 10.745-24 24-24z"/></svg>
                            Reload
                        </button>
                        <input type="text" name="search" v-model="search" />
                        <button type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"/></svg>
                        </button>
                    </form>
                </div>
            </div>
            <table class="table table-bordered table-striped table-sm dtable">
                <thead>
                    <tr>
                        <th v-for="(item, i) in columns" v-bind:key="i" v-on:click="sort(i)">
                            {{ item }}
                            <i class="fas fa-caret-up ml-1 sort-icon" v-if="colsort[i] == 'asc'"></i>
                            <i class="fas fa-caret-down ml-1 sort-icon" v-else></i>
                        </th>
                    </tr>
                </thead>
                <tbody v-if="data">
                    <tr v-for="(item, i) in data" v-bind:key="i">
                        <td v-for="(val, j) in item" v-bind:key="j" v-html="val"></td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <td v-bind:colspan="col_count" class="text-center">No Data Found</td>
                    </tr>
                </tbody>
            </table>
            <div class="text-right">
                <ul class="pagination datatable-pagination">
                    <li v-bind:class="prev_page_url == null ? 'disabled' : ''">
                        <a href="javascript:void(0)" v-on:click="getData(prev_page_url)">
                            <svg style="margin-right: 3px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M257.5 445.1l-22.2 22.2c-9.4 9.4-24.6 9.4-33.9 0L7 273c-9.4-9.4-9.4-24.6 0-33.9L201.4 44.7c9.4-9.4 24.6-9.4 33.9 0l22.2 22.2c9.5 9.5 9.3 25-.4 34.3L136.6 216H424c13.3 0 24 10.7 24 24v32c0 13.3-10.7 24-24 24H136.6l120.5 114.8c9.8 9.3 10 24.8.4 34.3z"/></svg>
                            Prev
                        </a>
                    </li>
                    <li v-for="(item, i) in buttons" v-bind:key="i" v-bind:class="item.active == true ? 'active' : ''">
                        <a href="javascript:void(0);" v-on:click="item.disabled == false ? loadPage(item.page) : ''">{{ item.text }}</a>
                    </li>
                    <li v-bind:class="next_page_url == null ? 'disabled' : ''">
                        <a href="javascript:void(0);" v-on:click="getData(next_page_url)">
                            Next 
                            <svg style="margin-left: 3px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M190.5 66.9l22.2-22.2c9.4-9.4 24.6-9.4 33.9 0L441 239c9.4 9.4 9.4 24.6 0 33.9L246.6 467.3c-9.4 9.4-24.6 9.4-33.9 0l-22.2-22.2c-9.5-9.5-9.3-25 .4-34.3L311.4 296H24c-13.3 0-24-10.7-24-24v-32c0-13.3 10.7-24 24-24h287.4L190.9 101.2c-9.8-9.3-10-24.8-.4-34.3z"/></svg>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import sortArray from 'sort-array'
    import { makeStaticArray } from '../utils'
    import Loader from './Loader.vue'

    export default {
        props: [
            'csrf_token',
            'request_url',
            'autoreload',
            'interval'
        ],
        components: {
            Loader
        },
        data() {
            return {
                columns: [],
                data: [],
                col_count: 0,
                search: '',
                per_page: '10',
                current_page: 1,
                has_pages: false,
                last_page: '',
                next_page_url: '',
                prev_page_url: '',
                current_page_url: this.request_url,
                loader: '',
                loading: false,
                colsort: [],
                buttons: []
            }
        },
        mounted() {
            this.getData()

            var interval = this.interval ? parseInt(this.interval) : 2000
            if(this.autoreload == 'true')
                setInterval(this.reloadData, interval)
        },
        methods: {
            loadPage(page) {
                var link = this.request_url + '?page=' + page
                this.getData(link)
            },
            reload() {
                var link = this.request_url
                this.getData(link)
            },
            getData(link='') {
                if(link =='') {
                    link = this.current_page_url
                }
                let formData = {
                    _token: this.csrf_token,
                    per_page: this.per_page,
                    search: this.search
                }
                this.loading = true
                axios.post(link, formData)
                    .then((response) => {
                        console.log(response.data)
                        this.columns = response.data.columns
                        this.col_count = response.data.columns.length
                        this.data = response.data.data
                        this.current_page = response.data.current_page
                        this.has_pages = response.data.has_pages
                        this.last_page = response.data.last_page
                        this.next_page_url = response.data.next_page_url
                        this.prev_page_url = response.data.prev_page_url
                        this.current_page_url = response.data.current_page_url
                        this.loading = false
                        this.colsort = makeStaticArray(response.data.columns.length, false)

                        this.pagination()
                    })
                    .catch((error) => {
                        this.loading = false
                        console.log(error.response)
                    })
            },
            pagination() {
                this.buttons = []
                if(this.last_page <= 10) {
                    for(var i=1; i<=this.last_page; i++) {
                        this.buttons.push({
                            page: i,
                            text: i,
                            disabled: false,
                            active: this.current_page == i ? true : false
                        })
                    }
                } else {
                    if(this.current_page <= 4) {
                        for(var i=1; i<=5; i++) {
                            this.buttons.push({
                                page: i,
                                text: i,
                                disabled: false,
                                active: this.current_page == i ? true : false
                            })
                        }
                        this.buttons.push({
                            page: '',
                            text:'...',
                            disabled: true,
                            active: false
                        })
                        this.buttons.push({
                            page: this.last_page,
                            text:this.last_page,
                            disabled: false,
                            active: false
                        })
                    } else {
                        this.buttons.push({
                            page: 1,
                            text:1,
                            disabled: false,
                            active: false
                        })
                        this.buttons.push({
                            page: '',
                            text:'...',
                            disabled: true,
                            active: false
                        })
                        if(this.current_page < this.last_page-3) {
                            for(var i=this.current_page-1; i<=this.current_page+3; i++) {
                                this.buttons.push({
                                    page: i,
                                    text: i,
                                    disabled: false,
                                    active: this.current_page == i ? true : false
                                })
                            }
                        } else {
                            for(var i=this.current_page-2; i<=this.last_page; i++) {
                                this.buttons.push({
                                    page: i,
                                    text: i,
                                    disabled: false,
                                    active: this.current_page == i ? true : false
                                })
                            }
                        }
                        if(this.current_page < this.last_page-3) {
                            this.buttons.push({
                                page: '',
                                text:'...',
                                disabled: true,
                                active: false
                            })
                            this.buttons.push({
                                page: this.last_page,
                                text:this.last_page,
                                disabled: false,
                                active: false
                            })
                        }
                    }
                }
            },
            reloadData(link='') {
                if(link =='') {
                    link = this.current_page_url
                }
                let formData = {
                    _token: this.csrf_token,
                    per_page: this.per_page,
                    search: this.search
                }
                axios.post(link, formData)
                    .then((response) => {
                        this.columns = response.data.columns
                        this.col_count = response.data.columns.length
                        this.data = response.data.data
                        this.current_page = response.data.current_page
                        this.has_pages = response.data.has_pages
                        this.last_page = response.data.last_page
                        this.next_page_url = response.data.next_page_url
                        this.prev_page_url = response.data.prev_page_url
                        this.current_page_url = response.data.current_page_url
                        this.colsort = makeStaticArray(response.data.columns.length, false)
                    })
                    .catch((error) => {
                        console.log(error.response)
                    })
            },
            sort(i) {
                var data = this.data
                var val = this.colsort[i] == 'asc' ? 'desc' : 'asc'
                sortArray(data, {
                    by: 'total',
                    order: val,
                    computed: {
                        total: row => row[i]
                    }
                })
                this.colsort[i] = val
            }
        }
    }
</script>

<style>
    /* table.dtable {
        table-layout: fixed;
        word-wrap: break-word;
    } */
    .dtable th {
        cursor: pointer;
        background-color: #e8e7e6;
        border-color: #e8e7e6;
    }
    .dtable th, .dtable td {
        vertical-align: middle;
    }
    .search-form input {
        border: 1px solid #dee2e6;
        border-radius: 0;
        padding: 3px;
    }
    .search-form button {
        border: 0;
        background-color: #5e5e5e;
        color: #ffffff;
        padding: 3px 10px;
    }
    .search-form button.refresh {
        background-color: #0b708a;
    }
    .datatable-pagination li a {
        padding: 4px 12px;
        border: 1px solid #dee2e6;
        color: #5e5e5e;
        transition: 0.3s;
    }
    .datatable-pagination li.active a {
        color: #ffffff;
        background-color: #5e5e5e;
    }
    .datatable-pagination li a i {
        font-size: 12px;
    }
    .datatable-pagination li.disabled a {
        pointer-events: none;
        background-color: #e9ecef;
        color: #bbbdbf;
    }
    .datatable-pagination li a:hover {
        background-color: #cfcdcc;
    }
    .table-loader {
        width: 32px;
        margin-right: 2px;
    }
    .sort-icon {
        font-size: 13px;
        cursor: pointer;
    }
    .search-form svg {
        fill: #ffffff;
        width: 18px;
    }
    .datatable-pagination svg {
        width: 15px;
        fill: #5e5e5e;
    }
    .datatable-pagination li.disabled svg {
        fill: #bbbdbf;
    }
</style>