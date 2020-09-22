<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <h1>Demonstration of how other components react to vue store</h1>
                <h3>Parent components Current state of counter is: {{$store.state.counter}}</h3>
                <div>
                    <comp-a/>
                </div>
                <div>
                    <comp-b/>
                </div>
                <div>
                    <comp-c/>
                </div>

                <Button type="info" @click="changeCounter">Change the state of the counter</Button>
            </div>

        </div>
    </div>
</template>

<script>
import compA from '../vuex/compA';
import compB from '../vuex/compB';
import compC from '../vuex/compC';

export default {
    created() {
        console.log(compA)
    },
    components: {
        compA,
        compB,
        compC
    },
    methods: {
        changeCounter() {
            this.$store.commit('changeCounter', 1)
        }
    }
}
</script>

