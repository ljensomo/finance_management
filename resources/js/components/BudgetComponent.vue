<template>
    <div>
        <div class="card">
            <div class="card-header"><strong>Budgets</strong></div>
            <div class="card-body">
                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#budget-modal">New Budget</button><hr>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="datatable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <!-- <th>Type</th> -->
                                <th>Budget For</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="budget-modal" tabindex="-1" role="dialog" aria-labelledby="budget-modal-label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="expense-modal-label">Budget</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>    
                </div>
                <form action="" id="form-budget" @submit="submit">
                    <input type="hidden" name="id" v-model="form.id" id="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" v-model="form.name" id="name" placeholder="Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="date_from" class="form-label">From</label>
                            <input type="date" id="date_from" class="form-control" name="date_from" v-model="form.date_from" required>
                        </div>
                        <div class="mb-3">
                            <label for="date_to" class="form-label">To</label>
                            <input type="date" id="date_to" class="form-control" name="date_to" v-model="form.date_to" required>
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
            form: {
                id: null,
                name: null,
                type: null,
                date_from: null,
                date_to: null
            },
            types:null
        }
    },
    methods: {
        submit(e) {
            e.preventDefault();
            const vm = this;

            let url = vm.form.id == null ? '/budgets' : '/budgets/update'

            axios.post(url, this.form)
            .then(function (response) {
                showResponse(response.data);

                if(response.data.success){
                    clearForm("#form-budget");
                    refreshDataTable("#datatable");
                    $("#budget-modal").modal("toggle");
                }
            })
            .catch(function (error) {
                showError(error);
            });
        },
        removeBudget(id) {
            const vm = this;

            if(confirm("Are you sure you want to delete?")){
                axios.delete(`/budgets/`+id)
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
        fetchBudget(id) {
            const vm = this;
            let url = "/budgets/"+id+"/get";
            axios.get(url)
                .then(function(response){
                    let data = response.data;
                    vm.form.id = data.id;
                    vm.form.name = data.name;
                    // vm.form.type = data.type;
                    vm.form.date_from = data.date_from;
                    vm.form.date_to = data.date_to;
                })
                .catch(function(error){
                    showError(error);
                });

            $("#budget-modal").modal("show");
        },
    },
    beforeMount(){
        const vm = this;
        axios.get('/budget-types/json-list')
            .then(function(response){
                const data = response.data;
                vm.types = data;
            })
            .catch(function(error){
                showError(error);
            });
    },
    mounted(){
        const vm = this;
        $("#datatable").DataTable({
            responsive:true,
            processing:true,
            ajax:"/budgets/list",
            columns: [
                {data:"name"},
                // {data:"type"},
                {data:function(data, type, toSet){
                    return data.date_from+" - "+data.date_to;
                }},
                {data:function(data, type, toSet){
                    return "₱ "+data["total_amount"];
                },className:"text-end"},
                {data:"status",className:"text-center"},
                {data:"action",className:"text-center"},
            ]
        });

        $(document).on("click", ".btn-edit", function(){
            let id = $(this).attr("value");
            vm.fetchBudget(id);
        });

        $(document).on("click", ".btn-delete", function(){
            let id = $(this).attr("value");
            vm.removeBudget(id);
        });
    }
}
</script>