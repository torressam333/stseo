<template>
    <div>
        <div class="container">
            <div class="login_form _box_shadow _border_radious _mar_b30 _p20">
                <div class="login_header">
                    <h1>Login to the dashboard</h1>
                </div>
                <div class="space">
                    <Input type="email" v-model="data.email" placeholder="Email Address"/>
                </div>
                <div class="space">
                    <Input type="password" v-model="data.password" placeholder="Enter Password"/>
                </div>
                <div class="login_footer">
                    <Button type="primary" @click="login" :disabled="isLogging" :loading="isLogging">
                        {{isLogging ? 'Logging in...' : 'Login'}}
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            data: {
                email: '',
                password: ''
            },
            isLogging: false,
        }
    },
    methods: {
        async login() {
            //Validation
            if (this.data.email.trim() === '') return this.e("Email is required");
            if (this.data.password.trim() === '') return this.e("Password is required");
            if (this.data.password.length < 6) return this.e("Password must be at least 6 characters long");
            this.isLogging = true;
            const res = await this.callApi('post', 'app/admin_login', this.data);

            if (res.status === 200) {
                this.s(res.data.msg);
            } else {
                if (res.status === 401) {
                    this.e(res.data.msg);
                } else if (res.status === 422) {
                    for (let i in res.data.errors) {
                        this.e(res.data.errors[i][0])
                    }
                } else {
                    this.swr();
                }
            }
            this.isLogging = false;
        }
    }
}
</script>


<style scoped>
.login_form {
    border-radius: 3px;
    margin: 0 auto;
    margin-top: 220px;
    max-width: 35%;
}

.login_header {
    text-align: center;
    margin-bottom: 25px;
}

.login_footer {
    text-align: center;
}
</style>
