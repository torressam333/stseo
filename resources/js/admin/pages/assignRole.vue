<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <p class="_title0">Role Management
                        <Select v-model="data.role_id" placeholder="Select admin type..." id="roleSelect">
                            <Option
                                :value="role.id"
                                v-for="(role, i) in roles" :key="i"
                                v-if="roles.length"
                            >{{role.roleName}}</Option>
                        </Select>
                    </p>

                    <div class="_overflow _table_div">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>Resource Name</th>
                                <th>Read Permission</th>
                                <th>Write Permission</th>
                                <th>Update Permission</th>
                                <th>Delete Permission</th>
                            </tr>
                            <!-- TABLE TITLE -->

                            <tr>
                                <td>Blog</td>
                                <td>Yes</td>
                                <td>Yes</td>
                                <td>Yes</td>
                                <td>Yes</td>
                            </tr>
                            <!-- ITEMS -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';

export default {
    data() {
        return {
            data: {
                roleName: '',
                role_id: null
            },
            resources: [],
            roles: [],
        }
    },
    methods: {
        format_date(value) {
            if (value) {
                return moment(String(value)).format('MMM DD YYYY, h:mm:ss a');
            }
        }
    },
    async created() {
        console.log(this.$route)
        const res = await this.callApi('get', 'app/get_roles');
        if (res.status === 200) {
            //Fill the roles[] in data
            this.roles = res.data;
        } else {
            this.swr();
        }
    },
}
</script>


<style scoped>
#roleSelect{
    width: 300px;
}
</style>


