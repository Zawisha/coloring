/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('img-download-component', require('./components/ImgDownloadComponent').default);
Vue.component('coloring-list-component', require('./components/ColoringListComponent').default);
Vue.component('tags-component', require('./components/TagsComponent').default);
Vue.component('edit-colored-component', require('./components/EditColoredComponent').default);
Vue.component('add-fairy-title-component', require('./components/AddFairyTitleComponent').default);
Vue.component('edit-fairy-title-component', require('./components/EditFairyTitleComponent').default);
Vue.component('fairy-pagination-component', require('./components/FairyPaginationComponent').default);
Vue.component('fairy-list-component', require('./components/FairyListComponent').default);
Vue.component('modal-delete-component', require('./components/ModalDeleteComponent').default);
Vue.component('add-video-component', require('./components/AddVideoComponent').default);
Vue.component('edit-video-component', require('./components/EditVideoComponent').default);
Vue.component('video-list-component', require('./components/VideoListComponent').default);
Vue.component('users-list-component', require('./components/UsersListComponent').default);
Vue.component('front-categories-component', require('./components/FrontCategoriesComponent').default);
Vue.component('front-coloring-list-component', require('./components/FrontColoringListComponent').default);
Vue.component('front-fairy-list-component', require('./components/FrontFairyListComponent').default);
Vue.component('front-video-list-component', require('./components/FrontVideoListComponent').default);
Vue.component('read-fairy-component', require('./components/ReadFairyComponent').default);
Vue.component('cat-component', require('./components/CatComponent').default);
Vue.component('profile-component', require('./components/ProfileComponent').default);
Vue.component('front-liked-component', require('./components/FrontLikedComponent').default);
Vue.component('front-cat-component', require('./components/FrontCatComponent').default);
Vue.component('ava-component', require('./components/AvaComponent').default);
Vue.component('streak-component', require('./components/StreakComponent').default);
Vue.component('search-component', require('./components/SearchComponent').default);
Vue.component('preloader-component', require('./components/PreloaderComponent').default);
Vue.component('front-coloring-one-component', require('./components/FrontColoringOneComponent').default);
Vue.component('front-cat-one-with-colorings-component', require('./components/FrontCatOneWithColoringsComponent').default);
Vue.component('user-add-coloring-component', require('./components/UserAddColoringComponent').default);
import { Icon } from '@iconify/vue2';
import iosAlertView from 'vue-ios-alertview';
Vue.use(iosAlertView);
import Autocomplete from '@trevoreyre/autocomplete-vue'
import '@trevoreyre/autocomplete-vue/dist/style.css'
Vue.use(Autocomplete)
import 'v-slim-dialog/dist/v-slim-dialog.css'
import SlimDialog from 'v-slim-dialog'

// import VueCropper from 'vue-cropperjs';
// import 'cropperjs/dist/cropper.css';
// Vue.component(VueCropper);

//let slug = require('slug')
Vue.use(SlimDialog)

//шинна данных
export let eventBusColoring = new Vue();
export let eventBusFairy = new Vue();
export let eventBusVideo = new Vue();

//шина данных для поиска

export let eventSearch = new Vue();





// import ClassicEditor from '@ckeditor/ckeditor5-build-classic/build/ckeditor';
// import SimpleUploadAdapter from '@ckeditor/ckeditor5-upload/src/adapters/simpleuploadadapter';
// window.ClassicEditor = ClassicEditor;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

export default {

}

const app = new Vue({
    el: '#app',

});
