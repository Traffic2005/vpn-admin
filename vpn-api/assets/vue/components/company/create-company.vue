<template>
    <div id="create-company">
        <h1>Create Company</h1>

        <p>
            <router-link :to="{ name: 'home' }">Home</router-link>
        </p>

        <notification v-bind:notifications="notifications"></notification>

        <form @submit.prevent="validateBeforeSubmit">
            <div class="form-group">
                <label name="company_id">ID</label>
                <input type="text" class="form-control" disabled v-model="company.id" id="company_id">
            </div>

            <div class="form-group">
                <label name="user_name">Name</label>
                <input name="name" v-model="company.name" v-validate="'required|alpha_spaces'"
                       :class="{'input': true, 'is-danger': errors.has('name') }" type="text" placeholder="Name">
                <i v-show="errors.has('name')" class="fa fa-warning"></i>
                <span v-show="errors.has('name')" class="help is-danger">{{ errors.first('name') }}</span>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Transfer quota</label>
                    <input type="number" class="form-control" v-model="company.transferQuota" v-validate="'required'"
                           :class="{'input': true, 'is-danger': errors.has('name') }" id="company_transferQuota" required>
                </div>
                <div class="form-group col-md-6">
                    <label name="company_price">Quota Unit</label>
                    <select class="form-control" v-model="quotaUnit">
                        <option v-for="type in types">{{type}}</option>
                    </select>
                </div>
            </div>


            <div class="form-group">
                <button class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</template>

<script>
    import Notification from './../notifications.vue';

    export default {
        data() {
            return {
                company: {},
                notifications: [],
                types: ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
                quotaUnit: 'GB'
            }
        },

        methods: {
            validateBeforeSubmit: function () {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.$http.post('/company', this.company, {
                            emulateJSON: true
                        }).then((response) => {
                            this.notifications.push({
                                type: 'success',
                                message: 'Company created successfully'
                            });
                            this.$router.push({name: 'home'})

                        }, (response) => {
                            this.notifications.push({
                                type: 'error',
                                message: 'Company not created'
                            });
                        });
                        return;
                    }
                    alert('Correct them errors!');
                });
            }
        },

        components: {
            'notification': Notification
        }
    }
</script>
