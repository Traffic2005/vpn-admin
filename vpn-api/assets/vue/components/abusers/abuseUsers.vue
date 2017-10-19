<template>
        <div>
            <h2>Abusers: Companies</h2>
            <div id="abuseUsers">
                <v-client-table :data="tableData" :columns="columns" :options="options"></v-client-table>
            </div>
        </div>
</template>
<script>

    export default {
        props:['tableData'],
        data() {
            return {
                columns: ['name', 'used', 'company'],
            }
        }
    }
</script>