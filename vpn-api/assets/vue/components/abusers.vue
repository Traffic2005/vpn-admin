<template>
    <div class="row" id="abusers">
        <hr>
            <form class="form-inline">
                <div class="form-group">
                    <button class="btn btn-primary" type="button" @click="generateReport">Generate Data</button>
                </div>

                <div class="form-group">
                    <select class="form-control" @change="getReport" v-model="selected">
                        <option v-for="month in months" :value="month.value">{{ month.name }}</option>
                    </select>
                </div>
            </form>
        <hr>

        <div class="col-md-6">
            <abuse-users :tableData="reportData.abuseUsers"></abuse-users>
        </div>
        <div class="col-md-6">
            <abuse-companies :tableData="reportData.abuseCompany"></abuse-companies>
        </div>
        <!-- <button class="button btn-success" @click="addUsers">Add Abuse Users</button>-->
    </div>
</template>
<script>

    import AbuseUsers from './abusers/abuseUsers.vue'
    import AbuseCompanies from './abusers/abuseCompanies.vue'

    export default {
        components: {
            AbuseUsers,
            AbuseCompanies
        },
        data() {
            return {
                selected: "",
                reportData: {
                    abuseUsers: [],
                    abuseCompany: [],
                    months: []
                }
            }
        },
        created: function () {
            this.fillMonthSelect()
        },
        watch: {
            selected: function (val) {
                const that = this;
                this.$http.get('/report/' + val).then((response) => {
                    let data = response.body;
                    let abuseCompany = data.abuseCompany;
                    let abuseUsers = data.abuseUsers;
                    $.each(abuseUsers, function (index, value) {
                        this.used = that.formatBytes(value.used);
                    });
                    $.each(abuseCompany, function (index, value) {
                        this.used = that.formatBytes(value.used);
                        this.quota = that.formatBytes(value.quota);
                    });
                    this.reportData.abuseUsers = abuseUsers;
                    this.reportData.abuseCompany = abuseCompany;
                }, (response) => {

                });
            },
        },
        methods: {
            fillMonthSelect() {
                let date = new Date();
                const months = [];
                for (let i = 0; i <= 6; i++) {
                    months.push({
                        name: date.toLocaleString("en-us", {month: "long"}),
                        value: (date.getMonth() + 1)
                    });
                    date.setMonth(date.getMonth() - 1);
                }
                this.months = months;
                this.selected = months[0].value
            },
            getReport() {
                console.log(this.selected)
            },
            generateReport() {
                this.$http.post('/generate').then((response) => {
                    let company = response.body;
                    alert(SUCCESS);
                }, (response) => {
                    alert(ERROR);

                });
            },
            formatBytes(bytes, decimals) {
                if (bytes == 0) return '0 Bytes';
                var k = 1024,
                    dm = decimals || 2,
                    sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
                    i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
            }
        },

    }
</script>