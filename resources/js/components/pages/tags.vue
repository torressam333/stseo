<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                    <p class="_title0">Tags
                    <Button @click="addModal=true"><Icon type="md-add">Default</Icon>Add Tag</Button>
                    </p>

                    <div class="_overflow _table_div">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>ID</th>
                                <th>Tag name</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                            <!-- TABLE TITLE -->

                            <!-- Display Tags From DB -->
                            <tr v-for="(tag, i) in tags" :key="i" v-if="tags.length">
                                <td>{{tag.id}}</td>
                                <td class="_table_name">{{tag.tagname}}</td>
                                <td>{{tag.created_at}}</td>
                                <td>
                                    <Button type="info" size="small">Edit</Button>
                                    <Button type="error" size="small">Delete</Button>
                                </td>
                            </tr>
                            <!-- ITEMS -->
                        </table>
                    </div>
                </div>

                <!--Add tag modal-->
                <Modal
                    v-model="addModal"
                    title="Common Modal dialog box title"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Input v-model="data.tagName" placeholder="Add tag name"/>

                    <div slot="footer">
                        <Button type="default" @click="addModal=false">Close</Button>
                        <Button
                            type="success"
                            @click="addTag"
                            :disabled="isAdding"
                            :loading="isAdding"
                        >{{ isAdding ? 'Adding...' : 'Add tag' }}</Button>
                    </div>
                </Modal>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
      return {
          data: {
              tagName: ''
          },
          //Don't display modal by default
          addModal: false,
          isAdding: false,
          tags: []
      }
    },
    methods: {
        async addTag() {
            if(this.data.tagName.trim() === '') return this.e('A tag name is required');
            //axios call from common.js
            const res = await this.callApi('post', '/app/create_tag', this.data);
            if (res.status === 201) {
               this.s('Tag has been added successfully!');
               //Close modal when tag is added
               this.addModal = false;
            }else{
                this.swr()
            }
        }
    },
    async created() {
        const res = await this.callApi('get', 'app/get_tags');
        if (res.status === 200) {
            //Fill the tags[] in data
            this.tags = res.data;
        }else{
            this.swr();
        }
    }
}
</script>



