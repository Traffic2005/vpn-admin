<template>
        <div>
            <h2>Abusers: Users</h2>
            <div id="abuseCompany">
                <v-client-table :data="tableData" :columns="columns" :options="options"></v-client-table>
            </div>
        </div>
</template>
<script>

    export default {
        props:['tableData'],
        data() {
            return {
                columns: ['id', 'name', 'used', 'quota'],
            }
        }
    }
</script>