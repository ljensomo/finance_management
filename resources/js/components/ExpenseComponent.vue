<template>
    <div>
        <div id="div-message"></div>
        <div class="card">
            <div class="card-header">
                <strong>Expenses</strong>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#expense-modal">Add Expense</button><hr>
                    <table class="table table-bordered table-hover table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th>Date Expend</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Category</th>
                                <th>Charged From</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="expense-modal" tabindex="-1" role="dialog" aria-labelledby="expense-modal-label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="expense-modal-label">Expense</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>    
                </div>
                <form action="" id="form-expense" @submit="submit">
                    <input type="hidden" name="id" v-model="form.id" id="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="incomeType" class="form-label">Category</label>
                            <select name="incomeType" class="form-control" v-model="form.category" id="category" required>
                                <option value="">-- Choose Type</option>
                                <option v-for="category in categories" v-bind:key="category.id" v-bind:value="category.id">{{ category.name }}</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="dateExpend" class="form-label">Date Expend</label>
                            <input type="date" class="form-control" v-model="form.date_expend" id="date-expend" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" class="form-control" v-model="form.description" id="description" cols="30" rows="2" placeholder="Description of Expense" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" v-model="form.amount" placeholder="0.00" id="amount" required>
                        </div>
                        <div class="mb-3">
                            <label for="incomeType" class="form-label">Account</label>
                            <select name="incomeType" class="form-control" v-model="form.charged_from" id="charged-from" required>
                                <option value="">-- Choose Account</option>
                                <option v-for="account in accounts" v-bind:key="account.id" v-bind:value="account.id">{{ account.name }}</option>
                            </select>
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
    data() {
        return {
            form: {
                id: null,
                date_expend: null,
                description: null,
                amount: null,
                category: null,
                charged_from: null
            },
            categories: null,
            accounts: null,
        }
    },
    methods: {
        submit(e) {
            e.preventDefault();
            const vm = this;

            let url = vm.form.id == null ? '/expenses' : '/expenses/update'

            axios.post(url, this.form)
            .then(function (response) {
                showResponse(response.data);

                if(response.data.success){
                    clearForm("#form-expense");
                    refreshDataTable("#datatable");
                    $("#expense-modal").modal("toggle");
                }
            })
            .catch(function (error) {
                showError(error);
            });
        },
        removeExpense(id) {
            const vm = this;

            if(confirm("Are you sure you want to delete?")){
                axios.delete(`/expenses/`+id)
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
        fetchExpense(id) {
            const vm = this;
            let url = "/expenses/"+id+"/get";
            axios.get(url)
                .then(function(response){
                    let data = response.data;
                    vm.form.id = data.id;
                    vm.form.date_expend = data.date_expend;
                    vm.form.description = data.description;
                    vm.form.amount = data.amount
                    vm.form.category = data.category;
                    vm.form.charged_from = data.charged_from;
                })
                .catch(function(error){
                    showError(error);
                });

            $("#expense-modal").modal("show");
        },
    },
    beforeMount(){
        const vm = this;
        axios.get('/expense-categories/json-list')
            .then(function(response){
                const data = response.data;
                vm.categories = data;
            })
            .catch(function(error){
                showError(error);
            });

        axios.get('/accounts/0/json-list')
            .then(function(response){
                const data = response.data;
                vm.accounts = data;
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
            serverSide:true,
            ajax:"/expenses/list",
            columns:[
                {data:"date_expend"},
                {data:"description"},
                {data:function(data, xhr, status){
                    return formatNumber(data["amount"]);
                },className:"text-end"},
                {data:"category",className:"text-center"},
                {data:"charged_from",className:"text-center"},
                {data:"action", className:"text-center"}
            ],
        });

        $(document).on("click", ".btn-edit", function(){
            let id = $(this).attr("value");
            vm.fetchExpense(id);
        });

        $(document).on("click", ".btn-delete", function(){
            let id = $(this).attr("value");
            vm.removeExpense(id);
        });
    }
}
</script>
