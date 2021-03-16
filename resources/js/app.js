import Vue from "vue";
import {
    AlertPlugin,
    CollapsePlugin,
    BadgePlugin,
    CardPlugin,
    ButtonPlugin,
    FormPlugin,
    FormGroupPlugin,
    FormInputPlugin,
    FormRadioPlugin,
    FormTextareaPlugin,
    FormCheckboxPlugin,
    InputGroupPlugin,
    SpinnerPlugin,
    ToastPlugin,
    IconsPlugin
} from "bootstrap-vue";
import axios from "axios";

/*
 |--------------------------------------------------------------------------
 | Configuration
 |--------------------------------------------------------------------------
 */

axios.defaults.headers.common = {
    "X-CSRF-TOKEN": document.head.querySelector('meta[name="csrf-token"]')
        .content,
    "X-Requested-With": "XMLHttpRequest"
};
axios.defaults.withCredentials = true;

// Make BootstrapVue available throughout your project
Vue.use(AlertPlugin);
Vue.use(FormPlugin);
Vue.use(CollapsePlugin);
Vue.use(BadgePlugin);
Vue.use(CardPlugin);
Vue.use(ButtonPlugin);
Vue.use(FormGroupPlugin);
Vue.use(FormInputPlugin);
Vue.use(FormRadioPlugin);
Vue.use(FormTextareaPlugin);
Vue.use(FormCheckboxPlugin);
Vue.use(InputGroupPlugin);
Vue.use(SpinnerPlugin);
Vue.use(ToastPlugin);
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin);

Vue.component("form-data", require("./components/SubscriptionForm.vue").default);
Vue.component("verification", require("./components/OtpMultipleVerification.vue").default);
Vue.component("single-verification", require("./components/OtpSingleVerification.vue").default);
Vue.component("payment", require("./components/Payment.vue").default);

Vue.component("phone-input", require("./components/Phone.vue").default);
Vue.component("table-data", require("./components/Table.vue").default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//let base_url ="https://" + window.location.host;
let base_url ="http://" + window.location.host;
window.api_url =  base_url + "/api/v1" ;
window.site_url =   base_url ;
window.img_url =   base_url ;



const app = new Vue({
    el: "#app"
});
