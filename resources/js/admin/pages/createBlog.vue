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

                    <div class="_input_field">
                        <Input type="text" v-model="data.title" placeholder="Blog Title"/>
                    </div>

                    <div class="_overflow _table_div blog_editor">
                        <editor
                            ref="editor"
                            autofocus
                            holder-id="codex-editor"
                            save-button-id="save-button"
                            :init-data="initData"
                            @save="onSave"
                            :config="config"
                        />
                    </div>

                    <div class="_input_field">
                        <Input type="textarea" v-model="data.postExcerpt" :rows="4" placeholder="Post excerpt"/>
                    </div>

                    <div class="_input_field">
                        <Select filterable multiple placeholder="Category" v-model="data.category_id">
                            <Option v-for="(cat, index) in category"
                                    :value="cat.id"
                                    :key="index">
                                {{cat.categoryName}}
                            </Option>
                        </Select>
                    </div>

                    <div class="_input_field">
                        <Select filterable multiple placeholder="Tags" v-model="data.tag_id">
                            <Option v-for="(tag, index) in tag"
                                    :value="tag.id"
                                    :key="index">
                                {{tag.tagname}}
                            </Option>
                        </Select>
                    </div>

                    <div class="_input_field">
                        <Input type="textarea" v-model="data.metaDescription" :rows="4" placeholder="Meta Description"/>
                    </div>

                    <div class="_input_field">
                        <Button @click="save" :loading="isCreating" :disabled="isCreating">
                            {{isCreating ? 'Creating...' : 'Create Blog'}}
                        </Button>
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
            config: {},
            initData: null,
            data: {
                title: '',
                post: '',
                postExcerpt: '',
                metaDescription: '',
                category_id: [],
                tag_id: [],
                jsonData: null
            },
            articleHTML: '',
            category: [],
            tag: [],
            isCreating: false,
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
        async onSave(response) {
            let data = response;
            await this.outputHtml(data.blocks);
            this.data.post = this.articleHTML;
            this.data.jsonData = JSON.stringify(data);
            this.isCreating = true;

            const res = await this.callApi('post', 'app/create_blog', this.data);
            if (res.status === 200) {
                this.s('Blog successfully created')
                //reload current blog page
               /* setTimeout(() =>
                {
                    window.location.reload();
                },3000);*/

            }else if (res.status === 422) {
                if (res.data.errors.title) {
                    this.e(res.data.errors.title[0]);
                }
                if (res.data.errors.post) {
                    this.e(res.data.errors.post[0]);
                }
            } else {
                this.swr();
            }
            this.isCreating = false;

        },
        async save() {
            this.$refs.editor.save();
        },
        outputHtml(articleObj) {
            articleObj.map(obj => {
                switch (obj.type) {
                    case 'paragraph':
                        this.articleHTML += this.makeParagraph(obj);
                        break;
                    case 'image':
                        this.articleHTML += this.makeImage(obj);
                        break;
                    case 'header':
                        this.articleHTML += this.makeHeader(obj);
                        break;
                    case 'raw':
                        this.articleHTML += `<div class="ce-block">
					<div class="ce-block__content">
					<div class="ce-code">
						<code>${obj.data.html}</code>
					</div>
					</div>
				</div>\n`;
                        break;
                    case 'code':
                        this.articleHTML += this.makeCode(obj);
                        break;
                    case 'list':
                        this.articleHTML += this.makeList(obj)
                        break;
                    case "quote":
                        this.articleHTML += this.makeQuote(obj)
                        break;
                    case "warning":
                        this.articleHTML += this.makeWarning(obj)
                        break;
                    case "checklist":
                        this.articleHTML += this.makeChecklist(obj)
                        break;
                    case "embed":
                        this.articleHTML += this.makeEmbed(obj)
                        break;
                    case 'delimeter':
                        this.articleHTML += this.makeDelimeter(obj);
                        break;
                    default:
                        return '';
                }
            });
        },
        format_date(value) {
            if (value) {
                return moment(String(value)).format('MMM DD YYYY, h:mm:ss a');
            }
        }
    }, //End methods
    async created() {
        const [cat, tag] = await Promise.all([
            this.callApi('get', 'app/get_categories'),
            this.callApi('get', 'app/get_tags'),
        ]);

        if (cat.status === 200) {
            this.category = cat.data
            this.tag = tag.data
        } else {
            this.swr()
        }
    },
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

.blog_editor:hover {
    border: 1px solid #57a3f3;
}

._input_field {
    margin: 20px 0 20px 100px;
    width: 750px;
}

._select_field {
    margin: 20px 0 10px 100px;
    width: 750px;
}
</style>


