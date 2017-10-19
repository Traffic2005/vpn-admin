<template>
    <div id="delete-user">
        <h1>Delete User {{ user.name }}</h1>

        <p><router-link :to="{ name: 'home' }">Home</router-link></p>

        <notification v-bind:notifications="notifications"></notification>

        <form v-on:submit.prevent="deleteUser">
            <p><button class="btn btn-danger">Delete User</button></p>
        </form>
    </div>
</template>

<script>
    import Notification from './../notifications.vue';

    export default{
        data(){
            return{
                user:{},
                notifications:[]
            }
        },

        created: function(){
            this.getUser();
        },

        methods: {
            getUser: function () {
                this.$http.get('/user/' + this.$route.params.id).then((response) => {
                    this.user = response.body;
                }, (response) => {

                });
            },

            deleteUser: function()
            {
                this.$http.delete('/user/' + this.$route.params.id,  {
                    emulateJSON: true
                }).then((response) => {
                    this.$router.push({name: 'home'})
                }, (response) => {
                    this.notifications.push({
                        type: 'danger',
                        message: 'User could not deleted'
                    });
                });
            }
        },

        components: {
            'notification' : Notification
        }
    }
</script>
