<template>
    <div>
        <div class="card">
            <div class="card-header">
                <strong>Users</strong>
            </div>
            <div class="card-body">
                <a href="/register-user" class="btn btn-primary">Register User</a><hr>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Username</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    methods:{
        changeStatus(id, status){
            axios.post('/user/status/change', {user_id: id, status: status})
                .then(function(response){
                    showResponse(response.data);
                    if(response.data.success){
                        refreshDataTable("#datatable");
                    }
                })
                .catch(function(error){
                    showError(error);
                });
        }
    },
    mounted(){
        const vm = this;
        $("#datatable").DataTable({
            responsive:true,
            processing:true,
            serverSide:true,
            ajax:"/user/list",
            columns:[
                {data:"name"},
                {data:"email"},
                {data:"username"},
                {data:"status", className:"text-center"},
                {data:"action", className:"text-center"}
            ],
        });

        $(document).on("click", ".btn-change", function(){
            let id = $(this).attr("value");
            let status = $(this).attr("status");
            vm.changeStatus(id, status == 1 ? 0 : 1);
        });
    }
}
</script>
