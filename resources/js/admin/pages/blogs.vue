<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <p class="_title0">Blogs
                        <Button
                            @click="$router.push('/createBlog')"
                        >
                            <Icon type="md-add">Default</Icon>
                            Create Blog
                        </Button>
                    </p>

                    <div class="_overflow _table_div">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Categories</th>
                                <th>Tags</th>
                                <th>Views</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                            <!-- TABLE TITLE -->

                            <!-- Display Blogs From DB -->
                            <tr v-for="(blog, i) in blogs" :key="i" v-if="blogs.length">
                                <td>{{blog.id}}</td>
                                <td class="_table_name">{{blog.title}}</td>
                                <td>
                                    <span v-for="(cat, i) in blog.category" v-if="blog.category.length">
                                        <Tag type="border">{{cat.categoryName}}</Tag>
                                    </span>
                                </td>
                                <td>
                                    <span v-for="(t, i) in blog.tag" v-if="blog.tag.length">
                                        <Tag type="border">{{t.tagname}}</Tag>
                                    </span>
                                </td>
                                <td class="_table_name">{{blog.views}}</td>
                                <td>{{format_date(blog.created_at)}}</td>
                                <td>
                                    <Button
                                        type="info"
                                        size="small"
                                    >View
                                    </Button>
                                    <Button
                                        type="info"
                                        size="small"
                                        @click="showEditModal(blog, i)"
                                        v-if="isUpdatePermitted"
                                    >Edit
                                    </Button>
                                    <Button
                                        type="error"
                                        size="small"
                                        @click="showDeletingModal(blog, i)"
                                        :loading="blog.isDeleting"
                                        v-if="isDeletePermitted"
                                    >Delete
                                    </Button>
                                </td>
                            </tr>
                            <!-- ITEMS -->
                        </table>
                    </div>
                </div>
                <!--Delete blog post-->
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
            isAdding: false,
            blogs: [],
            tags: [],
            index: -1,
            showDeleteModal: false,
            isDeleting: false,
            deleteItem: {},
            deletingIndex: -1,
        }
    },
    methods: {
        showDeletingModal(blog, i) {
            this.deletingIndex = i;
            const deleteModalObj = {
                showDeleteModal: true,
                deleteUrl: 'app/delete_blog',
                data: {id: blog.id},
                deletingIndex: i,
                isDeleted: false,
                msg: 'Are you sure you want to delete this blog',
                successMsg: 'Blog deleted successfully'
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
        const res = await this.callApi('get', 'app/blog_data');
        if (res.status === 200) {
            //Fill the blogs[] in data
            this.blogs = res.data;
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
                this.blogs.splice(this.deletingIndex, 1);
            }
        }
    }
}
</script>



