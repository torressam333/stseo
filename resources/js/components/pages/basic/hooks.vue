<template>
    <div>
        <h1 ref="myref">Hooks Page</h1>
        <p>My name is: {{name}}</p>
    </div>
</template>

<script>
export default {
    data() {
        return {
            name: 'Samuel'
        }
    },
    /***Lifecycle Hooks****/
    //before create
    beforeCreate() {
        console.log('created first')
    },
    //Created
    created() {
        //Call initial data
        console.log("From created method: " + this.name)
    },
    //Mounted
    mounted() {
        console.log(this.$refs.myref);
    },
}
</script>
