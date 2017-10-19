<template>
    <div id="create-user">
        <h1>Create User</h1>

        <p><router-link :to="{ name: 'home' }">Home</router-link></p>

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
                <button class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</template>

<script>
    import Notification from './../notifications.vue';

    export default{
        data(){
            return{
                user:{},
                notifications:[],
                companies:{}
            }
        },

        created: function () {
            this.getCompanies();
        },

        methods: {
            getCompanies(){
                this.$http.get('/company/').then((response) => {
                    this.companies = response.body;
                }, (response) => {

                });
            },
            validateBeforeSubmit: function()
            { this.$validator.validateAll().then((result) => {
                if (result) {
                    this.$http.post('/user/', this.user, {
                        emulateJSON: true
                    }).then((response) => {
                        this.notifications.push({
                            type: 'success',
                            message: 'User created successfully'
                        });
                        this.$router.push({name: 'home'})

                    }, (response) => {
                        this.notifications.push({
                            type: 'error',
                            message: 'User not created'
                        });
                    });
                    return;
                }

                alert('Correct them errors!');
            });
            }
        },

        components: {
            'notification' : Notification
        }
    }
</script>
