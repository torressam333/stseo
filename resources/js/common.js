/*Methods in this file are globally available as
* JS mixin
* */
export default {
    data(){
        return {

        }
    },
    methods: {
        /*General purpose api asycn method, can be used
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
        }
        /*END Notice banner notifications from ViewUI*/
    }
}
