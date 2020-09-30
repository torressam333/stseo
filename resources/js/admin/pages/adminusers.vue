<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <p class="_title0">Admins
                        <Button @click="addModal=true">
                            <Icon type="md-add">Default</Icon>
                            Add Admin
                        </Button>
                    </p>

                    <div class="_overflow _table_div">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>User Type</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                            <!-- TABLE TITLE -->

                            <!-- Display Users From DB -->
                            <tr v-for="(user, i) in users" :key="i" v-if="users.length">
                                <td>{{user.id}}</td>
                                <td class="_table_name">{{user.fullName}}</td>
                                <td>{{user.email}}</td>
                                <td>{{user.userType}}</td>
                                <td>{{format_date(user.created_at)}}</td>
                                <td>
                                    <Button type="info" size="small" @click="showEditModal(user, i)">Edit</Button>
                                    <Button type="error" size="small"
                                            @click="showDeletingModal(user, i)"
                                            :loading="user.isDeleting"
                                    >Delete
                                    </Button>
                                </td>
                            </tr>
                            <!-- ITEMS -->
                        </table>
                    </div>
                </div>

                <!--Add tag modal-->
                <Modal
                    v-model="addModal"
                    title="Add admin"
                    :mask-closable="false"
                    :closable="false"
                >
                    <div class="space">
                        <Input type="text" v-model="data.fullName" placeholder="Add Full Name"/>
                    </div>

                    <div class="space">
                        <Input type="text" v-model="data.email" placeholder="Email"/>
                    </div>

                    <div class="space">
                        <Input type="password" v-model="data.password" placeholder="Password"/>
                    </div>

                    <div class="space">
                        <Select v-model="data.userType" placeholder="Select admin type...">
                            <Option  value="Admin">Admin</Option>
                            <Option  value="Editor">Editor</Option>
                        </Select>
                    </div>

                    <div slot="footer">
                        <Button type="default" @click="addModal=false">Close</Button>
                        <Button
                            type="success"
                            @click="addAdmin"
                            :disabled="isAdding"
                            :loading="isAdding"
                        >{{ isAdding ? 'Adding...' : 'Add admin' }}
                        </Button>
                    </div>
                </Modal>

                <!--Edit tag modal-->
                <Modal
                    v-model="editModal"
                    title="Edit Admin User"
                    :mask-closable="false"
                    :closable="false"
                >

                    <div class="space">
                        <Input type="text" v-model="editData.fullName" placeholder="Edit Full Name"/>
                    </div>

                    <div class="space">
                        <Input type="text" v-model="editData.email" placeholder="Email"/>
                    </div>

                    <div class="space">
                        <Input type="password" v-model="editData.password" placeholder="Password"/>
                    </div>

                    <div class="space">
                        <Select v-model="editData.userType" placeholder="Select admin type...">
                            <Option  value="Admin">Admin</Option>
                            <Option  value="Editor">Editor</Option>
                        </Select>
                    </div>


                    <div slot="footer">
                        <Button type="default" @click="editModal=false">Close</Button>
                        <Button
                            type="info"
                            @click="editAdmin"
                            :disabled="isAdding"
                            :loading="isAdding"
                        >{{ isAdding ? 'Editing...' : 'Edit admin user' }}
                        </Button>
                    </div>
                </Modal>

                <!--Delete tag modal-->
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
                fullName: '',
                email: '',
                password: '',
                userType: ''
            },
            //Don't display modal by default
            addModal: false,
            editModal: false,
            isAdding: false,
            users: [],
            editData: {
                fullName: '',
                email: '',
                password: '',
                userType: ''
            },
            index: -1,
            showDeleteModal: false,
            isDeleting: false,
            deleteItem: {},
            deletingIndex: -1
        }
    },
    methods: {
        async addAdmin() {
            if (this.data.fullName.trim() === '') return this.e('A name is required');
            if (this.data.email.trim() === '') return this.e('An email is required');
            if (this.data.password.trim() === '') return this.e('A password is required');
            if (this.data.userType.trim() === '') return this.e('An admin type is required');

            //axios call from common.js
            const res = await this.callApi('post', '/app/create_user', this.data);
            if (res.status === 201) {
                this.users.unshift(res.data)
                this.s('Admin user has been added successfully!');
                //Close modal when tag is added
                this.addModal = false;
                this.data.fullName = '';
                this.data.email = '';
                this.data.password = '';
                this.data.userType = '';
            } else {
                if (res.status === 422) {
                    for (let i in res.data.errors) {
                        this.e(res.data.errors[i][0]);
                    }
                } else {
                    this.swr();
                }
            }
        },
        async editAdmin() {
            if (this.editData.fullName.trim() === '') return this.e('A name is required');
            if (this.editData.email.trim() === '') return this.e('An email is required');
            if (this.editData.userType.trim() === '') return this.e('An admin type is required');

            const res = await this.callApi('post', 'app/edit_user', this.editData);
            if (res.status === 200) {
                //Go to tag index and replace with edited tag name
                this.users[this.index] = this.editData;
                this.s('Admin user has been edited successfully');
                this.editModal = false;
            } else {
                if (res.status === 422) {
                    for (let i in res.data.errors) {
                        this.e(res.data.errors[i][0]);
                    }
                } else {
                    this.swr();
                }
            }
        },
        showEditModal(user, index) {
            //Assign data to be usable in editData v-model
            let obj = {
                id: user.id,
                fullName: user.fullName,
                email: user.email,
                userType: user.userType,
            }
            this.editData = obj;
            this.editModal = true;
            this.index = index;
        },
        showDeletingModal(tag, i) {
            const deleteModalObj = {
                showDeleteModal: true,
                deleteUrl: 'app/delete_tag',
                data: tag,
                deletingIndex: i,
                isDeleted: false
            };
            this.$store.commit('setDeletingModalObj', deleteModalObj);
            console.log('delete method called')
        },
        format_date(value) {
            if (value) {
                return moment(String(value)).format('MMM DD YYYY, h:mm:ss a');
            }
        }
    },
    async created() {
        const res = await this.callApi('get', 'app/get_users');
        if (res.status === 200) {
            //Fill the users[] in data
            this.users = res.data;
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
                this.tags.splice(obj.deletingIndex, 1);
            }
        }
    }
}
</script>



