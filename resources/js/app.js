require('./bootstrap');

import Vue from "vue"

import * as VueGoogleMaps from 'vue2-google-maps'
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css';



import FindCustomerComponent from "./components/FindCustomerComponent.vue";
import ExcelUploadModalComponent from "./components/ExcelUploadModalComponent.vue";
import SuppressionComponent from "./components/SuppressionComponent.vue";
import CreateTimeShareComponent from "./components/CreateTimeShareComponent.vue";
// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.use(VueSweetalert2);
Vue.use(VueToast);
Vue.use(VueGoogleMaps, {
    load: {
        key: 'AIzaSyBYEe_7BlFEZw-aBUyIpc6u_FPMgcsh_sU',
        libraries: 'places', // This is required if you use the Autocomplete plugin
    }
})

const app = new Vue({
    el: '#app',
    components:{
        FindCustomerComponent,
        ExcelUploadModalComponent,
        SuppressionComponent,
        CreateTimeShareComponent
    }
});

export default app;
