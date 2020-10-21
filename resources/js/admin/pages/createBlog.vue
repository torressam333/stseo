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
                        <editor
                            ref="editor"
                            autofocus
                            holder-id="codex-editor"
                            save-button-id="save-button"
                            :init-data="initData"
                            @save="onSave"
                        />

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
            initData: null
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



