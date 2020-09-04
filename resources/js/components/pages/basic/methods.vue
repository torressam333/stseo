<template>
    <div>
        <h1>Number count {{views}}</h1>
        <button @click="updateCounter(1)">Increase</button>
        <button @click="updateCounter(-1)">Decrease</button>
        <br><br>
        <div v-for="(blog, i) in blogs" :key="i" v-if="blogs.length">
            <h1>{{blog.title}}</h1>
            <p>{{blog.post}}</p>
        </div>
        <br><br>
        <div>
            <h1 v-if="showItem">Show this item if "showItem is true"</h1>
            <h1 v-else>Show this item if "showItem is false"</h1>
        </div>

        <button @click="showItem = !showItem">Change Show Item</button>

    </div>
</template>

<script>
export default {
    data() {
        return {
            views: 0,
            blogs: [],
            showItem: false
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
        //Sample data
        let posts = [
                {title: "Blog Title 1", post: "Blog Post 1", id: 1},
                {title: "Blog Title 2", post: "Blog Post 2", id: 2},
                {title: "Blog Title 3", post: "Blog Post 3", id: 3},
                {title: "Blog Title 4", post: "Blog Post 4", id: 4},];

        this.blogs = posts;
    },
}
</script>
