<template>
<div>
    <div class="row">
        <div class="col-md-12" style="overflow: scroll">
            <b-table
                :items="nutrientBalances"
                :fields="nutrientColumns"

            ></b-table>

        </div>
    </div>
</div>
</template>
<script>
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
                    label: 'Country',
                },

                {
                    key: 'farmer_field.village_community',
                    label: 'Village',
                },
                {
                    key: 'farmer_field.farmer_name',
                    label: 'farmer',
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