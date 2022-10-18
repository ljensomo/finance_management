<template>
    <div>
        <div class="card">
            <div class="card-header">
                <strong>{{ budget_name }} (Budget Detail)</strong>
            </div>
            <div class="card-body">
                <a href="/budgets" class="btn btn-info">Back to Budgets</a>&nbsp;
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#budget-detail-modal">Add Detail</button><hr>
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered" id="datatable">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="text-end">Total</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="budget-detail-modal" tabindex="-1" role="dialog" aria-labelledby="budget-modal-label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="expense-modal-label">Budget Detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>    
                </div>
                <form action="" id="form-budget-detail" @submit="submit">
                    <input type="hidden" name="id" v-model="form.id" id="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" cols="30" rows="2" placeholder="Description" v-model="form.description" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" step="any" class="form-control" v-model="form.amount" placeholder="0" required>
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
    props:['budget'],
    data(){
        return {
            form:{
                budget_id: this.budget.id,
                id: null,
                description: null,
                amount: null
            },
            budget_name: this.budget.name
        }
    },
    methods: {
        submit(e) {
            e.preventDefault();
            const vm = this;

            let url = vm.form.id == null ? '/budget-details' : '/budget-details/update'

            axios.post(url, this.form)
            .then(function (response) {
                showResponse(response.data);

                if(response.data.success){
                    clearForm("#form-budget-detail");
                    refreshDataTable("#datatable");
                    $("#budget-detail-modal").modal("toggle");
                }
            })
            .catch(function (error) {
                showError(error);
            });
        },
        removeBudgetDetail(id) {
            const vm = this;

            if(confirm("Are you sure you want to delete?")){
                axios.delete(`/budget-details/`+id)
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
        fetchBudgetDetail(id) {
            const vm = this;
            let url = "/budget-details/"+id+"/get";
            axios.get(url)
                .then(function(response){
                    let data = response.data;
                    vm.form.id = data.id;
                    vm.form.description = data.description;
                    vm.form.amount = data.amount;
                })
                .catch(function(error){
                    showError(error);
                });

            $("#budget-detail-modal").modal("show");
        },
    },
    mounted(){
        const vm = this;
        $("#datatable").DataTable({
            responsive:true,
            processing:true,
            ajax:"/budget-details/"+this.form.budget_id+"/list",
            columns: [
                {data:"description"},
                {data:function(data, type, toSet){
                    return "₱ "+data["amount"];
                },className:"text-end"},
                {data:"action",className:"text-center"},
            ],
            footerCallback: function(row, data, start, end, display){
                var api = this.api();
                var intVal = function(i){
                    i = i.toString().replace('₱ ', '');
                    return parseInt(i);
                }

                let total = api.column(1).data().reduce(function(a, b){
                    return intVal(a) + intVal(b);
                }, 0);

                $(api.column(1).footer()).html('₱ ' + total.toFixed(2));
            }
        });

        $(document).on("click", ".btn-edit", function(){
            let id = $(this).attr("value");
            vm.fetchBudgetDetail(id);
        });

        $(document).on("click", ".btn-delete", function(){
            let id = $(this).attr("value");
            vm.removeBudgetDetail(id);
        });
    }
}
</script>
