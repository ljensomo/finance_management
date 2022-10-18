<template>
    <div>
        <!-- Modal -->
        <div class="modal fade" id="income-modal" tabindex="-1" aria-labelledby="income-source-modal-label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">Income</div>
                    <form action="" @submit="submit" id="form-income">
                        <input type="hidden" name="id" v-model="form.id" id="income_id">
                        <div class="modal-body">
                            <div id="div-message"></div>
                            <div class="mb-3">
                                <label for="type" class="form-label">Income Source</label>
                                <select name="types" id="types" class="form-control" v-model="form.source" required>
                                    <option selected value="">-- Choose Sources</option>
                                    <option v-for="source in sources" v-bind:key="source.id" v-bind:value="source.id">{{ source.income_description }}</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" class="form-control" v-model="form.description" id="description" cols="30" rows="3" placeholder="Description of Income" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">Income Amount</label>
                                <input type="number" step="any" name="amount" id="amount" placeholder="Amount" class="form-control" v-model="form.amount" required>
                            </div>
                            <div class="mb-3">
                                <label for="date_started" class="form-label">Date</label>
                                <input type="date" name="date_started" id="date_started" class="form-control" v-model="form.income_date">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                                <button class="btn btn-success" type="submit">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Page content -->
        <div class="card">
            <div class="card-header">
                <strong>Incomes</strong>
            </div>
            <div class="card-body">
                <button class="btn btn-md btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#income-modal">Add Income</button><hr>
                <table class="table table-bordered table-hover table-striped" id="datatable">
                    <thead>
                        <tr>
                            <th>Source</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</template>

<script>
export default{
    props: ['income'],
    data() {
        return {
            incomeList: this.income,
            form: {
                id: null,
                source: null,
                description: null,
                amount: null,
                income_date: null
            },
            selectedId: null,
            sources: null,
        }
    },
    methods: {
        submit(e) {
            e.preventDefault();
            
            const vm = this;
            let url = vm.form.id == null ? '/incomes' : `/incomes/update`;

            axios.post(url, this.form)
            .then(function (response) {
                showResponse(response.data);
                if(response.data.success){
                    refreshDataTable(".table");
                    clearForm("#form-income");
                    $("#income-modal").modal("toggle");
                }
            })
            .catch(function (error) {
                showError(error);
            });
        },
        removeIncome(id) {
            const vm = this;

            if(confirm("Are you sure you want to delete?")){
                axios.delete(`/incomes/`+id)
                .then(function (response) {
                    showResponse(response.data);
                    if(response.data.success){
                        refreshDataTable('.table');
                    }
                })
                .catch(function (error) {
                    showError(error);
                });
            }
        },
        fetchIncome(id) {
            const vm = this;
            let url = "/incomes/"+id+"/get";
            axios.get(url)
                .then(function(response){
                    let data = response.data;
                    vm.form.id = data.id;
                    vm.form.source = data.source;
                    vm.form.description = data.description;
                    vm.form.amount = data.amount
                    vm.form.income_date = data.income_date;
                    vm.selectedId = data.source;
                })
                .catch(function(error){
                    showError(error);
                });

            $("#income-modal").modal("show");
        },
    },
    beforeMount(){
        const vm = this;
        axios.get('/income-sources/json-list')
            .then(function(response){
                const data = response.data;
                vm.sources = data;
            })
            .catch(function(error){
                showError(error);
            });
    },
    mounted(){
        const vm = this;
        $(".table").DataTable({
            responsive:true,
            processing:true,
            serverSide:true,
            ajax:"/incomes/list",
            columns:[
                {data:"income_description"},
                {data:"description"},
                {data:"amount",className:"text-end"},
                {data:"income_date",className:"text-center"},
                {data:"action", className:"text-center"}
            ],
        });

        $(document).on("click", ".btn-edit", function(){
            let id = $(this).attr("value");
            vm.fetchIncome(id);
        });

        $(document).on("click", ".btn-delete", function(){
            let id = $(this).attr("value");
            vm.removeIncome(id);
        });
    }
}
</script>
