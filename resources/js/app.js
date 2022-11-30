/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import moment from 'moment'
import Vue from 'vue';

window.Vue = require('vue').default;
Vue.prototype.moment = moment;


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('home-component',  require('./components/publicComponents/HomeComponent.vue').default);
Vue.component('advent-calendar-component',  require('./components/publicComponents/AdventCalendarComponent.vue').default);
Vue.component('team-component',  require('./components/publicComponents/TeamComponent.vue').default);
Vue.component('team-member-photo-left-component',  require('./components/publicComponents/TeamMemberPhotoLeftComponent.vue').default);
Vue.component('team-member-photo-right-component',  require('./components/publicComponents/TeamMemberPhotoRightComponent.vue').default);

Vue.component('members-home-component',  require('./components/membersComponents/MembersHomeComponent.vue').default);
Vue.component('members-play-download-component',  require('./components/membersComponents/MembersPlayDownloadComponent.vue').default);
Vue.component('members-textbook-component',  require('./components/membersComponents/MembersTextbookComponent.vue').default);
Vue.component('members-video-component',  require('./components/membersComponents/MembersVideoComponent.vue').default);
Vue.component('members-schedule-component',  require('./components/membersComponents/MembersScheduleComponent.vue').default);
Vue.component('members-user-settings-component',  require('./components/membersComponents/MembersUserSettingsComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
