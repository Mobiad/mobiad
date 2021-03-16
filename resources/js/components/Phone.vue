<template>
    <div>
        <div>
            <span>
                {{ formatAsPHPArray(response.rules) }}
            </span>
            <span>
                {{ formatAsPHPArray(response.request) }}
            </span>
        </div>
        <span v-if="response.exception">{{ response.exception }}</span>
        <span v-else>Validation failed</span>

        <div v-if="response.message">
            <span>
                {{ response.message }}
            </span>
        </div>
        <div>
            <input
                id="phone"
                type="tel"
                name="phone"
                class="uk-input"
                v-model="phone"
                placeholder=""
            />
        </div>
    </div>
</template>
<script>
import debounce from "lodash/debounce";

export default {
    data() {
        return {
            parameters: "phone:",
            phone: "",
            country: "",
            country_name: "",
            withCountry: false,
            loading: false,
            response: {
                loading: false,
                request: {},
                rules: {},
                passes: null,
                message: ""
            }
        };
    },

    computed: {
        countryInputName() {
            if (this.country_name.length > 0 && this.country_name !== "phone") {
                return this.country_name;
            }

            return "field_country";
        },

        requestData() {
            const data = {
                parameters: this.parameters,
                field: this.phone
            };

            if (this.withCountry) {
                data[this.countryInputName] = this.country;
                data.country_name = this.countryInputName;
            }

            return data;
        },

        shouldValidate() {
            return (
                this.phone.trim().length > 0 ||
                (this.parameters.trim().length > 0 &&
                    this.parameters !== "phone:")
            );
        },

        showHelp() {
            return !this.shouldValidate;
        }
    },
    watch: {
        phone(value, old) {
            if (this.shouldValidate) {
                this.validate();
            }
        },

        parameters(value, old) {
            if (this.shouldValidate) {
                this.validate();
            }
        },

        country(value, old) {
            if (this.shouldValidate) {
                this.validate();
            }
        },

        country_name(value, old) {
            if (this.shouldValidate) {
                this.validate();
            }
        },

        withCountry(value, old) {
            if (this.shouldValidate) {
                this.validate();
            }
        }
    },
    methods: {
        toggle() {
            this.withCountry = !this.withCountry;
        },

        formatAsPHPArray(json) {
            if (typeof json === "undefined") {
                return "";
            }

            return JSON.stringify(json, null, 4)
                .replace(/^{/g, "[")
                .replace(/}$/g, "]")
                .replace(/": /g, "' => ")
                .replace(/"/g, "'");
        },

        validate: debounce(function() {
            this.loading = true;

            axios
                .post("api/validate", this.requestData)
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
                })
                .catch(error => {
                    this.loading = false;
                    this.response = error.response.data;
                });
        }, 300)
    }
};
</script>
