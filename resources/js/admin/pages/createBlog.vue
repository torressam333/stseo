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

                    <div class="_overflow _table_div blog_editor">
                        <editor
                            ref="editor"
                            autofocus
                            holder-id="codex-editor"
                            save-button-id="save-button"
                            :init-data="initData"
                            @save="onSave"
                        />
                    </div>

                    <div class="_input_field">
                        <Input type="text" placeholder="Your blog post title"/>
                    </div>
                    <div class="_input_field">
                        <Button @click="save">Save Data</Button>
                    </div>

                    </div>
                </div>


            </div>
        </div>
</template>

<script>

export default {
    data() {
        return {
            config: {
                image: {
                    field: 'image',
                    types: 'image/*',
                },
            },
            initData: null,
            data: {

            }
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
        onSave(response) {
            console.log(response);
        },
        async save() {
           this.$refs.editor.save();
        },
        format_date(value) {
            if (value) {
                return moment(String(value)).format('MMM DD YYYY, h:mm:ss a');
            }
        }
    }
}
</script>

<style>
.blog_editor {
    width: 750px;
    margin-left: 100px;
    padding: 4px 7px;
    font-size: 16px;
    border: 1px solid #dcdee2;
    border-radius: 5px;
    color: #1b4b72;
    background-color: #fff;
    background-image: none;
    z-index: -1;
}
.blog_editor:hover{
    border: 1px solid #57a3f3;
}

._input_field{
    margin: 20px 0 0 100px;
    width: 750px;
}
</style>


