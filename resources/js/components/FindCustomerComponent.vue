<template>
<div class="component_main_div">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="form">
                        <form>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label w-100">
                                            Zip Code
                                            <span class="error">*</span>
                                        </label>
                                        <input
                                            autofocus
                                            type="text"
                                            class="form-control"
                                            placeholder="Zip Code"
                                            v-model.trim="v$.form_data.zip_code.$model"
                                        />
                                        <div class="error" v-if="v$.form_data.zip_code.required.$invalid && show_error">
                                            Zip code is required
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label w-100">
                                            Radius in Miles
                                            <span class="error">*</span>
                                        </label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            placeholder="Miles"
                                            v-model.trim="v$.form_data.radius.$model"
                                        />
                                        <div class="error" v-if="v$.form_data.radius.required.$invalid && show_error">
                                            Radius is required
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3 d-flex">
                                        <div class="">
                                            <label class="form-label w-100 text-white" style="display: block;">
                                                Submit button
                                            </label>
                                            <button
                                                type="btn"
                                                class="btn btn-primary waves-effect waves-lightml-2 me-2"
                                                @click="submit"
                                            >
                                                <i class="fa fa-search right_margin"></i> Find in Radius
                                            </button>
                                        </div>
                                        <div class="" v-if="customers.length > 0 && show_download_btn && auth_type == 'admin'">
                                            <label class="form-label w-100 text-white" style="display: block;">
                                                CSV download button
                                            </label>

                                            <vue-json-to-csv
                                                :json-data=customers
                                                :csv-title="'Customers'"
                                                @success="val => handleSuccess(val)"
                                                @error="val => handleError(val)"
                                            >
                                                <button class="btn btn-warning waves-effect waves-lightml-2 me-2">
                                                    <i class="fa fa-download right_margin"></i>
                                                    Download to CSV
                                                </button>
                                            </vue-json-to-csv>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--    <div class="table_div mt-3" v-if="customers.length > 0">-->
                    <!--        <h2>Customer's List</h2>-->
                    <!--        <table>-->
                    <!--            <tr>-->
                    <!--                <th>Full Name</th>-->
                    <!--                <th>Lat-Long</th>-->
                    <!--                <th>Distance</th>-->
                    <!--            </tr>-->
                    <!--            <tr v-for="customer in customers" :key="customer.id">-->
                    <!--                <td>{{customer.first_name+' '+customer.last_name}}</td>-->
                    <!--                <td>{{'Latitude: '+customer.latitude+' Longitude: '+customer.longitude}}</td>-->
                    <!--                <td>{{(customer.distance*0.000621371192).toFixed(3)}} Miles</td>-->
                    <!--            </tr>-->
                    <!--        </table>-->
                    <!--    </div>-->
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card" v-if="customers.length > 0">
                <div class="card-title p-3">
                    Total <span class="text-black bold" style="color: #000000;font-weight: bold;">{{customers.length}}</span> records found
                </div>
                <div class="card-body">
                    <RadiusMapComponent :center="center" :center-marker-position="centerMarkerPosition" :german-cities="germanCities" :markers="markers" :radius="radius"/>
                </div>
            </div>
            <p v-if="show_no_data" class="text-warning text-capitalize">No customer found in this radius</p>
        </div>
    </div>

</div>
</template>

<script>

import { useVuelidate } from '@vuelidate/core'
import { required } from '@vuelidate/validators'
import VueJsonToCsv from 'vue-json-to-csv'
import RadiusMapComponent from "./RadiusMapComponent.vue";
export default {
    name: "FindCustomerComponent",
    props:["auth_type"],
    components:{
        VueJsonToCsv,
        RadiusMapComponent
    },
    setup: () => ({ v$: useVuelidate() }),
    data: () => ({
        show_error: false,
        show_no_data:false,
        show_download_btn:false,
        form_data:{
            zip_code: '',
            radius: '',
        },
        customers : [],
        //to show in map
        radius:parseFloat(''),
        center: {lat: parseFloat(''), lng:parseFloat('')},
        centerMarkerPosition:{lat: parseFloat(''), lng: parseFloat('')},
        germanCities: {
                id: 'duesseldorf',
                population: 612178,
                position: {
                    lat: parseFloat(''), lng: parseFloat('')
                },
            },
        markers: [

        ],

    }),
    methods: {
        async submit (e) {
            e.preventDefault();
            let self = this;
            self.customers = [];
            self.show_no_data = false;
            self.show_download_btn = false;
            self.show_error = false;
            this.v$.$touch();
            const result = await this.v$.$validate()
            if (!result) {
                // notify user form is invalid
                if (this.v$.form_data.zip_code.$invalid ||this.v$.form_data.radius.$invalid){
                    this.show_error = true;
                    return false;
                }
            }else {
                // perform async actions
                let formData = new FormData();
                // Make form data
                for (const [key, value] of Object.entries(this.form_data)) {
                    formData.append(key, value);
                }
                await axios.post("/admin/customers/find/in-radius", formData)
                    .then(function (response) {
                        self.customers = [];
                        self.radius = parseFloat('');
                        self.center= {lat: parseFloat(''), lng:parseFloat('')};
                        self.centerMarkerPosition = {lat: parseFloat(''), lng: parseFloat('')},
                            self.germanCities= {
                                position: {
                                    lat: parseFloat(''), lng: parseFloat('')
                                },
                            };
                        self.markers= [

                        ];
                        console.log(response.data)
                        if (response.data.customers.length > 0){

                            console.log(response.data)
                            self.customers = response.data.customers;
                            self.form_data.zip_code = '';
                            self.form_data.radius = '';
                            self.show_download_btn =true;

                            //menupolate map data
                            self.center.lat = parseFloat(response.data.center.latitude)
                            self.center.lng = parseFloat(response.data.center.longitude)

                            self.centerMarkerPosition.lat = parseFloat(response.data.center.latitude)
                            self.centerMarkerPosition.lng = parseFloat(response.data.center.longitude)

                            self.germanCities.position.lat = parseFloat(response.data.center.latitude)
                            self.germanCities.position.lng = parseFloat(response.data.center.longitude)

                            self.customers.forEach((value, index) => {
                                let marker = {
                                    position: {lat: parseFloat(value.latitude), lng: parseFloat(value.longitude)},
                                };
                                self.markers.push(marker);
                            });

                            let lastElement = self.customers[self.customers.length - 1];
                            console.log(lastElement.distance)
                            self.radius = parseFloat(lastElement.distance);
                        }else {
                            self.show_no_data = true;
                            self.form_data.zip_code = '';
                            self.form_data.radius = '';
                            self.show_download_btn =false;

                            self.customers = [];
                            self.radius = parseFloat('');
                            self.center= {lat: parseFloat(''), lng:parseFloat('')};
                            self.centerMarkerPosition = {lat: parseFloat(''), lng: parseFloat('')},
                                self.germanCities= {
                                    position: {
                                        lat: parseFloat(''), lng: parseFloat('')
                                    },
                                };
                            self.markers= [];
                        }
                    })
                    .catch(function (error) {
                        console.log(error)
                        self.show_no_data = true;
                        self.form_data.zip_code = '';
                        self.form_data.radius = '';
                        self.show_download_btn =false;

                        self.customers = [];
                        self.radius = parseFloat('');
                        self.center= {lat: parseFloat(''), lng:parseFloat('')};
                        self.centerMarkerPosition = {lat: parseFloat(''), lng: parseFloat('')},
                            self.germanCities= {
                                position: {
                                    lat: parseFloat(''), lng: parseFloat('')
                                },
                            };
                        self.markers= [

                        ];
                    });
            }


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
        }
    },
    validations: {
        form_data: {
            zip_code: {
                required,
            },
            radius: {
                required,
            },
        }
    }
}
</script>

<style scoped>
.right_margin{
    margin-right: 5px;
}

table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>
