/*Methods in this file are globally available as
* JS mixin and is used/declared as a mixin in app.js
* */
import { mapGetters } from 'vuex';

export default {
    data(){
        return {

        }
    },
    methods: {
        /*General purpose api async method, can be used
        for any resource REST call anywhere in the app*/
        async callApi(method, url, dataObj) {
            try {
                return await axios({
                    method: method,
                    url: url,
                    data: dataObj
                });
            } catch (e){
                return e.response
            }
        },
        /*Notice banner notifications from ViewUI*/
        i (desc, title='Hello') {
            this.$Notice.info({
                title: title,
                desc: desc
            });
        },
        s (desc, title='Great, success') {
            this.$Notice.success({
                title: title,
                desc: desc
            });
        },
        w (desc, title='Oops, Warning') {
            this.$Notice.warning({
                title: title,
                desc: desc
            });
        },
        e (desc, title='Whoops!') {
            this.$Notice.error({
                title: title,
                desc: desc
            });
        },
        swr (desc='Something went wrong! Please try again', title='Doh Error') {
            this.$Notice.error({
                title: title,
                desc: desc
            });
        },
        /*END Notice banner notifications from ViewUI*/
        checkUserPermission(key) {
            //If no permission then user is super admin
            if(!this.userPermission) return true;

            //Deny initial permission for all actions (CRUD)
            let isPermitted = false;

            //Loop through users permission
            for (let data of this.userPermission) {
                //If page name is equal to the data name
                if (this.$route.name === data.name) {
                    //Allow permission for the action (CRUD)
                    if (data[key]) {
                        isPermitted = true;
                        break;
                    }else{
                        //Leave permitted as false (no access)
                        break;
                    }
                }
            }
            return isPermitted; //Return if user is permitted to perform an action
        }
    },
    computed: {
        ...mapGetters({
            'userPermission': 'getUserPermission'
        }),
        isReadPermitted() {
            return this.checkUserPermission('read');
        },
        isWritePermitted() {
            return this.checkUserPermission('write');
        },
        isUpdatePermitted() {
            return this.checkUserPermission('update');
        },
        isDeletePermitted() {
            return this.checkUserPermission('delete');
        },
    }
}
