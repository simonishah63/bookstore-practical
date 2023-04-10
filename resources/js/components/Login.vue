<template>
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12 col-md-6 offset-md-3">
                <div class="card shadow sm">
                    <div class="card-body">
                        <h1 class="text-center">Login</h1>
                        <hr/>
                        <form action="javascript:void(0)" class="row" method="post">
                            <div class="col-12" v-if="validationErrors && !validationErrors.errors">
                                <div class="alert alert-danger">
                                    {{validationErrors.message}}
                                </div>
                            </div>
                            <div class="col-12" v-if="validationErrors && validationErrors.errors">
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <li v-for="(value, key) in validationErrors.errors" :key="key">{{ value[0] }}</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group col-12">
                                <label for="email" class="font-weight-bold">Email</label>
                                <input type="text" v-model="email" name="email" id="email" class="form-control">
                            </div>
                            <div class="form-group col-12 my-2">
                                <label for="password" class="font-weight-bold">Password</label>
                                <input type="password" v-model="password" name="password" id="password" class="form-control">
                            </div>
                            <div class="col-12 mb-2">
                                <button type="submit" :disabled="processing" @click="login" class="btn btn-primary btn-block">
                                    {{ processing ? "Please wait" : "Login" }}
                                </button>
                            </div>
                            <div class="col-12 text-center">
                                <label>Don't have an account? <router-link :to="{name:'register'}">Register Now!</router-link></label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "login",
    data(){
        return {
            email: '',
            password: '',
            validationErrors: null,
            processing: false
        }
    },
    methods: {
        
        async login(){
            this.processing = true
            this.validationErrors = null
            try {
                await this.$store.dispatch('login' , {'email' : this.email , 'password' : this.password})
                this.$router.push({name: 'listBook'})
            }
            catch (e){
                this.validationErrors = e.data
            };
            this.processing = false ;
            
        }
    }
}
</script>