<template>
<div>
    <div class="row">
        <div class="col-md-12" style="overflow: scroll">
            <b-table
                :items="nutrientBalances"
                :fields="nutrientColumns"
            >
            <template #head(farmer_field_id)=item>
                {{ __('vue.farmer_field_id') }}
            </template>
            <template #head(farmer_field.country_id)=item>
                {{ __('vue.country') }}
            </template>
            <template #head(farmer_field.village_community)=item>
                {{ __('vue.village') }}
            </template>
            <template #head(farmer_field.farmer_name)=item>
                {{ __('vue.farmer') }}
            </template>
            <template #head(year)=item>
                {{  __('vue.year') }}
            </template>,
            <template #head(tot_org_Ninput)=item>
                {{  __('vue.tot_org_Ninput') }}
            </template>,
            <template #head(tot_org_Pinput)=item>
                {{  __('vue.tot_org_Pinput') }}
            </template>,
            <template #head(tot_org_Kinput)=item>
                {{  __('vue.tot_org_Kinput') }}
            </template>,
            <template #head(tot_inorg_Ninput)=item>
                {{  __('vue.tot_inorg_Ninput') }}
            </template>,
            <template #head(tot_inorg_Pinput)=item>
                {{  __('vue.tot_inorg_Pinput') }}
            </template>,
            <template #head(tot_inorg_Kinput)=item>
                {{  __('vue.tot_inorg_Kinput') }}
            </template>,
            <template #head(Total_cropNexport)=item>
                {{  __('vue.Total_cropNexport') }}
            </template>,
            <template #head(Total_cropPexport)=item>
                {{  __('vue.Total_cropPexport') }}
            </template>,
            <template #head(Total_cropKexport)=item>
                {{  __('vue.Total_cropKexport') }}
            </template>,
            <template #head(balance_N)=item>
                {{  __('vue.balance_N') }}
            </template>,
            <template #head(balance_P)=item>
                {{  __('vue.balance_P') }}
            </template>,
            <template #head(balance_K)=item>
                {{  __('vue.balance_K') }}
            </template>,
            </b-table>

        </div>
    </div>
</div>
</template>
<script>

import __ from "../trans.js"
export default {
    props: ['projectId'],

    data() {
        return {
            samplesDisplay: [],
            hasHR: false,
            hasCustomR: false,
            nutrientBalances: [],
            nutrientColumns: [
                'farmer_field_id',
                {
                    key: 'farmer_field.country_id',
                    label: "country",
                },

                {
                    key: 'farmer_field.village_community',
                    label: "village",
                },
                {
                    key: 'farmer_field.farmer_name',
                    label: "farmer",
                },
                'year',
                'tot_org_Ninput',
                'tot_org_Pinput',
                'tot_org_Kinput',
                'tot_inorg_Ninput',
                'tot_inorg_Pinput',
                'tot_inorg_Kinput',
                'Total_cropNexport',
                'Total_cropPexport',
                'Total_cropKexport',
                'balance_N',
                'balance_P',
                'balance_K',
            ]
        }
    },
    mounted: function(){
        axios.get(`/nutrientbalance/${this.projectId}/json`)
        .then((res) => {
            this.nutrientBalances = res.data;
        })
    }

}
</script>
