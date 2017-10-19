<template>
    <div>
        <h1>All companies</h1>
                <router-link :to="{ name: 'create_company' }" class="btn btn-primary">Create company</router-link>
       <hr>
        <div class="form-group">
            <input type="text" name="search" v-model="companySearch" placeholder="Search companies" class="form-control"
                   v-on:keyup="searchCompanies">
        </div>

        <table class="table table-hover">
            <thead>
            <tr>
                <td>ID</td>
                <td>Name</td>
                <td>Transfer quota</td>
                <td></td>
            </tr>
            </thead>

            <tbody>
            <tr v-for="company in companies">
                <td>{{ company.id }}</td>
                <td>{{ company.name }}</td>
                <td>{{  company.transferQuota / Math.pow(1024, 3)}}GB</td>
                <td>
                    <router-link :to="{name: 'edit_company', params: { id: company.id }}" class="btn btn-primary">Edit
                    </router-link>
                    <router-link :to="{name: 'delete_company', params: { id: company.id }}" class="btn btn-danger">
                        Delete
                    </router-link>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
<script>

    export default {
        components: {},
        data() {
            return {
                companies: [],
                originalCompanies: [],
                companySearch: ''
            }
        },
        created: function () {
            this.fetchProductData();
        },
        methods: {
            fetchProductData: function () {
                this.$http.get('/company').then((response) => {
                    this.companies = response.body;
                    this.originalCompanies = this.companies;
                }, (response) => {

                });
            },

            searchCompanies: function () {
                if (this.companySearch == '') {
                    this.companies = this.originalCompanies;
                    return;
                }

                var searchedCompanies = [];
                for (var i = 0; i < this.originalCompanies.length; i++) {
                    var companyName = this.originalCompanies[i]['name'].toLowerCase();
                    if (companyName.indexOf(this.companySearch.toLowerCase()) >= 0) {
                        searchedCompanies.push(this.originalCompanies[i]);
                    }
                }

                this.companies = searchedCompanies;
            }
        }
    }</script>
<style></style>