<template>
    <div>
        <div class="register-form">
            <div class="card">
                <div class="card-header">
                    <strong>Update User</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <form action="" class="form-horizontal" @submit="updateUser">
                            <input type="hidden" name="id" v-model="form.id" class="form-control">
                            <div class="mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" name="first_name" v-model="form.first_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" name="last_name" v-model="form.last_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" v-model="form.email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" v-model="form.username" class="form-control" disabled>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="/users" class="btn btn-secondary">Back to Users</a>
                                &nbsp;
                                <button type="submit" class="btn btn-success">Save Changes</button>
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
    props:['user'],
    data() {
        return {
            form: {
                id: this.user.id,
                first_name: this.user.first_name,
                last_name: this.user.last_name,
                email: this.user.email,
                username: this.user.username
            }
        }
    },
    methods: {
        updateUser(e){
            e.preventDefault();

            const vm = this;
            axios.post("/user/update", this.form)
                .then(function(response){
                    showResponse(response.data);
                    location.reload();
                }).catch(function(error){
                    showError(error);
                });
        }
    }
}
</script>
