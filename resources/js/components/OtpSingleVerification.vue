<template>

  <!-- Payment -->
  <div >
    <div v-if="!loading" class="row justify-content-center">

      <div v-if="!success" class="col-md-5 mt-5"  >

        <b-card style=" background-color: #ecf3f8"   >
          <h5 style="margin-bottom: 0.5em; color: #4484bc;"> Verify your phone {{ phone_number }} </h5>

          <p style="font-weight: lighter">By entering the received verification code you are agreeing  to our terms of service</p>

          <p>Enter verification code</p>

          <b-input-group class="my-2">
            <b-form-input
                id="input-subscriber_numbers"
                v-model="otp"
                type="text"
                placeholder="Code"
                required>
            </b-form-input>
            <b-input-group-append>
              <b-button
                  variant="success"
                  @click="verifyOtp()"
              >SUBMIT</b-button
              >
            </b-input-group-append>
          </b-input-group>


        </b-card>
      </div>

      <div v-else style="margin-top: 48px">
        <div class="row align-content-start">
          <div class="col-md-1">
            <b-icon-check-circle class="h3 success mr-2 " variant="success"></b-icon-check-circle >
          </div>
          <div class="align-top col">
           Success, you have agreed to the terms of services
          </div>
        </div>
      </div>

    </div>

    <div v-else style="padding: 12px">
      <b-spinner variant="primary" label="Spinning"></b-spinner>
      &nbsp; &nbsp; Loading...
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

      success: false,
      phone_number: null,
      otp: null


    };
  },
  computed: {

  },
  methods: {

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

    /** Payment API **/
    verifyOtp() {

      this.isVerifying = true;

      let data = {"phone_number": this.phone_number, "otp": this.otp };
      let endpoint = window.site_url + "/api/verify/otp";
      console.log(endpoint);
      console.log(JSON.stringify(data));

      axios.post(endpoint, data)
          .then(response => {
            this.isVerifying = false;
            this.success = true;
            this.toast("Success", data.phone_number+ " Verified", 'success')
          })
          .catch(error => {
            this.isVerifying = false;
            this.toast("Error", error.response.data.message, 'danger')
          });
    },

    redirectionToPaymentPage(){
      let paymentPage = window.site_url + "/payment/form?customer_id="+this.customer.id;
      window.location = paymentPage;
    },

    /**helper **/
    getCookie(cname) {
      return document.cookie.match('(^|;)\\s*' + cname + '\\s*=\\s*([^;]+)')?.pop() || '';
    }

  },
  created() {
    this.phone_number = this.getCookie('phone_number');
    console.log(this.phone_number);
   },
  mounted() {

  }


};
</script>
