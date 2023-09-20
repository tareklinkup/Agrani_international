<style>
.v-select {
    margin-bottom: 5px;
}

.v-select .dropdown-toggle {
    padding: 0px;
}

.v-select input[type=search],
.v-select input[type=search]:focus {
    margin: 0px;
}

.v-select .vs__selected-options {
    overflow: hidden;
    flex-wrap: nowrap;
}

.v-select .selected-tag {
    margin: 2px 0px;
    white-space: nowrap;
    position: absolute;
    left: 0px;
}

.v-select .vs__actions {
    margin-top: -5px;
}

.v-select .dropdown-menu {
    width: auto;
    overflow-y: auto;
}

.button {
    width: 25px;
    height: 25px;
    border: none;
    color: white;
}

.add-button {
    padding: 2.5px;
    width: 28px;
    background-color: #298db4;
    display: block;
    text-align: center;
    color: white;
}

.add-button:hover {
    background-color: #41add6;
    color: white;
}

.edit {
    background-color: #7bb1e0;
}

.active {
    background-color: rgb(252, 89, 89);
}
</style>

<div id="bankAccounts">
    <div class="widget-box">
        <div class="widget-header">
            <h4 class="widget-title">LC Information</h4>
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

                    <div class="col-md-6 col-offset-1">
                        <div class="form-group">
                            <label for="" class="control-label col-md-4">Account</label>
                            <div class="col-md-8">
                                <v-select v-bind:options="accounts" v-model="selectedAccount" label="account_name"
                                    @input="accountOnChange"></v-select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="control-label col-md-4">Account Number.</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" v-model="selectedAccount.account_number"
                                    required>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="" class="control-label col-md-4">Bank Name</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" v-model="selectedAccount.bank_name" required>
                            </div>
                        </div>

                        <!-------Add Expenses --------->

                        <form v-on:submit.prevent="addToConver">
                            <div class="form-group clearfix">
                                <label class="control-label col-md-4"> Expenses:</label>

                                <div class="col-md-8">
                                    <a type="button" class="btn btn-primary btn-xs" @click="addToConver()"
                                        style="margin-bottom:10px;">
                                        Add Expenses
                                    </a>
                                </div>
                            </div>
                        </form>

                        <div style="display:none;" v-bind:style="{display: cart.length > 0 ? '' : 'none'}">
                            <div v-for="(expense, sl) in cart">
                                <div class="form-group clearfix">
                                    <label class="control-label col-md-4">Expenses Name:</label>
                                    <div class="col-md-7">
                                        <select class="form-control" v-model="expense.expense_name"
                                            style="height: 30px; border-radius: 5px;">
                                            <option value=""></option>
                                            <?php if ($conunits && isset($conunits)) : foreach ($conunits as $conunit) : ?>
                                            <option value="<?= $conunit->expenses_name; ?>">
                                                <?= $conunit->expenses_name; ?>
                                            </option>
                                            <?php endforeach;
                                            endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-1" style="padding:0;margin-left: -15px;">
                                        <a type="button" class="add-button" @click="removeFromCon(sl)">
                                            <i class="fa fa-minus"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class="form-group clearfix">
                                    <label class="control-label col-md-4">Pay Bill:</label>
                                    <div class="col-md-7">
                                        <input type="number" step="any" class="form-control" v-model="expense.pay_bill">
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>

                    <div class="col-md-5">

                        <div class="form-group">
                            <label for="" class="control-label col-md-4">LC No</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" v-model="account.Lcc_No">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="control-label col-md-4">Supplier</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" v-model="account.supplier_name">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="control-label col-md-4">Description</label>
                            <div class="col-md-8">
                                <textarea class="form-control" v-model="account.description"></textarea>
                            </div>
                        </div>

                        <form v-on:submit.prevent="addToCart">
                            <div class="form-group">
                                <label class="col-md-4 control-label no-padding-right"> Product </label>
                                <div class="col-md-7">
                                    <v-select v-bind:options="products" v-model="selectedProduct" label="display_text"
                                        v-on:input="productOnChange"></v-select>
                                </div>
                                <div class="col-md-1" style="padding: 0;">
                                    <a href="<?= base_url('product') ?>" class="btn btn-xs btn-danger"
                                        style="height: 25px; border: 0; width: 27px; margin-left: -10px;"
                                        target="_blank" title="Add New Product"><i class="fa fa-plus" aria-hidden="true"
                                            style="margin-top: 5px;"></i></a>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-md-4 control-label no-padding-right"> Purchase Rate </label>
                                <div class="col-md-8">
                                    <input type="number" id="salesRate" placeholder="Rate" step="0.01"
                                        class="form-control" v-model="selectedProduct.Product_Purchase_Rate"
                                        v-on:input="productTotal" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label no-padding-right"> Quantity </label>
                                <div class="col-md-8">
                                    <input type="number" step="0.01" id="quantity" placeholder="Qty"
                                        class="form-control" ref="quantity" v-model="selectedProduct.quantity"
                                        v-on:input="productTotal" autocomplete="off" required />
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-md-4 control-label no-padding-right"> Amount </label>
                                <div class="col-md-8">
                                    <input type="text" id="productTotal" placeholder="Amount" class="form-control"
                                        v-model="selectedProduct.total" readonly />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-xs-3 control-label no-padding-right"> </label>
                                <div class="col-xs-9">
                                    <button type="submit" class="btn btn-default pull-right">Add to Cart</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-12" style="margin-top:35px; width:90%; margin:auto;">
                        <div class="col-xs-12 col-md-12 col-lg-12" style="padding-left: 0px;padding-right: 0px;">
                            <div class="table-responsive">
                                <table class="table table-bordered" style="color:#000;margin-bottom: 5px;">
                                    <thead>
                                        <tr class="">
                                            <th style="width:10%;color:#000;">Sl</th>
                                            <th style="width:15%;color:#000;">Product Code</th>
                                            <th style="width:20%;color:#000;">Product Name</th>
                                            <th style="width:7%;color:#000;">Qty</th>
                                            <th style="width:8%;color:#000;">Rate</th>
                                            <th style="width:15%;color:#000;">Total Amount</th>
                                            <th style="width:10%;color:#000;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody style="display:none;"
                                        v-bind:style="{display: cartProduct.length > 0 ? '' : 'none'}">
                                        <tr v-for="(product, sl) in cartProduct">
                                            <td>{{ sl + 1 }}</td>
                                            <td>{{ product.productCode }}</td>
                                            <td>{{ product.name }}</td>
                                            <td>{{ product.quantity }}</td>
                                            <td>{{ product.purchaseRate }}</td>
                                            <td>{{ product.total }}</td>
                                            <td><a href="" v-on:click.prevent="removeFromCart(sl)"><i
                                                        class="fa fa-trash"></i></a></td>
                                        </tr>

                                        <tr style="font-weight: bold;">
                                            <td colspan="5">Total</td>
                                            <td> {{ account.subTotal }}</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" style="margin-top:25px;">
                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <input type="submit" @click="saveAccount" value="Save" class="btn btn-success">
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <div class="widget-box">
        <div class="widget-header">
            <h4 class="widget-title">LC List</h4>
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
                        <div class="table-responsive">
                            <datatable :columns="columns" :data="lcc_detiails" :filter-by="filter">
                                <template scope="{ row }">
                                    <tr>
                                        <td>{{ row.account_name }}</td>
                                        <td>{{ row.account_number }}</td>
                                        <td>{{ row.bank_name }}</td>
                                        <td>{{ row.Lcc_No }}</td>
                                        <td>{{ row.branch_name }}</td>
                                        <td>{{ row.description }}</td>
                                        <!-- <td>{{ row.status_text }}</td> -->
                                        <td>
                                            <?php if ($this->session->userdata('accountType') != 'u') { ?>
                                            <button class="button edit" @click="editAccount(row)">
                                                <i class="fa fa-pencil"></i>
                                            </button>

                                            <button @click="viewDetails(row)"> <i class="fa fa-eye"> </i></button>

                                            <button @click="changeStatus(row)" v-if="row.status == 'p'"
                                                style="background:red; border-radius:5px; color:#fff; border:1px solid #ccc;">
                                                Pending
                                            </button>

                                            <button v-else
                                                style="background:green; border-radius:5px; color:#fff; border:1px solid #ccc;">
                                                Approve
                                            </button>
                                            <!-- <button class="button" v-bind:class="{active: row.status == 'a'}"
                                                @click="lcDelete(row)">
                                                <i class="fa fa-trash"></i>
                                            </button> -->
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
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
Vue.component('v-select', VueSelect.VueSelect);
new Vue({
    el: '#bankAccounts',
    data() {
        return {
            account: {
                Lcc_SlNo: '',
                account_id: null,
                Lcc_No: '',
                description: '',
                supplier_name: '',
                subTotal: ''
            },

            sales: {

            },

            accounts: [],
            lcc_detiails: [],
            products: [],
            selectedProduct: {
                Product_SlNo: '',
                display_text: 'Select Product',
                Product_Name: '',
                quantity: 0,
                Product_Purchase_Rate: '',
                total: 0.00
            },
            selectedAccount: {
                account_name: '',
                account_number: '',
                bank_name: '',
            },
            selectedExpense: {
                expense_name: '',
                pay_bill: '0.0000'
            },

            cart: [],
            cartProduct: [],
            columns: [{
                    label: 'Account Name',
                    field: 'account_name',
                    align: 'center'
                },
                {
                    label: 'Account Number',
                    field: 'account_number',
                    align: 'center'
                },
                {
                    label: 'Bank Name',
                    field: 'bank_name',
                    align: 'center'
                },

                {
                    label: 'LCC Number',
                    field: 'Lcc_No',
                    align: 'center'
                },
                {
                    label: 'Branch Name',
                    field: 'branch_name',
                    align: 'center'
                },
                // { label: 'Initial Balance', field: 'initial_balance', align: 'center' },
                {
                    label: 'Description',
                    field: 'description',
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
        this.getAccounts();
        this.getLccDetails();
        this.getProducts();
    },
    methods: {

        getLccDetails() {
            axios.get('/get_lcc_details').then(res => {
                this.lcc_detiails = res.data;
            })
        },

        viewDetails(row) {
            let lc_id = row.Lcc_SlNo;
            window.open('/lc_invoice_print/' + lc_id, '_blank');
        },

        addToCart() {
            let product = {
                productId: this.selectedProduct.Product_SlNo,
                productCode: this.selectedProduct.Product_Code,
                categoryName: this.selectedProduct.ProductCategory_Name,
                name: this.selectedProduct.Product_Name,
                salesRate: this.selectedProduct.Product_SellingPrice,
                vat: this.selectedProduct.vat,
                quantity: this.selectedProduct.quantity,
                total: this.selectedProduct.total,
                purchaseRate: this.selectedProduct.Product_Purchase_Rate
            }

            if (product.productId == '') {
                alert('Select Product');
                return;
            }

            if (product.quantity == 0 || product.quantity == '') {
                alert('Enter quantity');
                return;
            }

            if (product.salesRate == 0 || product.salesRate == '') {
                alert('Enter sales rate');
                return;
            }

            if (product.quantity > this.productStock && this.sales.isService == 'false') {
                alert('Stock unavailable');
                return;
            }

            let cartInd = this.cartProduct.findIndex(p => p.productId == product.productId);
            if (cartInd > -1) {
                this.cartProduct.splice(cartInd, 1);
            }

            this.cartProduct.unshift(product);
            this.clearProduct();
            this.calculateTotal();
        },

        removeFromCart(ind) {
            this.cartProduct.splice(ind, 1);
            this.calculateTotal();
        },

        async productOnChange() {
            if ((this.selectedProduct.Product_SlNo != '' || this.selectedProduct.Product_SlNo != 0)) {
                this.account.Product_Purchase_Rate = this.selectedProduct.Product_Purchase_Rate;
            }

            this.$refs.quantity.focus();
        },

        productTotal() {
            this.selectedProduct.total = (parseFloat(this.selectedProduct.quantity) * parseFloat(this
                .selectedProduct.Product_Purchase_Rate)).toFixed(2);
        },
        clearProduct() {
            this.selectedProduct = {
                Product_SlNo: '',
                display_text: 'Select Product',
                Product_Name: '',
                Unit_Name: '',
                quantity: 0,
                Product_Purchase_Rate: '',
                Product_SellingPrice: 0.00,
                vat: 0.00,
                total: 0.00
            }
            this.productStock = '';
            this.productStockText = '';
        },
        calculateTotal() {
            this.account.subTotal = this.cartProduct.reduce((prev, curr) => {
                return prev + parseFloat(curr.total)
            }, 0).toFixed(2);

        },
        getAccounts() {
            axios.get('/get_bank_accounts').then(res => {
                this.accounts = res.data;
            })
        },

        removeFromCon(ind) {
            this.cart.splice(ind, 1);
        },

        accountOnChange() {
            this.account.account_number = this.selectedAccount.account_number;
            this.account.bank_name = this.selectedAccount.bank_name;
            this.account.branch_name = this.selectedAccount.branch_name;
        },

        getProducts() {
            axios.post('/get_products', {
                isService: 'false'
            }).then(res => {
                this.products = res.data;
            })
        },

        addToConver() {

            let expense = {};
            // // let cartInd = this.cart.findIndex(p => p.per_unit == conversion.per_unit);
            // // if(cartInd > -1){
            // // 	this.cart.splice(cartInd, 1);
            // // }

            this.cart.push(expense);
            this.clearExpenses();
        },

        clearExpenses() {
            this.selectedExpense = {
                expense_name: '',
                pay_bill: '0.0000'
            }
        },

        saveAccount() {

            if (this.selectedAccount == null) {
                alert("Please select bank account!");
                exit;
            }

            if (this.selectedAccount != null && this.selectedAccount.account_id != '') {
                this.account.account_id = this.selectedAccount.account_id;
                this.account.account_name = this.selectedAccount.account_name;
            } else {
                this.account.account_id = null;
                this.account.account_name = '';
            }

            // this.account.Product_SlNo = this.selectedProduct != null && this.selectedProduct.Product_SlNo !=
            //     '' ?
            //     this.selectedProduct.Product_SlNo : null;

            // this.account.Product_Name = this.selectedProduct != null && this.selectedProduct.Product_SlNo !=
            //     '' ?
            //     this.selectedProduct.Product_Name : null;

            let data = {
                account: this.account,
                cart: this.cart,
                productCart: this.cartProduct
            }

            console.log(data);

            let url = '/add_lcc_details';
            if (this.account.Lcc_SlNo != 0) {
                url = '/update_lcc_details';
            }


            axios.post(url, data)
                .then(res => {
                    let r = res.data;
                    if (r.success) {
                        this.clearForm();
                        let conf = confirm('LC Entry success, Do you want to view invoice?');
                        if (conf) {
                            window.open('/lc_invoice_print/' + r.lc_id, '_blank');
                            new Promise(r => setTimeout(r, 1000));
                            window.location.reload();
                        } else {
                            window.location.reload();
                        }
                        // this.getLccDetails();
                    }
                })

        },

        clearForm() {

            this.account = {
                Lcc_SlNo: '',
                account_id: null,
                Lcc_No: '',
                description: ''
            }

            this.cart = [];

            this.selectedAccount = {
                account_name: '',
                account_number: '',
                bank_name: '',
            }
        },


        editAccount(account) {

            console.log(account);
            let keys = Object.keys(this.account);
            keys.forEach(key => {
                this.account[key] = account[key];
                this.account.subTotal = account.lc_purchase_total_amount;
            })

            // this.calculateTotal();

            // Object.keys(this.account).forEach(key => {
            //     // this.account[key] = account[key];
            //     this.account.Lcc_No = account.Lcc_No;
            //     this.Lcc_SlNo = account.Lcc_SlNo;
            //     this.account.bank_name = account.bank_name;
            //     this.account.branch_name = account.branch_name;
            //     this.account.description = account.description;
            // })

            // this.selectedAccount = {
            //     account_id: account.account_id,
            //     account_name: account.account_name
            // }

            this.selectedAccount = {
                    account_id: account.account_id,
                    account_name: account.account_name,
                    account_number: account.account_number,
                    bank_name: account.bank_name
                },

                // selectedProduct = {
                //     Product_SlNo: account.product_id,
                //     // display_text: 'Select Product',
                //     Product_Name: account.Product_Name,
                //     display_text: account.Product_Name,

                // },


                axios.post('/get_lcc_expenses_stock', {
                    lccId: account.Lcc_SlNo
                }).then(res => {
                    res.data.forEach(item => {
                        let expenses = {
                            expense_name: item.expense_name,
                            pay_bill: item.pay_bill
                        }
                        this.cart.push(expenses);
                    })
                })

            this.cart = [];

            axios.post('/get_lc_purchase_details', {
                lcId: account.Lcc_SlNo
            }).then(res => {
                res.data.forEach(product => {
                    let cartProduct = {
                        productCode: product.Product_Code,
                        productId: product.Product_IDNo,
                        categoryName: product.ProductCategory_Name,
                        name: product.Product_Name,
                        salesRate: product.SaleDetails_Rate,
                        vat: product.SaleDetails_Tax,
                        quantity: product.LC_PurchaseDetails_TotalQuantity,
                        total: product.LC_PurchaseDetails_TotalAmount,
                        purchaseRate: product.LC_PurchaseDetails_Rate,
                    }

                    this.cartProduct.push(cartProduct);
                })
                console.log(res.data);
            })

            this.cart = []

        },

        changeStatus(account) {
            axios.post('/change_bank_status', {
                    account: account
                }).then(res => {
                    let r = res.data;
                    alert(r.message);
                    if (r.success) {
                        this.getLccDetails();
                    }
                })
                .catch(error => {
                    if (error.response) {
                        alert(`${error.response.status}, ${error.response.statusText}`);
                    }
                })
        },

        lcDelete(account) {
            let lcId = account.Lcc_SlNo;
            axios.post('/delete_lc', {
                    lc_id: lcId
                }).then(res => {
                    let r = res.data;
                    alert(r.message);
                    if (r.success) {
                        this.getLccDetails();
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
                account_id: 0,
                account_number: '',
                account_name: '',
                account_type: '',
                bank_name: '',
                branch_name: '',
                initial_balance: 0.00
            }
        }
    }
})
</script>