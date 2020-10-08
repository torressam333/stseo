<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <p class="_title0">Role Management
                        <Select v-model="data.id" id="roleSelect">
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

                            <tr v-for="(r, i) in resources" :key="i">
                                <td>{{r.resourceName}}</td>
                                <td><Checkbox v-model="r.read"></Checkbox></td>
                                <td><Checkbox v-model="r.write"></Checkbox></td>
                                <td><Checkbox v-model="r.update"></Checkbox></td>
                                <td><Checkbox v-model="r.delete"></Checkbox></td>
                            </tr>
                            <!-- ITEMS -->
                            <div class="center_button">
                                <Button
                                    class="center_button"
                                    type="primary"
                                    :loading="isSending"
                                    :disabled="isSending"
                                    @click="assignRoles"
                                >Assign Permissions</Button>
                            </div>
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
                id: null
            },
            isSending: false,
            roles: [],
            resources: [
                {
                    resourceName: 'Dashboard',
                    read: false,
                    write: false,
                    update: false,
                    delete: false,
                    name: 'home',
                },
                {
                    resourceName: 'Tags',
                    read: false,
                    write: false,
                    update: false,
                    delete: false,
                    name: 'tags',
                },
                {
                    resourceName: 'Categories',
                    read: false,
                    write: false,
                    update: false,
                    delete: false,
                    name: 'category',
                },
                {
                    resourceName: 'Admin Users',
                    read: false,
                    write: false,
                    update: false,
                    delete: false,
                    name: 'adminusers',
                },
                {
                    resourceName: 'Role Management',
                    read: false,
                    write: false,
                    update: false,
                    delete: false,
                    name: 'role',
                },
                {
                    resourceName: 'Role Assignment',
                    read: false,
                    write: false,
                    update: false,
                    delete: false,
                    name: 'assignRole',
                },
            ],
        }
    },
    methods: {
        async assignRoles() {
            let data = JSON.stringify(this.resources);
            //Pass this data  and id to be used server-side in our controller
            const res = await this.callApi('post', '/app/assign_roles', {'permission': data, id: this.data.id});
            if (res.status === 200) {
                this.s('Permissions successfully assigned');
            }else{
                this.swr();
            }
        }
    },
    async created() {
        const res = await this.callApi('get', 'app/get_roles');
        if (res.status === 200) {
            //Fill the roles[] in data
            this.roles = res.data;
            if (res.data.length) {
                this.data.id = res.data[0].id;
                if (res.data[0].permission) {
                    this.resources = JSON.parse(res.data[0].permission);
                }
            }
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


