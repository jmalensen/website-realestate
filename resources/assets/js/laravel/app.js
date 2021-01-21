/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('es6-promise').polyfill();

import DateComponent from './components/date.vue';
import FlatPickrComponent from 'vue-flatpickr-component';
import TelComponent from './components/Tel.vue';
import EmailComponent from './components/Email.vue';
import DateTimeComponent from './components/DateTime.vue';
import ModalComponent from './components/Modal.vue';
import Select2Component from './components/select2.vue';

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.mixin({
    methods: {
        route: window.route
    }
});

Vue.component('flatPickr', FlatPickrComponent);
Vue.component('tel', TelComponent);
Vue.component('email', EmailComponent);
Vue.component('DateTime', DateTimeComponent);
Vue.component('date', DateComponent);
Vue.component('modal', ModalComponent);
Vue.component('v-select', Select2Component);


Vue.filter('formatDateTime', function(value) {
  if (value) {
    return moment(String(value)).format('DD/MM/YYYY HH:mm')
  }
});

Vue.filter('formatDate', function(value) {
  if (value) {
    return moment(String(value)).format('DD/MM/YYYY')
  }
});

Vue.filter('formatHumanDate', function(value) {
  if (value) {
    return moment(String(value)).fromNow();
  }
});

Vue.filter('trim', function(value) {
  return value.trim();
});

Vue.filter('nl2br', function(value) {
  return value.replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1<br/>$2');
});
