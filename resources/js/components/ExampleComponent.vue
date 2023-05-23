<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        <input type="date" v-model="start">
                        <input type="date" v-model="end">
                        <button @click="filter(start,end)">filter</button>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>location</th>
                                    <th>temperature</th>
                                    <th>date time</th>
                                    <th>action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(weather, index) in weatherdata" :key="index">
                                    <td>{{weather.id}}</td>
                                    <td>{{weather.location}}</td>
                                    <td>{{weather.temperature}}</td>
                                    <td>{{weather.date_time}}</td>
                                    <td><button @click="ctof(weather)">change</button></td>
                                </tr>
                            </tbody>
                            <tfoot><Paginate :page-count="pageCount" :prev-text="'Prev'" :next-text="'Next'" :click-handler="getData"></Paginate></tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Paginate from 'vuejs-paginate'
    export default {
        data() {
            return {
                weatherdata: null,
                start: null,
                end: null,
                pageCount:null
            }
        },
        components:{
            Paginate
        },
        mounted() {
            console.log('Component mounted.')
        },
        created() {
            this.getData();
        },
        methods: {
            async getData(page){
                if (typeof page === 'undefined') {
                    page = 1;
                }
                axios.get('http://127.0.0.1:8000/api/weather?page='+page)
                  .then(response => {
                    this.weatherdata = response.data.data
                    this.pageCount = response.data.last_page
                  })
            },
            ctof(data){
                data.temperature = (data.temperature * (9/5)) + 32;
            },
            filter(start,end){
                axios.get('http://127.0.0.1:8000/api/weather?start'+start+'&end='+end)
                  .then(response => {
                    this.weatherdata = response.data.data
                    this.pageCount = response.data.last_page
                  })

            }
        }
    }
</script>
