<style>
#accountForm select {
    padding: 0 !important;
}

#accountsTable .button {
    width: 25px;
    height: 25px;
    border: none;
    color: white;
}

#accountsTable .edit {
    background-color: #7bb1e0;
}

#accountsTable .delete {
    background-color: #ff6666;
}
</style>

<div id="accounts">
    <div class="widget-box">
        <div class="widget-header">
            <h4 class="widget-title">LC Expense</h4>
            <div class="widget-toolbar">
                <a href="#" data-action="collapse">
                    <i class="ace-icon fa fa-chevron-up"></i>
                </a>

                <a href="#" data-action="close">
                    <i class="ace-icon fa fa-times"></i>
                </a>
            </div>
        </div>

        <div class="widget-body">
            <div class="widget-main">
                <form id="accountForm" class="form-horizontal" @submit.prevent="saveAccount">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-3">

                            <div class="form-group">
                                <label class="control-label col-md-4">Expense Name</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" v-model="account.expenses_name" required
                                        style="height:35px;">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <input v-if="account.expense_id == null " type="submit" value="Save"
                                        class="btn btn-success" style="border-radius:5px;">
                                    <input v-if="account.expense_id != null " type="submit" value="Update"
                                        class="btn btn-success" style="border-radius:5px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="widget-box">
        <div class="widget-header">
            <h4 class="widget-title">Expense List</h4>
            <div class="widget-toolbar">
                <a href="#" data-action="collapse">
                    <i class="ace-icon fa fa-chevron-up"></i>
                </a>

                <a href="#" data-action="close">
                    <i class="ace-icon fa fa-times"></i>
                </a>
            </div>
        </div>
        <div class="widget-body">
            <div class="widget-main">
                <div class="row">
                    <div class="col-md-4">
                        <label for="filter" class="sr-only">Filter</label>
                        <input type="text" class="form-control" v-model="filter" placeholder="Filter">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="accountsTable" class="table-responsive">
                            <datatable :columns="columns" :data="expenses" :filter-by="filter">
                                <template scope="{ row }">
                                    <tr>
                                        <td>{{ row.expenses_name }}</td>
                                        <td>
                                            <?php if ($this->session->userdata('accountType') != 'u') { ?>
                                            <button class="button edit" @click="editExpense(row)">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            <button class="button delete" @click="deleteExpense(row.expense_id)">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                </template>
                            </datatable>
                            <datatable-pager v-model="page" type="abbreviated" :per-page="per_page"></datatable-pager>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vuejs-datatable.js"></script>

<script>
new Vue({
    el: '#accounts',
    data() {
        return {
            account: {
                expense_id: null,
                expenses_name: '',
            },
            accounts: [],
            expenses: [],

            columns: [

                {
                    label: 'Expense Name',
                    field: 'expenses_name',
                    align: 'center'
                },
                {
                    label: 'Action',
                    align: 'center',
                    filterable: false
                }
            ],
            page: 1,
            per_page: 10,
            filter: ''
        }
    },
    created() {
        this.getLccExpenses();

    },
    methods: {

        getLccExpenses() {
            axios.get('/get_lcc_expenses').then(res => {
                this.expenses = res.data;
                console.log(this.expenses);
            })
        },

        saveAccount() {
            let url = '/add_lcc_expense';
            if (this.account.expense_id != null) {
                url = '/update_lcc_expense';
            }

            console.log(this.account);
            axios.post(url, this.account).then(res => {
                    let r = res.data;
                    alert(r.message);
                    if (r.success) {
                        this.resetForm();
                        this.account.Acc_Code = r.newAccountCode;
                        this.getLccExpenses()
                    }
                })
                .catch(error => {
                    if (error.response) {
                        alert(`${error.response.status}, ${error.response.statusText}`);
                    }
                })
        },

        editExpense(exp) {
            Object.keys(this.account).forEach(key => {
                this.account[key] = exp[key];
            })
        },

        deleteExpense(accountId) {
            let confirmation = confirm("Are you sure?");
            if (confirmation == false) {
                return;
            }
            axios.post('/delete_expense', {
                    accountId: accountId
                })
                .then(res => {
                    let r = res.data;
                    alert(r.message);
                    if (r.success) {
                        this.getLccExpenses();
                    }
                })
                .catch(error => {
                    if (error.response) {
                        alert(`${error.response.status}, ${error.response.statusText}`);
                    }
                })
        },

        resetForm() {
            this.account = {
                expense_id: null,
                expenses_name: ''
            }
        }
    }
})
</script>