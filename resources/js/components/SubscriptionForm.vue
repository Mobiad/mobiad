<template>

  <div>
    <div class="row justify-content-center">

      <div class="col-md-7">
        <!-- <div class="alert alert-warning" v-if="response.message">
            {{ response.message }}
        </div> -->
        <b-alert
            :show="dismissCountDown"
            dismissible
            :variant="responseStatus"
            @dismissed="dismissCountDown = 0"
            @dismiss-count-down="countDownChanged">
          <p>{{ response.message }}</p>
        </b-alert>
        <b-form @submit="onSubmit" @reset="onReset" v-if="show">

          <!-- Primary info -->
          <h4 style="margin-top: 1.5em; color: #4484bc"> Primary information </h4>

          <b-form-group
              id="input-group-name"
              label="Name / Jina:"
              label-for="input-name"
          >
            <b-form-input
                id="input-name"
                v-model="form.fullname"
                type="text"
                placeholder="Enter your name / Andika jina lako"
                required
            ></b-form-input>
            <b-form-invalid-feedback>
              Your user ID must be 5-12 characters long.
            </b-form-invalid-feedback>
          </b-form-group>

          <b-form-group
              id="input-group-phone"
              label="Phone Number / Namba ya Simu:"
              label-for="input-phone"
              description="Hatutasambaza namba ya simu yako na mtu mwingine yeyote."
          >
            <b-form-input
                id="input-phone"
                v-model="form.phone"
                type="tel"
                placeholder="Enter your phone number / Andika namba ya simu"
                required
            ></b-form-input>
          </b-form-group>

          <b-form-group
              id="input-group-businessname"
              label="Business Name / Jina la Biashara:"
              label-for="input-businessname"
          >
            <b-form-input
                id="input-businessname"
                v-model="form.businessname"
                type="text"
                placeholder="Enter your business name / Andika jina la biashara yako"
                required
            ></b-form-input>
          </b-form-group>

          <b-form-group
              id="input-group-businessdesc"
              label="Business Description (SCRIPT) / Maelezo ya Biashara:"
              label-for="textarea"
              class="my-4"
          >
            <b-form-textarea
                id="textarea"
                v-model="form.businessdesc"
                placeholder="Enter your business description here / Maelezo ya biashara yako"
                rows="3"
                max-rows="6"
            ></b-form-textarea>
          </b-form-group>

          <!-- Subscription information -->
          <h4 style="margin-top: 1.5em; color: #4484bc"> Subscription information </h4>

          <b-form-group
              id="input-group-4"
              class="my-4"
              label="Service Period/ Miezi ya Huduma"
              description="Gharama za muito wa simu kwa namba moja ni shilingi TZS 10,000/= kwa Mwezi mmoja
                                (TZS 10,000 / Kwa Mwezi / Kwa Namba Moja)"
              label-for="input-subscription_period"
              v-slot="{ ariaDescribedby }"
          >
            <!-- <b-form-input
                id="input-subscription_period"
                v-model="form.subscription_period"
                type="number"
                placeholder="Eg: 3 months / Mf: Miezi 3"
                required
            ></b-form-input> -->

            <b-form-radio-group
                id="input-subscription_period"
                v-model="form.subscription_period"
                :aria-describedby="ariaDescribedby"
            >
              <b-form-radio value="1">1 Month</b-form-radio>
              <b-form-radio value="3">3 Months</b-form-radio>
              <b-form-radio value="6">6 Months</b-form-radio>
            </b-form-radio-group>
          </b-form-group>

          <b-form-group
              id="input-group-tune"
              label="Include Tune Production / Kutengeneza Muito"
              description="Gharama za kutengeneza muito wa biashara yako ni shilingi TZS 20,000/="
              label-for="input-tune"
              class="my-4"
          >
            <b-form-radio-group id="input-tune" v-model="form.tune">
              <b-form-radio value="1">Yes</b-form-radio>
              <b-form-radio value="0">No</b-form-radio>
            </b-form-radio-group>
          </b-form-group>

          <b-form-group
              v-if="form.tune === 1"
              id="input-group-voice"
              label="Audio voice over / Sauti ya  Muito"
              label-for="input-voice"
              class="my-4"
          >
            <b-form-radio-group id="input-voice" v-model="form.voice">
              <b-form-radio value="male">Male</b-form-radio>
              <b-form-radio value="female">Female</b-form-radio>
            </b-form-radio-group>
          </b-form-group>

          <b-form-group
              id="input-group-ref"
              label="Ref Number (optional) / Kumbukumbu Namba (Sio lazima kujaza)"
              label-for="input-ref"
          >
            <b-form-input
                id="input-ref"
                v-model="form.ref"
                type="text"
                placeholder="Enter Ref Number / Andika namba ya kumbukumbu"
            ></b-form-input>
          </b-form-group>

          <!-- Phone numbers -->
          <h4 style="margin-top: 1.5em; color: #4484bc"> Phone Numbers </h4>
          <b-form-group
              id="input-group-subscriber_numbers"
              label-for="input-subscriber_numbers"
              class="my-4">
            <label class="row" for="input-subscriber_numbers">
              <div class="col">
                Phone numbers to activate the service / Namba za
                kuweka Muito:
              </div>
              <div class="col">
                <b-button
                    class="float-right my-1"
                    variant="primary"
                    size="small"
                    @click="addPhoneNumbers"
                >
                  Add another phone number
                </b-button>
              </div>
            </label>

            <b-input-group
                v-for="phone in form.subscriber_numbers"
                :key="phone.key"
                class="my-2"
            >
              <b-form-input
                  id="input-subscriber_numbers"
                  v-model="phone.value"
                  type="text"
                  placeholder="Eg: 255 781 123312"
                  required
              >
              </b-form-input>
              <b-input-group-append>
                <b-button
                    variant="danger"
                    @click="removePhoneNumber(phone)"
                >Remove
                </b-button
                >
              </b-input-group-append>
            </b-input-group>
          </b-form-group>

          <div style=" height: 24px"></div>
          <b-form-group
              class="my-4"
              id="input-group-terms_and_conditions"
          >
            <div class="row">
              <div class="col-md-6">
                <b-form-checkbox
                    :state="termsValidation"
                    id="checkbox-terms_and_conditions"
                    v-model="form.terms_and_conditions"
                    value="1"
                    unchecked-value="0"
                >Nimekubali Vigezo na Masharti
                </b-form-checkbox>
                <b-form-invalid-feedback :state="termsValidation">
                  Check this box to indicate that you have read
                  and agree our Terms and Conditions
                </b-form-invalid-feedback>
              </div>
              <div class="col-md-6">
                <a href="/service_agreement.pdf" target="blank">
                  Bonyeza hapa kusoma vigezo na masharti
                </a>
              </div>
            </div>
          </b-form-group>

          <b-button
              type="submit"
              variant="primary"
              class="w-100"
              :disabled="loading">
            <b-spinner
                type="grow"
                label="Spinning"
                small
                v-if="loading"
            ></b-spinner>
            Submit / Tuma
          </b-button>
        </b-form>
      </div>
      <!-- Summary   -->
      <div class="col-md-5">
        <div class="content">
          <h6>Hello 👋🏽, {{ form.fullname }}</h6>
          <b-card class="mt-3" header="Your Total Costs are">
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
                <td> {{ this.subscriberPhoneNumbers.length }}</td>
                <td> {{ (monthPrice).toLocaleString() }}</td>
                <td>{{ form.subscription_period }}</td>
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
          </b-card>
        </div>
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
      customer: {},

      subscriptionForm: false,
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
          this.form.subscription_period *
          this.subscriberPhoneNumbers.length *
          this.monthPrice
      );
    },
    totalSubscriptionCost() {
      return Math.round(this.totalSubscriberPhoneNumbers);
    }
  },
  methods: {

    addPhoneNumbers() {
      this.form.subscriber_numbers.push({
        key: Date.now(),
        value: ""
      });
    },

    removePhoneNumber(item) {
      var index = this.form.subscriber_numbers.indexOf(item);
      if (index !== -1) {
        this.form.subscriber_numbers.splice(index, 1);
      }
    },

    signupToast(toaster, append = false) {
      this.counter++;
      this.$bvToast.toast(
          `We have received your enquery our team will start to work on it ASAP`,
          {
            title: `Thank you for signing up`,
            toaster: toaster,
            solid: true,
            appendToast: append
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
      axios
          .post("signup", this.form)
          .then(response => {
            console.log(JSON.stringify(response.data));
            this.loading = false;
            this.response = response.data;
            this.showAlert();
            if (response.data.message.length != 0) {
              this.response.message = JSON.stringify(response.data.message, null, 4);
            }
            this.signupToast("b-toaster-bottom-right", true);
            this.onReset();

            this.customer = response.data.customer;
            this.redirectionToPaymentPage();
          })
          .catch(error => {
            this.showAlert();
            this.loading = false;
            this.toast("Error", error.response.data.message, 'danger')

            this.response = error.response.data;
          });

    },

    onReset() {
      // event.preventDefault();
      // Reset our form values
      this.form.fullname = "";
      this.form.phone = "";
      this.form.businessname = "";
      this.form.businessdesc = "";
      this.form.subscription_period = "";
      this.form.subscriber_numbers = [
        {
          key: 1,
          value: ""
        }
      ];
      this.form.voice = "male";
      this.form.ref = "";
      this.form.terms_and_conditions = 0;
      // Trick to reset/clear native browser form validation state
      this.show = false;
      this.$nextTick(() => {
        this.show = true;
      });
    },

    countDownChanged(dismissCountDown) {
      this.dismissCountDown = dismissCountDown;
    },

    showAlert() {
      this.dismissCountDown = this.dismissSecs;
    },

    /** Paymemnt   **/
    redirectionToPaymentPage() {
      let verificationPage = window.site_url + "/verification/form?customer_id=" + this.customer.id;
      window.location = verificationPage;
    },

    submitPayment() {
      this.loading = true;
      axios
          .post("signup", this.form)
          .then(response => {
            this.loading = false;
            this.response = response.data;

            this.showAlert();

            if (response.data.message.length != 0) {
              this.response.message = JSON.stringify(
                  response.data.message,
                  null,
                  4
              );
            }

            this.signupToast("b-toaster-bottom-right", true);

            this.onReset();
          })
          .catch(error => {
            this.showAlert();
            this.loading = false;
            this.response = error.response.data;
          });
    },

    /*** Helpers **/
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
    console.log("Created form")
  },
  mounted() {
    console.log("Created form....")
  }


};
</script>
