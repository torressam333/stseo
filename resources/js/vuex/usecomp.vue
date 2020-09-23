<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <h1>Demonstration of how other components react to vue store</h1>
                <h3>Parent components Current state of counter is: {{counter}}</h3>
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
import {mapActions, mapGetters} from 'vuex';

export default {
    data() {
        return {

        }
    },
    computed: {
        ...mapGetters({
            //If you want to re-name the getter from your store
            'counter': 'getCounter'
        })
    },
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
            //Dispatch an action
            this.$store.dispatch('changeCounterAction', 1)
        }
    }
}
</script>

