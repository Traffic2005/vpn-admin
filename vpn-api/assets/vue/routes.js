const AllUsers = require('./components/users/all-users.vue');
const EditUser = require('./components/users/edit-user.vue');
const CreateUser = require('./components/users/create-user.vue');
const DeleteUser = require('./components/users/delete-user.vue');

const AllCompanies = require('./components/company/all-companies.vue');
const EditCompany = require('./components/company/edit-company.vue');
const CreateCompany = require('./components/company/create-company.vue');
const DeleteCompany = require('./components/company/delete-company.vue');
const Home = require('./components/home.vue');
export const routes = [
    {
        name: 'all_users',
        path: '/users',
        component: AllUsers
    },
    {
        name: 'home',
        path: '/',
        component: Home
    },
    {
        name: 'create_user',
        path: '/users/create',
        component: CreateUser
    },
    {
        name: 'edit_user',
        path: '/users/edit/:id',
        component: EditUser
    },
    {
        name: 'delete_user',
        path: '/users/delete/:id',
        component: DeleteUser
    },
    {
        name: 'all_companies',
        path: '/company',
        component: AllCompanies
    },
    {
        name: 'create_company',
        path: '/company/create',
        component: CreateCompany
    },
    {
        name: 'edit_company',
        path: '/company/edit/:id',
        component: EditCompany
    },
    {
        name: 'delete_company',
        path: '/company/delete/:id',
        component: DeleteCompany
    }
];