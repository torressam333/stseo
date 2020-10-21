<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <p class="_title0">Role Management
                        <Button @click="addModal=true" v-if="isWritePermitted">
                            <Icon type="md-add">Default</Icon>
                            Add a new role
                        </Button>
                    </p>

                    <div class="_overflow _table_div">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>ID</th>
                                <th>Role type</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                            <!-- TABLE TITLE -->

                            <!-- Display Roles From DB -->
                            <tr v-for="(role, i) in roles" :key="i" v-if="roles.length">
                                <td>{{role.id}}</td>
                                <td class="_table_name">{{role.roleName}}</td>
                                <td>{{format_date(role.created_at)}}</td>
                                <td>
                                    <Button
                                        type="info"
                                        size="small"
                                        @click="showEditModal(role, i)"
                                        v-if="isUpdatePermitted"
                                    >
                                        Edit
                                    </Button>
                                    <Button type="error" size="small"
                                            @click="showDeletingModal(role, i)"
                                            :loading="role.isDeleting"
                                            v-if="isDeletePermitted"
                                    >Delete
                                    </Button>
                                </td>
                            </tr>
                            <!-- ITEMS -->
                        </table>
                    </div>
                </div>

                <!--Add Role modal-->
                <Modal
                    v-model="addModal"
                    title="Add Role"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Input v-model="data.roleName" placeholder="Role name"/>

                    <div slot="footer">
                        <Button type="default" @click="addModal=false">Close</Button>
                        <Button
                            type="success"
                            @click="add"
                            :disabled="isAdding"
                            :loading="isAdding"
                        >{{ isAdding ? 'Adding...' : 'Add Role' }}
                        </Button>
                    </div>
                </Modal>

                <!--Edit role modal-->
                <Modal
                    v-model="editModal"
                    title="Edit role"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Input v-model="editData.roleName" placeholder="Edit role name"/>

                    <div slot="footer">
                        <Button type="default" @click="editModal=false">Close</Button>
                        <Button
                            type="info"
                            @click="edit"
                            :disabled="isAdding"
                            :loading="isAdding"
                        >{{ isAdding ? 'Editing...' : 'Edit role' }}
                        </Button>
                    </div>
                </Modal>

                <!--Delete role modal-->
                <deleteModal></deleteModal>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import DeleteModal from "../components/deleteModal";
import {mapGetters} from 'vuex';

export default {
    components: {DeleteModal},
    data() {
        return {
            data: {
                roleName: ''
            },
            //Don't display modal by default
            addModal: false,
            editModal: false,
            isAdding: false,
            roles: [],
            editData: {
                roleName: ''
            },
            index: -1,
            showDeleteModal: false,
            isDeleting: false,
            deleteItem: {},
            deletingIndex: -1
        }
    },
    methods: {
        async add() {
            if (this.data.roleName.trim() === '') return this.e('A role name is required');
            //axios call from common.js
            const res = await this.callApi('post', '/app/create_role', this.data);
            if (res.status === 201) {
                this.roles.unshift(res.data)
                this.s('Role has been added successfully!');
                //Close modal when role is added
                this.addModal = false;
                this.data.roleName = '';
            } else {
                if (res.status === 422) {
                    if (res.data.errors.roleName) {
                        this.i(res.data.errors.roleName[0]);
                    }
                } else {
                    this.swr();
                }
            }
        },
        async edit() {
            if (this.editData.roleName.trim() === '') return this.e('Role name is required')
            const res = await this.callApi('post', 'app/edit_role', this.editData);
            if (res.status === 200) {
                //Go to role index and replace with edited role name
                this.roles[this.index].roleName = this.editData.roleName;
                this.s('Role has been edited successfully');
                this.editModal = false;
            } else {
                if (res.status === 422) {
                    if (res.data.errors.roleName) {
                        this.i(res.data.errors.roleName[0]);
                    }
                } else {
                    this.swr();
                }
            }
        },
        showEditModal(role, index) {
            //Assign data to be usable in editData v-model
            let obj = {
                id: role.id,
                roleName: role.roleName
            }
            this.editData = obj;
            this.editModal = true;
            this.index = index;
        },
        showDeletingModal(role, i) {
            const deleteModalObj = {
                showDeleteModal: true,
                deleteUrl: 'app/delete_role',
                data: role,
                deletingIndex: i,
                isDeleted: false
            };
            this.$store.commit('setDeletingModalObj', deleteModalObj);
        },
        format_date(value) {
            if (value) {
                return moment(String(value)).format('MMM DD YYYY, h:mm:ss a');
            }
        }
    },
    async created() {
        const res = await this.callApi('get', 'app/get_roles');
        if (res.status === 200) {
            //Fill the roles[] in data
            this.roles = res.data;
        } else {
            this.swr();
        }
    },
    computed: {
        ...mapGetters(['getDeleteModalObj'])
    },
    watch: {
        getDeleteModalObj(obj) {
            if (obj.isDeleted) {
                this.roles.splice(obj.deletingIndex, 1);
            }
        }
    }
}
</script>



