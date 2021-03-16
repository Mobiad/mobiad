<template>

  <!-- Payment -->
  <div v-if="!loading">
    <div class="row justify-content-center mt-5">

      <div class="col-md-5"  v-if="!success && !isLoading">

        <h5  v-if="customer.is_paid===0"
             style="font-weight: lighter; margin-bottom: 18px" class="justify-content-center"
        > Customer: {{ customer.businessname}} </h5>

        <b-card style=" background-color: #ecf3f8" v-if="customer.is_paid===0"   >
          <h4 style="margin-bottom: 0.5em; color: #4484bc;"> Tigo Pesa Payment </h4>
          <p>Enter a tigo number to pay.</p>
          <b-input-group class="my-2">
            <b-form-input
                id="input-subscriber_numbers"
                v-model="payment_phone"
                type="text"
                placeholder="Example: 255 656 026 235"
                required
            >
            </b-form-input>
            <b-input-group-append>
              <b-button
                  variant="success"
                  @click="initPayment()"
              >PAY NOW</b-button
              >
            </b-input-group-append>
          </b-input-group>

          <p class="mt-5" style="font-weight: lighter">
            You will be prompted to enter your tigopesa PASSWORD on your PHONE inorder to complete payment
          </p>

        </b-card>

        <div v-else >
          <div  style="margin-top: 48px"  >
            <div class="row justify-content-center">
              <b-icon-check-circle class="h3 success mr-2 " variant="success"></b-icon-check-circle >
            </div>
            <div class="row justify-content-center ">
              <span> Dear {{ customer.businessname}}, Your payment is complete </span>
            </div>

          </div>

        </div>



      </div>

      <div  style="margin-top: 48px" v-if="isLoading">
        <div class="row justify-content-center">
          <b-spinner variant="primary" label="Spinning"></b-spinner>
        </div>
        <div class="row align-content-start mt-4">
          <div class="align-top col">
            Sending request to your phone ...
          </div>
        </div>
      </div>
      

      <div  style="margin-top: 48px" v-if="success">
        <div class="row justify-content-center">
          <img width="96px" class="mb-5"
               src="https://www.flaticon.com/svg/vstatic/svg/438/438556.svg?token=exp=1615918058~hmac=6e5fd13d7ebfcbccc38a35ee482db5f4">

        </div>
           <div class="row align-content-start">
          <div class="align-top col">
            Enter your tigopesa PASSWORD on your PHONE
          </div>
        </div>
      </div>


    </div>
  </div>

  <div v-else >
    <div  style="margin-top: 48px"  >
      <div class="row justify-content-center">
        <b-spinner variant="primary" label="Spinning"></b-spinner>
      </div>
      <div class="row justify-content-center mt-5">
        <span>Loading customer info ...</span>
      </div>

    </div>

  </div>

</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            dismissSecs: 10,
            dismissCountDown: 0,
             show: true,
            loading: false,
            response: {
                status: "warning",
                message: ""
            },

          monthPrice: 15000,
          payment_phone: null,

          subscriptionForm: false,
          
          isLoading:false,
          customer_id: null,
          customer: { },
          success: false
         };
    },
    computed: {
        responseStatus() {
            return this.response.status ? this.response.status : "warning";
        },
        termsValidation() {
            return this.form.terms_and_conditions == 1;
        },


    },
    methods: {

      fetchCustomerInfo() {
        this.loading = true;
        let endpoint = window.site_url + "/customer/info?customer_id=" + this.customer_id;
        console.log(endpoint);

        axios.get(endpoint, this.form)
            .then(response => {
              this.loading = false;
              this.customer = response.data.customer;
              console.log(this.customer)
            }).catch(error => {
          this.loading = false;
          console.log(error)
        });
      },

      /** Payment API **/
      initPayment() {
        this.isLoading = true;
        let data = {"payment_phone": this.payment_phone, "customer_id": this.customer_id };
        let endpoint = window.site_url + "/api/payment/init";
        console.log(endpoint);
        console.log(JSON.stringify(data));

        axios.post(endpoint, data)
            .then(response => {
              this.isLoading = false;
              this.success = true;
              this.toast("Success",  "Enter your tigopesa password on your phone ", 'success')
            })
            .catch(error => {
              this.isLoading = false;
              this.toast("Error", error.response.data.message, 'danger')
            });
      },

      /*** helper **/

      getCookie(cname) {
        return document.cookie.match('(^|;)\\s*' + cname + '\\s*=\\s*([^;]+)')?.pop() || '';
      },

      toast(title, message, color) {
        this.counter++;
        this.$bvToast.toast(
            message,
            {
              title: title,
              toaster: "b-toaster-bottom-right",
              solid: true,
              appendToast: true,
              variant: color,
              autoHideDelay: 5000,

            }
        );
      },

    },
  created() {
      this.customer_id = this.getCookie('customer_id');
      console.log(this.customer_id);
       this.fetchCustomerInfo();
  },
  mounted() {
      //this.toast('hoho',"wrewr");

  }


};
</script>
