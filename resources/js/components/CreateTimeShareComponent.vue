<template>
<div class="mb-time-wrapper">
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <input class="form-control" placeholder="Name of timeshare" v-model.trim="v$.form_data.name.$model">
                                    <div class="error" v-if="v$.form_data.name.required.$invalid && showErrorText">
                                        Name is required
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-4" style="padding-right: 0!important;">
                                            <button class="btn btn-outline-secondary text-capitalize text-black" :disabled="!isSaved" style="color: #6e6e6e; width: 100%" data-bs-toggle="modal" data-bs-target="#exampleModal">Suppressions</button>
                                        </div>
                                        <div class="col-md-4" style="padding-right: 0!important;">
                                            <button class="btn btn-outline-secondary text-capitalize text-black" :disabled="prospects.length === 0" style="color: #6e6e6e; width: 100%" @click="saveTimeshare">Save</button>
                                        </div>
                                        <div class="col-md-4" style="padding-left: 0!important;" v-if="auth_type == 'admin'">
                                            <vue-json-to-csv
                                                :json-data=prospects
                                                :csv-title="form_data.city+'_'+form_data.state+'_'+form_data.radius+'_TimeSheet'"
                                                @success="val => handleSuccess(val)"
                                                @error="val => handleError(val)"
                                            >
                                                <button class="btn btn-outline-secondary text-capitalize text-black" :disabled="prospects.length === 0" style="color: #6e6e6e; width: 100%;font-size: 10px;padding:9px;">
                                                    <i class="fa fa-download right_margin"></i>
                                                    Download to CSV
                                                </button>
                                            </vue-json-to-csv>
<!--                                            <button class="btn btn-outline-secondary text-capitalize text-black" style="color: #6e6e6e; width: 100%">Download</button>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-title" style="padding-left: 20px;padding-top: 20px">
                            <h1 class="card-title">Geography</h1>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <p v-if="instraction" class="text-warning">Please <span class="text-danger">save</span> time share from <span class="text-danger">top</span> or <span class="text-danger">filter</span> with <span class="text-danger">suppressions</span> or <span class="text-danger">geography</span>.</p>
                                    <div class="row" style="margin-bottom: 15px;">
                                        <div class="col-md-2" style="padding-right: 0!important;">
                                            <button class="btn btn-outline-secondary text-capitalize text-black" style="color: #6e6e6e; width: 100%"
                                                    @click="showCityDiv = !showCityDiv"
                                            >City</button>
                                        </div>
                                        <div class="col-md-2" style="padding-right: 0!important; padding-left: 0!important;">
                                            <button class="btn btn-outline-secondary text-capitalize text-black" style="color: #6e6e6e; width: 100%"
                                                    @click="showStateDiv = !showStateDiv"
                                            >State</button>
                                        </div>
                                        <div class="col-md-2" style="padding-right: 0!important; padding-left: 0!important;">
                                            <button class="btn btn-outline-secondary text-capitalize text-black" style="color: #6e6e6e; width: 100%"
                                                    @click="showPhoneDiv = !showPhoneDiv"
                                            >Phone</button>
                                        </div>
                                        <div class="col-md-2" style="padding-left: 0!important;">
                                            <button class="btn btn-outline-secondary text-capitalize text-black" style="color: #6e6e6e; width: 100%"
                                                    @click="showZipDiv = !showZipDiv"
                                            >Zip Radius</button>
                                        </div>
                                        <div class="col-md-4">
                                            <button class="btn btn-outline-primary text-capitalize" :disabled="form_data.zip === '' && form_data.radius === ''" style="width: 100%" @click="search">Search</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" v-if="showCityDiv">
                                    <div class="card card_bg" style="margin-top: 15px;margin-bottom: 15px;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">City <span class="error">*</span></label>
                                                    <input type="text" class="form-control" placeholder="Enter city name" v-model="form_data.city">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" v-if="showStateDiv">
                                    <div class="card card_bg" style="margin-bottom: 15px;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">State</label>
                                                    <input type="text" class="form-control" placeholder="Enter state name" v-model="form_data.state">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" v-if="showZipDiv">
                                    <div class="card card_bg" style="margin-bottom: 15px;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">Zip</label>
                                                    <input type="text" class="form-control" placeholder="Enter zip name" v-model="form_data.zip">
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">Radius</label>
                                                    <input type="text" class="form-control" placeholder="Enter radius in miles" v-model="form_data.radius">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" v-if="showPhoneDiv">
                                    <div class="card card_bg" style="margin-bottom: 15px;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">Phone</label>
                                                    <input type="text" class="form-control" placeholder="Enter phone name" v-model="form_data.phone">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3 d-flex justify-content-between">
                                <button class="btn btn-outline-secondary text-capitalize text-black" style="color: #6e6e6e;font-size: 10px;padding:9px; width: 25%" @click="countAllCustomer">Count</button>
                                <button class="btn btn-outline-secondary text-capitalize text-black" style="color: #6e6e6e;font-size: 10px;padding:9px; width: 25%" @click="reload">Reload</button>
                            </div>
                            <div class="card-title d-flex justify-content-between" style="border-bottom: 1px solid #e7e7e7;">
                                <h4 class="card-title mb-3">Count</h4>
                                <ICountUp
                                    :endVal="count"
                                    class="card-title"
                                />
<!--                                <h4 class="card-title mb-3">{{count}}</h4>-->
                            </div>
                            <div class="mt-3">
                                <p>
                                    <span>Timeshare Name : <span class="bold">{{form_data.name}}</span></span>
                                </p>
                                <p>
                                    <span>Zip Code : <span class="bold">{{form_data.zip}}</span></span>
                                </p>
                                <p>
                                    <span>Zip Radius : <span class="bold">{{form_data.radius}}</span></span>
                                </p>
                                <p>
                                    <span>Phone : <span class="bold">{{form_data.phone}}</span></span>
                                </p>
                                <p>
                                    <span>City : <span class="bold">{{form_data.city}}</span></span>
                                </p>
                                <p>
                                    <span>State : <span class="bold">{{form_data.state}}</span></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex justify-content-between" style="border-bottom: 1px solid #e7e7e7;">
                                <h4 class="card-title mb-3">Suppressions</h4>
                            </div>

                            <div class="">
                                <ul class="list-group" v-if="selectedSuppressions.length > 0">
                                    <li class="list-group-item suppression_list" v-for="(suppression,index) in selectedSuppressions" :key="suppression.id">
                                        <div class="d-flex justify-content-end">
                                            <button type="button" class="btn-close" @click="removeTimeShare(index,suppression)"></button>
                                        </div>
                                        <div class="tooltip" @click="timeShareSelected(1)">
                                            {{suppression.csv_file_name}}
                                            <div class="tooltiptext">Click to add</div>
                                        </div>
                                    </li>
                                </ul>
                                <p v-else class="text-warning p-2 mt-3">No suppressions selected</p>
                                <button class="btn btn-outline-dark mt-3" @click="suppressed">Suppressed</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <loading :active.sync="isLoading"
             :can-cancel="false"
             :is-full-page="true"
             background-color="#414141"
    ></loading>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Suppressions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body scroll">
                    <ul class="list-group" v-if="suppressions.length > 0">
                        <li class="list-group-item suppression_list" v-for="(suppression,index) in suppressions" :key="suppression.id">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn-close" @click="removeTimeShare(1)"></button>
                            </div>
                            <div class="tooltip" @click="timeShareSelected(suppression)">
                                {{suppression.csv_file_name}}
                                <div class="tooltiptext">Click to add</div>
                            </div>
                        </li>
                    </ul>
                    <p v-else class="text-warning p-2">No suppressions found</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
<!--                    <button type="button" class="btn btn-primary">Save changes</button>-->
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import VueJsonToCsv from 'vue-json-to-csv'
import { useVuelidate } from '@vuelidate/core'
import { required } from '@vuelidate/validators'
import RadiusMapComponent from "./RadiusMapComponent.vue";
import ICountUp from 'vue-countup-v2';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    name: "CreateTimeShareComponent",
    props:["auth_type"],
    components:{
        VueJsonToCsv,
        ICountUp,
        Loading
    },
    setup () {
        return { v$: useVuelidate() }
    },
    data(){
        return{
            isLoading:false,
            isSaved:false,
            prospects:[],
            count:0,
            suppressions:[],
            selectedSuppressions:[],
            timeshareIds:[],
            saveBtnDisable:true,
            downloadBtnDisable:true,
            suppressionBtnDisable:true,
            suppressedBtnDisable:true,
            form_data:{
                name: '',
                city: '',
                state: '',
                zip: '',
                phone: '',
                radius: '',
            },
            showErrorText:false,
            instraction:false,
            showCityDiv:false,
            showStateDiv:false,
            showZipDiv:false,
            showPhoneDiv:false,
        }
    },

    methods:{

        async getAllCustomer(){
            await axios.get('/admin/times')
                .then((response)=>{
                    // console.log(response)

                    // this.prospects = response.data.customers
                    this.suppressions = response.data.times

                    // console.log(this.prospects)
                    // console.log(this.suppressions)
                })
                .catch((error)=>{
                    console.log(error)
                })
        },

        async countAllCustomer(){
            this.isLoading = true;
            await axios.get('/admin/times/customer/count')
                .then((response)=>{
                    console.log(response)

                    this.count = response.data.count
                    this.isLoading = false;
                    // console.log(this.prospects)
                    // console.log(this.suppressions)
                })
                .catch((error)=>{
                    this.isLoading = false;
                    console.log(error)
                })
        },

        async search(){
            if (this.checkSearchForm()){
                this.isLoading = true;
                await axios.post('/admin/times/search/prospects',this.form_data)
                    .then((response)=>{
                        console.log(response)

                        // this.prospects = response.data.customers
                        // this.count = response.data.count
                        // this.suppressions = response.data.times

                        // console.log(response.data.customers)
                        // console.log(response.data.count)
                        if (response.data.customers){
                            this.$toast.success('Search Complete',{position:"top-right"})
                            this.prospects = []
                            this.prospects = response.data.customers
                            this.count = response.data.count
                            this.instraction = true;

                        }
                        // console.log(this.prospects)
                        // console.log(this.suppressions)
                        this.isLoading = false;
                    })
                    .catch((error)=>{
                        console.log(error)
                        this.isLoading = false;
                    })
            }
        },

        async saveTimeshare(){

            if (this.checkSearchForm()){
                let data = {
                    form_data:this.form_data,
                    prospects:this.prospects,
                }
                this.isLoading = true;
                await axios.post('/admin/times',data)
                    .then((response)=>{
                        console.log(response.data.status)

                        if (response.data.status == 200){
                            //show toaster massage
                            this.suppressions.unshift(response.data.timeshare);
                            this.$toast.success('Successfully Saved',{position:"top-right"})
                            console.log('push hoise')
                        }
                        this.isLoading = false;
                        // window.location.reload();
                        // this.prospects = response.data.customers
                        // this.suppressions = response.data.times
                    })
                    .catch((error)=>{
                        this.isLoading = false;
                        console.log(error)
                    })
            }
        },

        timeShareSelected(suppression){
            console.log(suppression)
            this.selectedSuppressions.push(suppression)
            this.$toast.success('Added',{position:"top-right"})
        },
        removeTimeShare(index,suppression){
            console.log(index)
            this.selectedSuppressions.splice(index,1)
            this.$toast.success('Removed',{position:"top-right"})
        },
        async suppressed(){
            this.isLoading = true;
            this.selectedSuppressions.forEach((value,index)=>{
                this.timeshareIds.push(value.id)
            })
            console.log(this.timeshareIds)

            await axios.post('/admin/times/search/suppressed',this.timeshareIds)
                .then((response)=>{
                    console.log(response)
                    if (response.data.status == 200){
                        //Suppressed Successfully toaster
                        this.$toast.success('Suppressed Successfully',{position:"top-right"})
                        this.prospects = [];

                        response.data.sups.forEach((value, index)=>{
                            this.prospects.push(value)
                        })

                        this.count = this.prospects.length
                        this.isLoading = false;

                    }else {
                        //something went wrong toaster
                        this.isLoading = false;
                        this.$toast.error('Something Went wrong',{position:"top-right"})
                    }
                })
                .catch((error)=>{
                    console.log(error)
                    this.isLoading = false;
                })

        },
        checkSearchForm(){
            this.v$.$touch()
            if (this.v$.form_data.name.$invalid) {
                this.showErrorText = true;
                return false;
            }
            return true;
        },
        handleSuccess(val){
            if (val){
                this.form_data.zip_code = '';
                this.form_data.radius = '';
                this.show_download_btn =false;
            }
        },
        handleError(val){
            if (val){
                this.form_data.zip_code = '';
                this.form_data.radius = '';
                this.show_download_btn =false;
            }
        },
        reload(){
            window.location.reload();
        }

    },

    mounted(){
        this.getAllCustomer();
    },
    validations () {
        return {
            form_data: {
                name:{
                    required
                }
            },
        }
    }
}
</script>

<style scoped>
.card_bg{
    background: #eeeeee;
}
.suppression_list{
    cursor: pointer;
    margin-top: 15px;
    margin-right: 15px;
}
/* Tooltip container */
.tooltip {
    position: relative;
    padding: 5px;
    display: inline-block;
    //border-bottom: 1px dotted black; /* If you want dots under the hoverable text */
    opacity: 1;
}

/* Tooltip text */
.tooltip .tooltiptext {
    visibility: hidden;
    width: 120px;
    background-color: #a2a2a2;
    color: #fff;
    text-align: center;
    padding: 5px 0;
    border-radius: 6px;

    /* Position the tooltip text - see examples below! */
    position: absolute;
    z-index: 1;
}

/* Show the tooltip text when you mouse over the tooltip container */
.tooltip:hover .tooltiptext {
    visibility: visible;
}


.scroll {
    padding:4px;
    height: 500px;
    overflow-x: hidden;
    overflow-y: auto;
}
.btn-outline-secondary:disabled{
    background-color:#e0e0e0;
}
.btn-outline-primary:disabled{
    background-color:#e0e0e0;
    border: 1px solid #e0e0e0;
}
</style>
