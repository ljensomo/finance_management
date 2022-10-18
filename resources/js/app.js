/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// window.$ = window.jQuery = require('jquery');
// window.Vue = require('vue').default;

require('./bootstrap');

import { createApp } from 'vue';
import income from './components/IncomeComponent.vue'
import income_source from './components/IncomeSourceComponent.vue'
import expense from './components/ExpenseComponent.vue'
import login from './components/LoginComponent.vue'
import user from './components/UserComponent.vue'
import register_user from './components/RegisterUserComponent.vue'
import user_update from './components/UserUpdateComponent.vue'
import account from './components/AccountComponent.vue'
import expense_category from './components/ExpenseCategoryComponent.vue'
import budget from './components/BudgetComponent.vue'
import budget_detail from './components/BudgetDetailComponent.vue'
import index from './components/IndexComponent.vue'

let app=createApp({})
app.component('income-component', income)
app.component('income-source-component', income_source)
app.component('expense-component', expense)
app.component('login-component', login)
app.component('user-component', user)
app.component('register-user-component', register_user)
app.component('user-update-component', user_update)
app.component('account-component', account)
app.component('expense-category-component', expense_category);
app.component('budget-component', budget)
app.component('budget-detail-component', budget_detail)
app.component('index-component', index)

app.mount("#app")

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);
//Vue.component('income-component', require('./components/IncomeComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
// const app = new Vue({
//     el: '#app',
// });
