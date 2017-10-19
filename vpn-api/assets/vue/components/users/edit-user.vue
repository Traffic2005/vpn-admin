<template>
    <div id="update-user">
        <h1>Update User</h1>

        <p>
            <router-link :to="{ name: 'home' }">Home</router-link>
        </p>

        <notification v-bind:notifications="notifications"></notification>

        <form @submit.prevent="validateBeforeSubmit">
            <div class="form-group">
                <label name="user_id">ID</label>
                <input type="text" class="form-control" disabled v-model="user.id" id="user_id">
            </div>

            <div class="form-group">
                <label name="user_name">Name</label>
                <input name="name" v-model="user.name" v-validate="'required|alpha_spaces'" :class="{'input': true, 'is-danger': errors.has('name') }" type="text" placeholder="Name">
                <i v-show="errors.has('name')" class="fa fa-warning"></i>
                <span v-show="errors.has('name')" class="help is-danger">{{ errors.first('name') }}</span>
            </div>
            <div class="form-group">
                <label name="user_price">Email</label>
                <input v-validate="'required|email'" v-model="user.email"
                       :class="{'input': true, 'is-danger': errors.has('email') }" name="email" type="text"
                       placeholder="Email">
                <span v-show="errors.has('email')" class="help is-danger">{{ errors.first('email') }}</span>
            </div>

            <div class="form-group">
                <label name="user_price">Company</label>
                <select class="form-control" id="sel1" v-model="user.company_id">
                    <option v-for="company in companies" :value="company.id">{{ company.name }}</option>
                </select>
            </div>

            <div class="form-group">
                <button class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</template>

<script>
    import Notification from './../notifications.vue';

    export default {
        data() {
            return {
                user: {},
                email: "",
                notifications: [],
                companies: {}
            }
        },

        created: function () {
            this.getUser();
        },

        methods: {
            validateBeforeSubmit() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.$http.put('/user/' + this.$route.params.id, {
                            name: this.user.name,
                            email: this.user.email,
                            company_id: this.user.company_id,
                        }, {
                            emulateJSON: true
                        })
                            .then((response) => {
                                this.notifications.push({
                                    type: 'success',
                                    message: 'User updated successfully'
                                });
                                this.$router.push({name: 'home'})
                            }, (response) => {
                                this.notifications.push({
                                    type: 'error',
                                    message: 'User not updated'
                                });
                            });
                        return;
                    }

                    alert('Correct them errors!');
                });
            },
            getUser: function () {
                this.$http.get('/user/' + this.$route.params.id).then((response) => {
                    this.user = response.body;
                }, (response) => {

                });
                this.$http.get('/company/').then((response) => {
                    this.companies = response.body;
                }, (response) => {

                });
            }
        },

        components: {
            'notification': Notification
        }
    }
</script>
<style>
    .input.is-danger {
        border-color: #ff3860;
    }
    .input
    {
        -moz-appearance: none;
        -webkit-appearance: none;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        background-color: #fff;
        border: 1px solid #dbdbdb;
        border-radius: 3px;
        color: #363636;
        display: -webkit-inline-box;
        display: -ms-inline-flexbox;
        display: inline-flex;
        font-size: 14px;
        height: 32px;
        -webkit-box-pack: start;
        -ms-flex-pack: start;
        justify-content: flex-start;
        line-height: 24px;
        padding-left: 8px;
        padding-right: 8px;
        position: relative;
        vertical-align: top;
        box-shadow: inset 0 1px 2px hsla(0,0%,4%,.1);
        max-width: 100%;
        width: 100%;
    }
</style>