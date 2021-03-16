<template>
    <b-table
        :items="JSON.parse(items)"
        :fields="fields"
        striped
        responsive="sm"
    >
        <template #cell(fullname)="data">
            {{ data.value.fullname }}
        </template>

        <template #cell(phone)="data">
            {{ data.value.phone }}
        </template>

        <template #cell(businessname)="data">
            {{ data.value.businessname }}
        </template>

        <template #cell(businessdesc)="data">
            <span class="text-wrap ellipsis-text">{{
                data.value.businessdesc
            }}</span>
        </template>

        <template #cell(subscription_period)="data">
            {{ data.value.subscription_period }}
        </template>

        <template #cell(show_details)="row">
            <b-button size="sm" @click="row.toggleDetails" class="mr-2">
                {{ row.detailsShowing ? "Hide" : "Show" }} Details
            </b-button>

            <!-- As `row.showDetails` is one-way, we call the toggleDetails function on @change -->
            <b-form-checkbox
                v-model="row.detailsShowing"
                @change="row.toggleDetails"
            >
                Details via check
            </b-form-checkbox>
        </template>

        <template #row-details="row">
            <b-card>
                <b-row class="mb-2">
                    <b-col sm="3" class="text-sm-right"><b>Age:</b></b-col>
                    <b-col>{{ row.item.age }}</b-col>
                </b-row>

                <b-row class="mb-2">
                    <b-col sm="3" class="text-sm-right"
                        ><b>Is Active:</b></b-col
                    >
                    <b-col>{{ row.item.isActive }}</b-col>
                </b-row>

                <b-button size="sm" @click="row.toggleDetails"
                    >Hide Details</b-button
                >
            </b-card>
        </template>
    </b-table>
</template>
<script>
export default {
    props: {
        itemsta: () => []
    },
    data() {
        return {
            fields: [
                "Fullname",
                "Phone",
                "Business Name",
                "Subscription Period"
            ]
        };
    }
};
</script>
