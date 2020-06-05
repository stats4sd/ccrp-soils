import Vue from 'vue';
import ProjectFormsTable from './components/ProjectFormsTable'

import VueEcho from 'vue-echo-laravel';


Vue.use(VueEcho, {
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    wsHost: window.location.hostname,
    wsPort: process.env.MIX_APP_ENV === 'local' ? 6001 : 6002,
    wssPort: process.env.MIX_APP_ENV === 'local' ? 6001 : 6002,
    disableStats: true,
    encrypted: true,
    enabledTransports: ['ws', 'wss'],
});


Vue.component("project-forms-table",ProjectFormsTable)

let app = new Vue({
    el: '#forms-table-vue',
});
