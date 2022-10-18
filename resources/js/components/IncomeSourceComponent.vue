<template>
    <div>
        <!-- Modal -->
        <div class="modal fade" id="income-source-modal" tabindex="-1" aria-labelledby="income-source-modal-label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">Income Source</div>
                    <form action="" id="form-income-source" @submit="submit">
                        <input type="hidden" name="id" v-model="form.id" id="income_id">
                        <div class="modal-body">
                            <div id="div-message"></div>
                            <div class="mb-3">
                                <label for="type" class="form-label">Type</label>
                                <select name="types" id="types" class="form-control" v-model="form.income_type" required>
                                    <option selected value="">-- Choose Types</option>
                                    <option v-for="type in types" v-bind:key="type.id" v-bind:value="type.id">{{ type.type_name }}</option>
                                </select>
                                
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">Description</label>
                                <input type="text" name="description" id="description" placeholder="Description" class="form-control" v-model="form.income_description" required>
                            </div>
                            <div class="mb-3">
                                <label for="date_started" class="form-label">Date Started</label>
                                <input type="date" name="date_started" id="date_started" class="form-control" v-model="form.income_start">
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

        <div class="card">
            <div class="card-header">
                <strong>Income Sources</strong>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#income-source-modal">Add Income Source</button>
                <hr>
                <table class="table table-bordered table-hover table-striped" id="datatable">
                    <thead>
                        <tr>
                            <th>Income Type</th>
                            <th>Income Description</th>
                            <th>Income Started</th>
                            <th class="text-center">Income Status</th>
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
    data() {
        return {
            form: {
                id: null,
                income_description: null,
                income_type: null,
                income_start: null,
            },
            types: null
        }
    },
    methods: {
        submit(e) {
            e.preventDefault();

            const vm = this;
            let url = vm.form.id == null ? '/income-sources' : `/income-sources/update`;

            axios.post(url, this.form)
            .then(function (response) {
                showResponse(response.data);

                if(response.data.success){
                    clearForm("#form-income-source");
                    refreshDataTable("#datatable");
                    $("#income-source-modal").modal("toggle");
                }
            })
            .catch(function (error) {
                showError(error);
            });
        },
        removeIncomeSource(id) {
            const vm = this;

            if(confirm("Are you sure you want to delete?")){
                axios.delete(`/income-sources/`+id)
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
        fetchIncomeSource(id) {
            const vm = this;
            let url = "/income-sources/"+id+"/get";
            axios.get(url)
                .then(function(response){
                    let data = response.data;
                    vm.form.id = data.id;
                    vm.form.income_type = data.income_type;
                    vm.form.income_description = data.income_description;
                    vm.form.income_start = data.income_start;
                })
                .catch(function(error){
                    showError(error);
                });

            $("#income-source-modal").modal("toggle");
        },
    },
    beforeMount(){
        const vm = this;
        axios.get('/income-types/list')
            .then(function(response){
                // loadOptions("types", response.data)
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
            serverSide:true,
            ajax:"/income-sources/list",
            columns:[
                {data:"income_type"},
                {data:"income_description"},
                {data:"income_start"},
                {data:"status",className:"text-center"},
                {data:"action", className:"text-center"}
            ],
        });

        $(document).on("click", ".btn-edit", function(){
            let id = $(this).attr("value");
            vm.fetchIncomeSource(id);
        });

        $(document).on("click", ".btn-delete", function(){
            let id = $(this).attr("value");
            vm.removeIncomeSource(id);
        });
    }
}
</script>
