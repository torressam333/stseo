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
                                <th>Category name</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                            <!-- TABLE TITLE -->

                            <!-- Display Tags From DB -->
                            <tr v-for="(tag, i) in tags" :key="i" v-if="tags.length">
                                <td>{{ tag.id }}</td>
                                <td class="_table_name">{{ tag.tagName }}</td>
                                <td>{{ format_date(tag.created_at) }}</td>
                                <td>
                                    <Button type="info" size="small" @click="showEditModal(tag, i)">Edit</Button>
                                    <Button type="error" size="small"
                                            @click="showDeletingModal(tag, i)"
                                            :loading="tag.isDeleting"
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
                    title="Add Category"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Input v-model="data.tagName" placeholder="Add category"/>
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
                            <p>Click or drag files here to upload</p>
                        </div>
                    </Upload>
                    <!--Show thumbnail for any image files-->
                    <div class="demo-upload-list" v-if="data.iconImage">
                        <img :src="`/uploads/${data.iconImage}`">
                        <div class="demo-upload-list-cover">
                            <Icon type="ios-trash-outline" @click="deleteImage"></Icon>
                        </div>
                    </div>

                    <div slot="footer">
                        <Button type="default" @click="addModal=false">Close</Button>
                        <Button
                            type="success"
                            @click="addTag"
                            :disabled="isAdding"
                            :loading="isAdding"
                        >{{ isAdding ? 'Adding...' : 'Add tag' }}
                        </Button>
                    </div>
                </Modal>

                <!--Edit tag modal-->
                <Modal
                    v-model="editModal"
                    title="Edit tag"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Input v-model="editData.tagName" placeholder="Edit tag name"/>

                    <div slot="footer">
                        <Button type="default" @click="editModal=false">Close</Button>
                        <Button
                            type="info"
                            @click="editTag"
                            :disabled="isAdding"
                            :loading="isAdding"
                        >{{ isAdding ? 'Editing...' : 'Edit tag' }}
                        </Button>
                    </div>
                </Modal>

                <!--Delete tag modal-->
                <Modal v-model="showDeleteModal" width="360">
                    <p slot="header" style="color:#e70000;text-align:center">
                        <Icon type="ios-information-circle"></Icon>
                        <span>Delete confirmation</span>
                    </p>
                    <div style="text-align:center">
                        <p>Are you sure you want to delete this category?</p>
                    </div>
                    <div slot="footer">
                        <Button type="error" size="large" long
                                :loading="isDeleting"
                                :disabled="isDeleting"
                                @click="deleteTag"
                        >Delete
                        </Button>
                    </div>
                </Modal>
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
                iconImage: '',
                categoryName: ''
            },
            //Don't display modal by default
            addModal: false,
            editModal: false,
            isAdding: false,
            tags: [],
            editData: {
                tagName: ''
            },
            index: -1,
            showDeleteModal: false,
            isDeleting: false,
            deleteItem: {},
            deletingIndex: -1,
            token: ''
        }
    },
    methods: {
        async addTag() {
            if (this.data.tagName.trim() === '') return this.e('A tag name is required');
            //axios call from common.js
            const res = await this.callApi('post', '/app/create_tag', this.data);
            if (res.status === 201) {
                this.tags.unshift(res.data)
                this.s('Tag has been added successfully!');
                //Close modal when tag is added
                this.addModal = false;
                this.data.tagName = '';
            } else {
                if (res.status === 422) {
                    if (res.data.errors.tagName) {
                        this.i(res.data.errors.tagName[0]);
                    }
                } else {
                    this.swr();
                }
            }
        },
        async editTag() {
            if (this.editData.tagName.trim() === '') return this.e('Tag name is required')
            const res = await this.callApi('post', 'app/edit_tag', this.editData);
            if (res.status === 200) {
                //Go to tag index and replace with edited tag name
                this.tags[this.index].tagName = this.editData.tagName;
                this.s('Tag has been edited successfully');
                this.editModal = false;
            } else {
                if (res.status === 422) {
                    if (res.data.errors.tagName) {
                        this.i(res.data.errors.tagName[0]);
                    }
                } else {
                    this.swr();
                }
            }
        },
        showEditModal(tag, index) {
            //Assign data to be usable in editData v-model
            let obj = {
                id: tag.id,
                tagName: tag.tagName
            }
            this.editData = obj;
            this.editModal = true;
            this.index = index;
        },
        async deleteTag() {
            //When deletion is in process
            this.isDeleting = true;
            const res = await this.callApi('post', 'app/delete_tag', this.deleteItem);
            if (res.status === 200) {
                this.tags.splice(this.deletingIndex, 1);
                this.s('Tag has been deleted successfully')
            } else {
                this.swr();
            }
            //When deletion is complete
            this.isDeleting = false;
            //Close modal when deletion is complete
            this.showDeleteModal = false;
        },
        showDeletingModal(tag, i) {
            this.deleteItem = tag;
            this.deletingIndex = i;
            this.showDeleteModal = true;
        },
        format_date(value) {
            if (value) {
                return moment(String(value)).format('MMM DD YYYY, h:mm:ss a');
            }
        },
        handleSuccess(res, file) {
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
        async deleteImage() {
            //Delete image before upload
            let image = this.data.iconImage;
            this.data.iconImage = '';
            this.$refs.uploads.clearFiles();

            const res = await this.callApi('post', 'app/delete_image', {
               imageName: image
            });

            //If not deleted successfully
            if (res.status !== 200) {
                this.data.iconImage = image;
                this.i('The image was not able to be deleted');
            }
        }
    },
    async created() {
        //Assign csrf token
        this.token = window.Laravel.csrfToken;

        const res = await this.callApi('get', 'app/get_tags');
        if (res.status === 200) {
            //Fill the tags[] in data
            this.tags = res.data;
        } else {
            this.swr();
        }
    }
}
</script>
