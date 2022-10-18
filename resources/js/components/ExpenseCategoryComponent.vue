<template>
    <div>
        <!-- Page Content -->
        <div class="card">
            <div class="card-header">
                <strong>Expense Category</strong>
            </div>
            <div class="card-body">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#category-modal">Add Expense Category</button><hr>
                <table class="table table-hover table-striped table-bordered" id="datatable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="category-modal" tabindex="-1" role="dialog" aria-labelledby="category-modal-label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="category-modal-label">Expense Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>    
                </div>
                <form action="" id="form-category" @submit="submit">
                    <input type="hidden" name="id" v-model="form.id" id="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" v-model="form.name" id="name" placeholder="Category Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="descirption" id="description" cols="30" rows="5" class="form-control" placeholder="Catgory Description" v-model="form.description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
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
            form:{
                id: null,
                name: null,
                description: null
            }
        }
    },
    methods:{
        submit(e) {
            e.preventDefault();
            const vm = this;

            let url = vm.form.id == null ? '/expense-categories' : '/expense-categories/update';

            axios.post(url, this.form)
            .then(function (response) {
                showResponse(response.data);

                if(response.data.success){
                    clearForm("#form-category");
                    refreshDataTable("#datatable");
                    $("#category-modal").modal("toggle");
                }
            })
            .catch(function (error) {
                showError(error);
            });
        },
        removeCategory(id) {
            const vm = this;

            if(confirm("Are you sure you want to delete?")){
                axios.delete(`/expense-categories/`+id)
                .then(function (response) {
                    showResponse(response.data);
                    if(response.data.success){
                        refreshDataTable('#datatable');
                    }
                })
                .catch(function (error) {
                    showError(error);
                });
            }
        },
        fetchCategory(id) {
            const vm = this;
            let url = "/expense-categories/"+id+"/get";
            axios.get(url)
                .then(function(response){
                    let data = response.data;
                    vm.form.id = data.id;
                    vm.form.name = data.name;
                    vm.form.description = data.description;
                })
                .catch(function(error){
                    showError(error);
                });

            $("#category-modal").modal("show");
        },
    },
    mounted(){
        const vm = this;
        $("#datatable").DataTable({
            responsive:true,
            processing:true,
            serverSide:true,
            ajax:"/expense-categories/list",
            columns:[
                {data:"name"},
                {data:"description"},
                {data:"action", className:"text-center"}
            ],
        });

        $(document).on("click", ".btn-edit", function(){
            let id = $(this).attr("value");
            vm.fetchCategory(id);
        });

        $(document).on("click", ".btn-delete", function(){
            let id = $(this).attr("value");
            vm.removeCategory(id);
        });
    }
}
</script>
