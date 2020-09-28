<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <p class="_title0">Category
                        <Button @click="addModal=true">
                            <Icon type="md-add">Default</Icon>
                            Add Category
                        </Button>
                    </p>

                    <div class="_overflow _table_div">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>ID</th>
                                <th>Icon image</th>
                                <th>Category name</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                            <!-- TABLE TITLE -->

                            <!-- Display Tags From DB -->
                            <tr v-for="(category, i) in categories" :key="i" v-if="categories.length">
                                <td>{{ category.id }}</td>
                                <td class="table_image">
                                    <img :src="category.iconImage" alt="image thumbnail">
                                </td>
                                <td class="_table_name">{{ category.categoryName }}</td>
                                <td>{{ format_date(category.created_at) }}</td>
                                <td>
                                    <Button type="info" size="small" @click="showEditModal(category, i)">Edit</Button>
                                    <Button type="error" size="small"
                                            @click="showDeletingModal(category, i)"
                                            :loading="category.isDeleting"
                                    >Delete
                                    </Button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!--Add tag modal-->
                <Modal
                    v-model="addModal"
                    title="Add Category"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Input v-model="data.categoryName" placeholder="Add category"/>
                    <div class="space"></div>
                    <Upload
                        ref="uploads"
                        type="drag"
                        :headers="{'x-csrf-token':token, 'X-Requested-With':'XMLHttpRequest'}"
                        :on-success="handleSuccess"
                        :on-error="handleError"
                        :max-size="2048"
                        :on-exceeded-size="handleMaxSize"
                        :format="['jpg','jpeg','png','bmp','docx','txt','xlsx','xlsm','pdf','doc']"
                        :on-format-error="handleFormatError"
                        action="/app/upload">
                        <div style="padding: 20px 0">
                            <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                            <p>Click or drag files to upload</p>
                        </div>
                    </Upload>
                    <!--Show thumbnail for any image files-->
                    <div class="demo-upload-list" v-if="data.iconImage">
                        <img :src="`${data.iconImage}`">
                        <div class="demo-upload-list-cover">
                            <Icon type="ios-trash-outline" @click="deleteImage"></Icon>
                        </div>
                    </div>

                    <div slot="footer">
                        <Button type="default" @click="addModal=false">Close</Button>
                        <Button
                            type="success"
                            @click="addCategory"
                            :disabled="isAdding"
                            :loading="isAdding"
                        >{{ isAdding ? 'Adding...' : 'Add Category' }}
                        </Button>
                    </div>
                </Modal>

                <!--Edit tag modal-->
                <Modal
                    v-model="editModal"
                    title="Edit category"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Input v-model="editData.categoryName" placeholder="Edit tag name"/>
                    <div class="space"></div>
                    <Upload v-show="isIconImageNew"
                        ref="editaDataUploads"
                        type="drag"
                        :headers="{'x-csrf-token':token, 'X-Requested-With':'XMLHttpRequest'}"
                        :on-success="handleSuccess"
                        :on-error="handleError"
                        :max-size="2048"
                        :on-exceeded-size="handleMaxSize"
                        :format="['jpg','jpeg','png','bmp','docx','txt','xlsx','xlsm','pdf','doc']"
                        :on-format-error="handleFormatError"
                        action="/app/upload">
                        <div style="padding: 20px 0">
                            <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                            <p>Click or drag files here to upload</p>
                        </div>
                    </Upload>
                    <!--Show thumbnail for any image files-->
                    <div class="demo-upload-list" v-if="editData.iconImage">
                        <img :src="`${editData.iconImage}`">
                        <div class="demo-upload-list-cover">
                            <Icon type="ios-trash-outline" @click="deleteImage(false)"></Icon>
                        </div>
                    </div>

                    <div slot="footer">
                        <Button type="default" @click="closeEditModal">Close</Button>
                        <Button
                            type="info"
                            @click="editCategory"
                            :disabled="isAdding"
                            :loading="isAdding"
                        >{{ isAdding ? 'Editing...' : 'Edit category' }}
                        </Button>
                    </div>
                </Modal>

                <!--Delete modal component-->
                <deleteModal></deleteModal>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import DeleteModal from "../components/deleteModal";
import {mapGetters} from "vuex";

export default {
    components: {DeleteModal},
    data() {
        return {
            data: {
                iconImage: '',
                categoryName: ''
            },
            //Don't display modal by default
            addModal: false,
            editModal: false,
            isAdding: false,
            categories: [],
            editData: {
                iconImage : '',
                categoryName: ''
            },
            index: -1,
            showDeleteModal: false,
            isDeleting: false,
            deleteItem: {},
            deletingIndex: -1,
            token: '',
            isIconImageNew : false,
            isEditingItem : false
        }
    },
    methods: {
        async addCategory() {
            if (this.data.categoryName.trim() === '') return this.e('A category name is required');
            if (this.data.iconImage.trim() === '') return this.e('Icon image is required');
            this.data.iconImage = `${this.data.iconImage}`;
            //axios call from common.js
            const res = await this.callApi('post', '/app/create_category', this.data);
            if (res.status === 201) {
                this.categories.unshift(res.data)
                this.s('Category has been added successfully!');
                //Close modal when tag is added
                this.addModal = false;
                this.data.categoryName = '';
                this.data.iconImage = '';
            } else {
                if (res.status === 422) {
                    this.unprocessableEntityError();
                } else {
                    this.swr();
                }
            }
        },
        async editCategory() {
            if (this.editData.categoryName.trim() === '') return this.e('Category name is required')
            if (this.editData.iconImage.trim() === '') return this.e('Icon image is required');
            const res = await this.callApi('post', 'app/edit_category', this.editData);
            if (res.status === 200) {
                //Go to category index and replace with edited tag name
                this.categories[this.index].categoryName = this.editData.categoryName;
                this.s('Category has been edited successfully!');
                this.editModal = false;
            } else {
                if (res.status === 422) {
                    this.unprocessableEntityError();
                } else {
                    this.swr();
                }
            }
        },
        showEditModal(category, index) {
            //Assign data to be usable in editData v-model
            this.editData = category;
            this.editModal = true;
            this.index = index;
            this.isEditingItem = true;
        },
        showDeletingModal(category, i) {
            const deleteModalObj = {
                showDeleteModal: true,
                deleteUrl: 'app/delete_category',
                data: category,
                deletingIndex: i,
                isDeleted: false
            };
            this.$store.commit('setDeletingModalObj', deleteModalObj);
        },
        format_date(value) {
            if (value) {
                return moment(String(value)).format('MMM DD YYYY, h:mm:ss a');
            }
        },
        handleSuccess(res) {
            res = `/uploads/${res}`;

            if (this.isEditingItem) {
                return this.editData.iconImage = res;
            }
            //When file is uploaded
            this.data.iconImage = res;
        },
        handleError(res, file) {
            this.$Notice.warning({
                title: 'Incorrect file format',
                desc: `${file.errors.file.length ? file.errors.file[0] : 'Something went wrong!'}`
            });
        },
        handleFormatError(file) {
            this.$Notice.warning({
                title: 'The file format is incorrect',
                desc: 'File format of ' + file.name + ' is incorrect, please select an acceptable file format.'
            });
        },
        handleMaxSize(file) {
            this.$Notice.warning({
                title: 'Exceeding file size limit',
                desc: 'File  ' + file.name + ' is too large, no more than 2M.'
            });
        },
        async deleteImage(isAdd = true) {
            let image;
            if (!isAdd) {
                //For editing
                this.isIconImageNew = true;
                image = this.editData.iconImage;
                this.editData.iconImage = '';
                this.$refs.editaDataUploads.clearFiles();
            }else{
                image = this.data.iconImage;
                this.data.iconImage = '';
                this.$refs.uploads.clearFiles();
            }

            const res = await this.callApi('post', 'app/delete_image', {
                imageName: image

            });

            //If not deleted successfully
            if (res.status !== 200) {
                this.data.iconImage = image;
                this.i('The image was not able to be deleted');
            }
        },
        closeEditModal() {
            this.isEditingItem = false;
            this.editModal = false;
        },
        unprocessableEntityError() {
            if (res.data.errors.categoryName) {
                this.i(res.data.errors.categoryName[0]);
            }
            if (res.data.errors.iconImage) {
                this.i(res.data.errors.iconImage[0]);
            }
        }
    },
    async created() {
        //Assign csrf token
        this.token = window.Laravel.csrfToken;

        const res = await this.callApi('get', 'app/get_categories');
        if (res.status === 200) {
            //Fill the categories[] in data attribute
            this.categories = res.data;
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
                this.categories.splice(obj.deletingIndex, 1);
            }
        }
    }
}
</script>
