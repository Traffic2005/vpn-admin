<template>
    <div class="container">
        <vue-tabs ref="tabs">
            <v-tab title="Abusers">
                <abusers></abusers>
            </v-tab>
            <v-tab title="Users">
                <all-users></all-users>
            </v-tab>

            <v-tab title="Companies">
                <all-companies></all-companies>
            </v-tab>


        </vue-tabs>
    </div>
</template>
<script>
    import Abusers from './abusers.vue'

    import AllUsers from './users/all-users.vue'
    import AllCompanies from './company/all-companies.vue'
    import {VueTabs, VTab} from 'vue-nav-tabs'

    export default {

        data() {
            return {}
        },
        components: {
            AllUsers,
            Abusers,
            AllCompanies,
            VueTabs,
            VTab,
            activeTab: 0
        },
        watch: {
            '$route.params.id': function (id) {
            console.log(123)
            }
        },
        methods: {
            changeTab(tab) {
                this.$refs.tabs.navigateToTab(tab)
            }
        }

    }
</script>
