<template>
    <div>
        <h1>All users</h1>

        <router-link :to="{ name: 'create_user' }" class="btn btn-primary">Create user</router-link>
        <hr>
        <div class="form-group">
            <input type="text" name="search" v-model="userSearch" placeholder="Search users" class="form-control"
                   v-on:keyup="searchUsers">
        </div>

        <table class="table table-hover">
            <thead>
            <tr>
                <td>ID</td>
                <td>Name</td>
                <td>Email</td>
                <td>Company</td>
                <td></td>
            </tr>
            </thead>

            <tbody>
            <tr v-for="user in users">
                <td>{{ user.id }}</td>
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td>{{ user.companyName }}</td>
                <td>
                    <router-link :to="{name: 'edit_user', params: { id: user.id }}" class="btn btn-primary">Edit
                    </router-link>
                    <router-link :to="{name: 'delete_user', params: { id: user.id }}" class="btn btn-danger">
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
                users: [],
                originalUsers: [],
                userSearch: ''
            }
        },
        created: function () {
            this.fetchProductData();
        },
        methods: {
            fetchProductData: function () {
                this.$http.get('/user').then((response) => {
                    this.users = response.body;
                    this.originalUsers = this.users;
                }, (response) => {

                });
            },

            searchUsers: function () {
                if (this.userSearch == '') {
                    this.users = this.originalUsers;
                    return;
                }

                var searchedUsers = [];
                for (var i = 0; i < this.originalUsers.length; i++) {
                    var userName = this.originalUsers[i]['name'].toLowerCase();
                    if (userName.indexOf(this.userSearch.toLowerCase()) >= 0) {
                        searchedUsers.push(this.originalUsers[i]);
                    }
                }

                this.users = searchedUsers;
            }
        }
    }</script>
<style></style>