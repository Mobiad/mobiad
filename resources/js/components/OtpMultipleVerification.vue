<template>

  <!-- Payment -->
  <div >
    <div v-if="!loading" class="row justify-content-center">

      <!-- Welcome -->
      <div class="col-md-5">
        <h4 style="color: #4484bc; font-weight: lighter"> Hello, {{ customer.businessname }}</h4>
      </div>
      <div class="col-md-5">
      </div>
      <!-- [end] Welcome -->

      <div class="col-md-5" v-if="!allPhonesAgreed">

        <b-card style=" background-color: #ecf3f8">
          <h4 style="margin-bottom: 0.5em; color: #4484bc;"> Policy agreement </h4>

          <p style="font-weight: lighter">By entering the received code you are agreeing to our terms and conditions</p>


          <div v-if="!isVerifying">
            <div v-for=" (phone,index) in customer.phones" :key="index" style="margin-bottom: 24px;">

              <span>{{ index + 1 }}. {{ phone.phone }}</span>
              <div v-if="phone.has_accepted_terms" class="justify-content-center align-content-center mt-2">
                <div class="row align-content-start">
                  <div class="col-md-1">
                    <b-icon-check-circle class="h3 success mr-2 " variant="success"></b-icon-check-circle >
                  </div>
                  <div class="align-top col">
                    Accepted terms
                  </div>
                </div>
              </div>

              <b-input-group v-else
                  v-for="phone in form.subscriber_numbers"
                  :key="phone.key"
                  class="my-2">

                <b-form-input
                    :ref="phone"
                    id="input-subscriber_numbers"
                    type="text"
                    v-model="numbers.otps[index]"
                    placeholder="Verification Code"
                    required>

                </b-form-input>
                <b-input-group-append>
                  <b-button
                      variant="primary"
                      @click="verifyOtp(index)"
                  >Verify
                  </b-button
                  >
                </b-input-group-append>
              </b-input-group>
            </div>
          </div>
          <!-- verification progrerss -->
          <div v-else style="padding: 12px">
            <b-spinner variant="primary" label="Spinning"></b-spinner>
            &nbsp; &nbsp; Verifying the number ...
          </div>


        </b-card>
      </div>

      <div class="col-md-5">
        <div class="content">

          <b-card header="Your Total Costs are">
            <pre class="m-0"></pre>
            <table class="table">
              <thead>
              <tr>
                <th scope="col">Phone Numbers</th>
                <th scope="col"> Price(TZS)</th>
                <th scope="col"> Months</th>

                <th scope="col">Total(TZS)</th>
              </tr>
              </thead>
              <tbody>
              <tr>
                <td> {{ customer.phones.length }}</td>
                <td> {{ (monthPrice).toLocaleString() }}</td>
                <td>{{ customer.subscription_period }}</td>
                <td>{{ totalSubscriberPhoneNumbers }}</td>

              </tr>


              <!-- total -->
              <tr>
                <th scope="row" colspan="3">
                  Total cost for your subscription
                </th>
                <td>{{ totalSubscriptionCost }}</td>
              </tr>
              </tbody>
            </table>
            <b-button
                variant="primary"
                class="w-100" v-if="allPhonesAgreed"
                @click="redirectionToPaymentPage()"
                :disabled="loading">
              <b-spinner
                  type="grow"
                  label="Spinning"
                  small
                  v-if="loading"
              ></b-spinner>
              Pay Now
            </b-button>


          </b-card>

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
      form: {
        fullname: "",
        phone: "",
        businessname: "",
        businessdesc: "",
        subscription_period: 1,
        tune: 1,
        voice: "male",
        ref: "",
        subscriber_numbers: [
          {
            key: 1,
            value: ""
          }
        ],
        terms_and_conditions: 0
      },
      show: true,
      loading: false,
      response: {
        status: "warning",
        message: ""
      },

      monthPrice: 15000,

      subscriptionForm: false,

      customer_id: null,
      customer: {},
      isVerifying: false,

      numbers: {
        otps: []
      }

    };
  },
  computed: {
    responseStatus() {
      return this.response.status ? this.response.status : "warning";
    },
    termsValidation() {
      return this.form.terms_and_conditions == 1;
    },
    tuneProduction() {
      return this.form.tune;
    },
    subscriberPhoneNumbers() {
      let results = this.form.subscriber_numbers.filter(phone => {
        return phone.value != "";
      });

      return results;
    },
    totalSubscriberPhoneNumbers() {
      return Math.round(
          this.customer.subscription_period *
          this.customer.phones.length *
          this.monthPrice
      );
    },
    totalSubscriptionCost() {
      return Math.round(this.totalSubscriberPhoneNumbers);
    },

    allPhonesAgreed(){
      let agreed = true;
      this.customer.phones.forEach(function(item){
          if(item.has_accepted_terms ===0){
            agreed = false;
          }
      });
      return agreed;
    }

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

    onSubmit(event) {
      event.preventDefault();

      let numbers = this.form.subscriber_numbers.map(function (item) {
        return item.value;
      });

      this.form.numbers = numbers;
      console.log(JSON.stringify(this.form));

      if (!(this.termsValidation)) {
        return
      }

      console.log("Signing up");

      this.loading = true;
      axios.post("signup", this.form)
          .then(response => {
            this.loading = false;
            this.response = response.data;

            if (response.data.message.length != 0) {
              this.response.message = JSON.stringify(
                  response.data.message,
                  null,
                  4
              );
            }

            this.onReset();
          })
          .catch(error => {
            console.log(JSON.stringify(error))
            this.loading = false;
            this.response = error.response.data;
          });

    },

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
    verifyOtp(index) {

      this.isVerifying = true;

      let data = {"phone_number": this.customer.phones[index].phone, "otp": this.numbers.otps[index]};
      let endpoint = window.site_url + "/api/verify/otp";
      console.log(endpoint);
      console.log(JSON.stringify(data));

      axios.post(endpoint, data)
          .then(response => {
            this.isVerifying = false;
            this.toast("Success", data.phone_number+ " Verified", 'success')
            this.fetchCustomerInfo();
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
    this.customer_id = this.getCookie('customer_id');
    console.log(this.customer_id);
    this.fetchCustomerInfo();
  },
  mounted() {
    //this.toast('hoho',"wrewr");

  }


};
</script>
