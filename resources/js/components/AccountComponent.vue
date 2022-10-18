<template>
    <div>
        <!-- Page Content -->
        <div class="card">
            <div class="card-header">
                <strong>Accounts</strong>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#account-modal">Add Account</button><hr>
                <table class="table table-bordered table-hover table-striped" id="datatable">
                    <thead>
                        <tr>
                            <th>Account</th>
                            <th>Description</th>
                            <th>Remaining Balance</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="account-modal" tabindex="-1" role="dialog" aria-labelledby="account-modal-label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="account-modal-label">Account(s)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>    
                </div>
                <form action="" id="form-account" @submit="submit">
                    <input type="hidden" name="id" v-model="form.id" id="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Account Name</label>
                            <input type="text" class="form-control" v-model="form.name" id="name" placeholder="Account Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" v-model="form.description" cols="30" rows="5" placeholder="Description" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="incomeType" class="form-label">Balance</label>
                            <input type="number" name="balance" step=".01" class="form-control" id="balance" v-model="form.balance" placeholder="0.00" required>
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

        <!-- Transfer Modal -->
        <div class="modal fade" id="transfer-modal" tabindex="-1" role="dialog" aria-labelledby="transfer-modal-label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="account-modal-label">Transfer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>    
                </div>
                <form action="" id="form-transfer" @submit="transfer">
                    <input type="hidden" name="account_id_from" v-model="transfer_form.account_id_from" id="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Account</label>
                            <select class="form-control" name="account_id_to" id="account_id_to" v-model="transfer_form.account_id_to" required>
                                <option value="">-- Choose Account</option>
                                <option v-for="account in accounts" v-bind:key="account.id" v-bind:value="account.id">{{ account.name }}</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fee" class="form-label">Amount</label>
                            <input type="number" step="any" min="1" name="amount" class="form-control" id="amount" v-model="transfer_form.amount" placeholder="0.00" required>
                        </div>
                        <div class="mb-3">
                            <label for="fee" class="form-label">Transaction Fee</label>
                            <input type="number" step="1" min="0" name="fee" class="form-control" id="fee" v-model="transfer_form.fee" placeholder="0.00" required>
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
                id:null,
                name:null,
                description:null,
                balance:null,
            },
            transfer_form: {
                account_id_from: null,
                account_id_to: null,
                amount: null,
                fee: null
            },
            accounts: null
        }
    },
    methods: {
        submit(e){
            e.preventDefault();

            const vm = this;

            let url = vm.form.id == null ? '/accounts' : '/accounts/update'

            axios.post(url, this.form)
            .then(function (response) {
                showResponse(response.data);

                if(response.data.success){
                    clearForm("#form-account");
                    refreshDataTable("#datatable");
                    $("#account-modal").modal("toggle");
                }
            })
            .catch(function (error) {
                showError(error);
            });
        },
        transfer(e){
            e.preventDefault();

            axios.post('accounts/transfer', this.transfer_form)
            .then(function (response) {
                showResponse(response.data);

                if(response.data.success){
                    clearForm("#form-account");
                    refreshDataTable("#datatable");
                    $("#transfer-modal").modal("toggle");
                }
            })
            .catch(function (error) {
                showError(error);
            });
        },
        removeAccount(id){
            if(confirm("Are you sure you want to delete?")){
                axios.delete(`/accounts/`+id)
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
        fetchAccount(id) {
            const vm = this;
            let url = "/accounts/"+id+"/get";
            axios.get(url)
                .then(function(response){
                    let data = response.data;
                    vm.form.id = data.id;
                    vm.form.name = data.name;
                    vm.form.description = data.description;
                    vm.form.balance = data.balance;
                })
                .catch(function(error){
                    showError(error);
                });

            $("#account-modal").modal("show");
        },
    },
    mounted(){
        const vm = this;
        $("#datatable").DataTable({
            responsive:true,
            processing:true,
            serverSide:true,
            ajax:"/accounts/list",
            columns:[
                {data:"name"},
                {data:"description"},
                {className:"text-end", data:function(data, xhr, status){
                    return formatNumber(data["balance"]);
                }},
                {data:"action", className:"text-center"}
            ],
        });

        $(document).on("click", ".btn-edit", function(){
            let id = $(this).attr("value");
            vm.fetchAccount(id);
        });

        $(document).on("click", ".btn-delete", function(){
            let id = $(this).attr("value");
            vm.removeAccount(id);
        });

        $(document).on("click", ".btn-transfer", function(){
            let id = $(this).attr("value");
            vm.transfer_form.account_id_from = id;

            axios.get('/accounts/'+id+'/json-list')
                .then(function(response){
                    const data = response.data;
                    vm.accounts = data;
                })
                .catch(function(error){
                    showError(error);
                });
                
            $("#transfer-modal").modal("toggle");
        });
    }
}
</script>
