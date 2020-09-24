<template>
    <div>
        <!--Delete modal-->
        <Modal :value="getDeleteModalObj.showDeleteModal"
               :mask-closable="false"
               :closable="false"
               width="360">
            <p slot="header" style="color:#e70000;text-align:center">
                <Icon type="ios-information-circle"></Icon>
                <span>Delete confirmation</span>
            </p>
            <div style="text-align:center">
                <p>Are you sure you want to delete this Tag?</p>
            </div>
            <div slot="footer">
                <Button type="error" size="large"
                        :loading="isDeleting"
                        :disabled="isDeleting"
                        @click="deleteTag"
                >Delete
                </Button>
                <Button type="default" size="large" @click="closeModal">Close</Button>
            </div>
        </Modal>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';

export default {
    data() {
        return{
            isDeleting: false
        }
    },
    computed: {
        ...mapGetters(['getDeleteModalObj'])
    },
    methods: {
        async deleteTag() {
            //When deletion is in process
            this.isDeleting = true;
            const res = await this.callApi('post', this.getDeleteModalObj.deleteUrl, this.getDeleteModalObj.data);
            if (res.status === 200) {
                this.s('Tag has been deleted successfully')
                this.$store.commit('setDeleteModal', true);
            } else {
                this.swr();
                this.$store.commit('setDeleteModal', false);
            }
            this.isDeleting = false;
        },
        closeModal() {
            this.$store.commit('setDeleteModal', false);
        }
    }
}
</script>
