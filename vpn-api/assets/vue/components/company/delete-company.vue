<template>
    <div id="delete-company">
        <h1>Delete Company {{ company.name }}</h1>

        <p><router-link :to="{ name: 'home' }">Home</router-link></p>

        <notification v-bind:notifications="notifications"></notification>

        <form v-on:submit.prevent="deleteCompany">
            <p><button class="btn btn-danger">Delete Company</button></p>
        </form>
    </div>
</template>

<script>
    import Notification from './../notifications.vue';

    export default{
        data(){
            return{
                company:{},
                notifications:[]
            }
        },


        methods: {

            deleteCompany: function()
            {
                this.$http.delete('/company/' + this.$route.params.id, this.company, {
                    headers : {
                        'Content-Type' : 'application/json'
                    }
                }).then((response) => {
                    this.$router.push({name: 'home'})
                }, (response) => {
                    this.notifications.push({
                        type: 'danger',
                        message: 'Company could not deleted'
                    });
                });
            }
        },

        components: {
            'notification' : Notification
        }
    }
</script>
