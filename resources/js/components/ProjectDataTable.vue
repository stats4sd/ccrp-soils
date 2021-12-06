<template>
<div>
    <div class="container mt-4">
        {{ __("vue.samples-count") }}: <b> {{ samples.length }}</b>
    </div>
    <div class="row">
        <div class="col-md-11">
            <table class="table table-striped table-bordered w-100">
                <tr>
                    <th rowspan="2">{{ __("vue.Sample ID") }}</th>

                    <th
                        v-for="identifier in project.identifiers"
                        :key="identifier.name"
                        rowspan="2">
                        {{ identifier.label }}
                    </th>


                    <th rowspan="2">
                        {{ __("vue.POXC Value") }}
                        <br/>({{ __("vue.POXC Units")}})
                    </th>
                    <th rowspan="2">
                        {{ __("vue.p Value") }}
                        <br/>({{ __("vue.P Units")}})
                    </th>
                    <th rowspan="2" v-if="hasHR">
                        {{ __("vue.p HR Value") }}
                        <br/>( {{ __("vue.P Units") }})
                    </th>
                    <th rowspan="2" v-if="hasCustomR">
                        {{ __("vue.p Custom R Value") }}
                        <br/>( {{ __("vue.P Units") }})
                    </th>
                    <th rowspan="2">{{ __("vue.pH Value") }}</th>
                    <th rowspan="2" style="border-right: 1px solid darkgray">{{__("vue.Amino N") }}<br>({{ __("vue.Amino N Units") }})</th>
                    <th rowspan="2">{{__("vue.POM Percent") }}<br>({{ __("vue.POM Percent Units") }})</th>
                    <th rowspan="2" style="border-right: 1px solid darkgray">{{__("vue.POM Diameter") }}<br>({{ __("vue.POM Diameter Units")}})</th>
                    <th colspan="3" style="border-left: 1px solid darkgray">{{__("vue.Stable Aggregates") }}</th>
                </tr>
                <tr class="w-100">
                    <th class="font-weight-normal">2mm</th>
                    <th style="border-right: 1px solid lightgray" class="font-weight-normal">250μm</th>
                    <th style="border-left: 1px solid lightgray">{{ __("vue.Total") }}</th>
                </tr>

                <tr v-for="sample in samplesDisplay" :key="sample.id">
                    <td>{{ sample.id }}</td>


                    <td
                        v-for="identifier in project.identifiers"
                        :key="identifier.name"
                    >
                        {{ sample.identifiers ? sample.identifiers[identifier.name] : ''}}
                    </td>


                    <td>{{ sample.poxc_result }} </td>
                    <td>{{ sample.lr_p_result }} </td>
                    <td v-if="hasHR">{{ sample.hr_p_result }} </td>
                    <td v-if="hasCustomR">{{ sample.custom_r_p_result }} </td>
                    <td>{{ sample.ph_result }}</td>
                    <td style="border-right: 1px solid darkgray">{{ sample.mg_kg_aminsugar_n }}</td>
                    <td>{{ sample.pom_percent }}</td>
                    <td style="border-right: 1px solid darkgray">{{ sample.pom_diameter }}</td>
                    <td style="border-left: 1px solid darkgray">{{ sample.twomm_aggreg_pct_result }} <span v-if="sample.twomm_aggreg_pct_result">%</span></td>
                    <td style="border-right: 1px solid lightgray">{{ sample.twofiftymicron_aggreg_pct_result }} <span v-if="sample.twofiftymicron_aggreg_pct_result">%</span></td></td{{>
                    <td style="border-left: 1px solid lightgray">{{ sample.total_stableaggregates }} <span v-if="sample.total_stableaggregates">%</span></td></td>
                </tr>
            </table>

        </div>
    </div>
</div>
</template>
<script>
export default {
    props: ['project','userId','samples'],

    data() {
        return {
            samplesDisplay: [],
            hasHR: false,
            hasCustomR: false,
        }
    },
    mounted: function(){

        console.log(this.project.identifiers)

        // round things for display
        this.samplesDisplay = this.samples.map((sample) => {
            console.log("sample:", sample);
            if(sample.poxc_result) {
                sample.poxc_result = Number(sample.poxc_result).toFixed(2);
            }
            if(sample.p_result) {
                sample.p_result = Number(sample.p_result).toFixed(2);
            }
            if(sample.lr_p_result) {
                sample.lr_p_result = Number(sample.lr_p_result).toFixed(2);
            }
            if(sample.hr_p_result) {
                sample.hr_p_result = Number(sample.hr_p_result).toFixed(2);
                this.hasHR = true
            }
            if(sample.custom_r_p_result) {
                sample.custom_r_p_result = Number(sample.custom_r_p_result).toFixed(2);
                this.hasCustomR = true
            }
            if(sample.ph_result) {
                sample.ph_result = Number(sample.ph_result).toFixed(2);
            }
            if(sample.total_stableaggregates) {
                sample.total_stableaggregates = Number(sample.total_stableaggregates).toFixed(1);
            }

            return sample;

        })
    }


}
</script>