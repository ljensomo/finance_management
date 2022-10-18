<template>
    <div>
        <div class="register-form">
            <div class="card">
                <div class="card-header">
                    <strong>Register User</strong>
                </div>
                <div class="card-body">
                    <div id="message"></div>
                    <div class="row">
                        <form action="" class="form-horizontal">
                            <div class="mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" name="first_name" v-model="form.first_name" class="form-control" placeholder="First Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" name="last_name" v-model="form.last_name" class="form-control" placeholder="Last Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" v-model="form.email" class="form-control" placeholder="Email" required>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" v-model="form.username" class="form-control" placeholder="Username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" v-model="form.password" class="form-control" placeholder="Password" required>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" v-model="form.password_confirmation" placeholder="Confirm Password" class="form-control" required>
                            </div>
                            <div class="float-right">
                                <a href="/users" class="btn btn-secondary">Back to Users</a>
                                &nbsp;
                                <button type="button" class="btn btn-success" @click="register()">Register User</button>
                            </div>
                        </form>
                    </div>
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
                first_name: null,
                last_name: null,
                email: null,
                username: null,
                password: null,
                password_confirmation: null
            }
        }
    },
    methods: {
        register(){
            const vm = this;
            axios.post('/users', this.form)
                .then(function(response){
                    if(response.data.success){
                        vm.clearForm();
                        $("#message").html("").append('<div class="alert alert-success" role="alert">'+response.data.message+'</div>').fadeIn();

                        return;
                    }

                    $("#message").html("").append('<div class="alert alert-danger" role="alert">'+response.data.message+'</div>').fadeIn();
                })
                .catch(function (error){
                    console.log(error);
                    let response = error.response;
                    let error_message = "Something went wrong!";
                    let errors = {};

                    if(response){
                        error_message = "<b>"+response.data.message+"</b>";
                        errors = response.data.errors;
                    }

                    for (var error_subject in errors){
                        for(var error in errors[error_subject]){
                            error_message += "<br>"+errors[error_subject][error];
                        }
                    }

                    $("#message").html("").append('<div class="alert alert-danger" role="alert">'+error_message+'</div>').fadeIn();
                });
        },
        clearForm(){
            const vm = this;
            vm.form.first_name = null;
            vm.form.last_name = null;
            vm.form.email = null;
            vm.form.username = null;
            vm.form.password = null;
            vm.form.password_confirmation = null;
        }
    }

}
</script>
