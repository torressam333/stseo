<template>
    <div>
        <h1>Number count {{views}}</h1>
        <button @click="updateCounter(1)">Increase</button>
        <button @click="updateCounter(-1)">Decrease</button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            views: 0
        }
    },
    methods: {
        updateCounter(number) {
            this.views += number;
        }
    },
    created() {
        //Call server to get views of this blog post...
        this.views = 100;
    }
}
</script>
