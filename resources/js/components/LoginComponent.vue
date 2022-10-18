<template>
    <div>
        <div class="login-form">
            <div class="card">
                <div class="card-header">
                    <strong><center>Finance Manager | Login</center></strong>
                </div>
                <div class="card-body">
                    <div id="message">

                    </div>
                    <form action="" class="form-horizontal" @submit="login">
                        <div class="form-group">
                            <input type="text" class="form-control" v-model="form.username" placeholder="Username" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <input type="password" class="form-control" v-model="form.password" placeholder="Password" required>
                        </div>
                        <br>
                        <div class="float-right">
                            <button type="button" class="btn btn-secondary">Cancel</button>
                            &nbsp;
                            <button type="submit" class="btn btn-success">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default{
    data(){
        return {
            form: {
                username: null,
                password: null
            }
        }
    },
    methods: {
        login(e){
            e.preventDefault();

            const vm = this;
            axios.post('/login', this.form)
                .then(function(response){
                    if(response.data.success){
                        window.location.href = "/users";
                    }else{
                        vm.form.password = null;

                        $("#message").html("").append('<div class="alert alert-danger" role="alert">'+response.data.message+'</div>');
                    }
                })
                .catch(function (error){
                    vm.form.password = null;
                    showError(error);
                });
        }
    }
}
</script>
