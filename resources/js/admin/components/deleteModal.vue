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
                <p>{{getDeleteModalObj.msg}}</p>
            </div>
            <div slot="footer">
                <Button type="error" size="large"
                        :loading="isDeleting"
                        :disabled="isDeleting"
                        @click="deleteEntity"
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
        async deleteEntity() {
            //When deletion is in process
            this.isDeleting = true;
            const res = await this.callApi('post', this.getDeleteModalObj.deleteUrl, this.getDeleteModalObj.data);
            if (res.status === 200) {
                this.s(getDeleteModalObj.successMsg);
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
