<template>
<div class="container mt-4">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>{{ __("vue.Form Name") }}</th>
                <th>{{ __("vue.Num Submissions") }}</th>
                <th>{{ __("vue.Status") }}</th>
                <th>{{ __("vue.Action") }}</th>
            </tr>
        </thead>
        <tbody>
            <tr
            v-for="(form, index) in projectForms"
            :key="form.id">
                <td>{{ form.title }}</td>
                <td>{{ form.records }}</td>
                <td>
                    <p v-if="!form.kobo_id">{{ __("vue.Undeployed") }}</p>
                    <p v-if="form.kobo_id && form.is_active">
                        {{ __("vue.Deployed") }} -
                        <a target="_blank" :href="'https://kf.kobotoolbox.org/#/forms/'+form.kobo_id+'/summary'">{{ __("vue.Show on KoBoToolbox") }}</a>
                    </p>
                    <p v-if="form.kobo_id && !form.is_active">
                        {{ __("vue.Uploaded") }} -
                        <a target="_blank" :href="'https://kf.kobotoolbox.org/#/forms/'+form.kobo_id+'/summary'">{{ __("vue.Show on KoBoToolbox") }}</a>
                    </p>
                    <p v-if="form.kobo_id && form.kobo_version_id && !form.is_active">
                        {{ __("vue.Archived") }} -
                        <a target="_blank" :href="'https://kf.kobotoolbox.org/#/forms/'+form.kobo_id+'/summary'">{{ __("vue.Show on KoBoToolbox") }}</a>
                    </p>
                </td>
                <td class="d-flex justify-content-start">
                    <button
                    class="btn btn-sm mr-3"
                    :class="form.kobo_id ? 'btn-info' : 'btn-success'"
                    @click="deployForm(index)"
                    :disabled="form.processing==1"
                    >
                        <span v-if="!form.kobo_id">Deploy Form</span>
                        <span v-if="form.kobo_id && form.is_active">{{ __("vue.Update to latest form") }}</span>
                        <span v-if="form.kobo_id && !form.is_active">{{ __("vue.Re-deploy form") }}</span>
                    </button>
                    <button
                    v-if="form.kobo_id && form.is_active"
                    class="btn btn-warning btn-sm mr-3"
                    @click="archiveForm(index)"
                    :disabled="form.processing==1"
                    >
                        {{ __("vue.Archive form") }}
                    </button>
                    <div v-if="form.processing==1">
                        <span class="spinner-border spinner-border-sm text-muted"></span>
                    </div>

                    <button
                    class="btn btn-success btn-sm"
                    @click="download(index)"
                    >
                        <!-- {{ __("vue.Download") }} -->
                    <i class="fa fa-download"></i>
                    </button>



                </td>
            </tr>
        </tbody>
    </table>
    <button
    @click="getData()"
    class="btn btn-success"
    :disabled="anyProcessing==true"
    >{{ __("vue.Get data from KoBoToolbox") }}</button>
</div>
</template>

<script>

    const rootUrl = process.env.MIX_APP_URL
    export default {
        props: ['projectSlug','userId'],
        data() {
            return {
                projectForms: [],
            }
        },

        computed: {
            anyProcessing: function() {
                return this.projectForms.some(x => x.processing == true);
            }
        },

        mounted() {

            axios.get(rootUrl+'/projects/'+this.projectSlug+'/projectxlsforms')
            .then((response) => {
                this.projectForms = response.data
            })

            this.$echo.private('App.User.'+this.userId)
            .listen('KoboUploadReturnedError', (payload) => {

                console.log("BOOM! errr... wait...");

                this.projectForms = this.projectForms.map( projectForm => {
                    if(projectForm.id === payload.form.id) {
                        return payload.form;
                    }
                    return projectForm
                })
            })


            this.$echo.private('App.User.'+this.userId)
            .listen('KoboDeploymentReturnedSuccess', (payload) => {

                console.log("BOOM!");

                this.projectForms = this.projectForms.map( projectForm => {
                    if(projectForm.id === payload.form.id) {
                        return payload.form;
                    }
                    return projectForm
                })
            })

            .listen('KoboGetDataReturnedSuccess', (payload) => {

                axios.post(rootUrl+'/projectxlsforms/'+payload.form.id+'/getdata')
                .then((response) => {
                    const index = this.projectForms.findIndex(x => x.id === payload.form.id);
                    this.projectForms[index]['records'] = response.data.length;
                    this.projectForms[index]['processing'] = false;
                })
            })

            .listen('KoboGetDataReturnedError', (payload) => {

                axios.post(rootUrl+'/projectxlsforms/'+payload.form.id+'/getdata')
                .then((response) => {
                    const index = this.projectForms.findIndex(x => x.id === payload.form.id);
                    this.projectForms[index]['processing'] = false;
                })
            });
        },

        methods: {
            deployForm(index) {
                this.projectForms[index].processing = true;

                axios.post(rootUrl+'/projectxlsforms/'+this.projectForms[index]['id']+'/deploytokobo/')
                .then((response) => {
                    console.log(response);
                })
            },

            archiveForm(index) {
                this.projectForms[index].processing = true;

                axios.post(rootUrl+'/projectxlsforms/'+this.projectForms[index]['id']+'/archive')
                .then((response) => {
                    console.log(response);
                })
            },

            getData() {

                this.projectForms.forEach((projectForm, index) => {

                    if(projectForm.kobo_id) {

                        this.projectForms[index]['processing'] = true;

                        axios.post(rootUrl+'/projectxlsforms/'+projectForm.id+'/syncdata')

                    }

                });
            },

            download(index){

                axios.post(rootUrl+'/projectxlsforms/' + this.projectForms[index].id + '/download')
                .then((result) => {

                    window.location.href = result.data['path'];
                }, (error) => {

                    console.log(error);
                });
            }
        }
    }
</script>
