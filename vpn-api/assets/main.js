import Vue from 'vue'
import VeeValidate from 'vee-validate';

Vue.use(VeeValidate);

import App from './vue/App.vue'


import VueRouter from 'vue-router'
import {routes} from './vue/routes'

Vue.use(VueRouter);


import {ClientTable, Event} from 'vue-tables-2';

Vue.use(ClientTable);

import VueResource from 'vue-resource';

Vue.use(VueResource);

var router = new VueRouter({routes: routes/*, mode: 'history'*/});
new Vue(Vue.util.extend({router}, App)).$mount('#app');






