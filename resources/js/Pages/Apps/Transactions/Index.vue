<template>
    <Head>
        <title>Transactions - CAFE-MAMI</title>
        <link rel="icon" href="/images/vue2.png">
    </Head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card border-0 rounded-3 shadow">
                            <div class="card-body">

                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="fa fa-barcode"></i></span>
                                    <input type="text" class="form-control" v-model="barcode" @keyup="searchProduct" placeholder="Scan or Input Barcode">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Product Name</label>
                                    <input type="text" class="form-control" :value="product.title" placeholder="Product Name" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Qty</label>
                                    <input type="number" class="form-control text-center" v-model="qty" placeholder="Qty" min="1">
                                </div>
                                <div class="text-end">
                                    <button @click.prevent="clearSearch" class="btn btn-warning btn-md border-0 shadow text-uppercase mt-3 me-2" :disabled="!product.id">CLEAR</button>
                                    <button @click.prevent="addToCart" class="btn btn-success btn-md border-0 shadow text-uppercase mt-3" :disabled="!product.id">ADD ITEM</button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">

                        <div v-if="session.error" class="alert alert-danger">
                            {{ session.error }}
                        </div>

                        <div v-if="session.success" class="alert alert-success">
                            {{ session.success }}
                        </div>

                        <div class="card border-0 rounded-3 shadow border-top-success">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-4">
                                        <h4 class="fw-bold">GRAND TOTAL</h4>
                                    </div>
                                    <div class="col-md-8 col-8 text-end">
                                        <h4 class="fw-bold">Rp. {{ formatPrice(grandTotal) }}</h4>
                                        <div v-if="change > 0">
                                            <hr>
                                            <h5 class="text-success">Change : <strong>Rp. {{ formatPrice(change) }}</strong></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 rounded-3 shadow">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="fw-bold">Cashier</label>
                                        <input class="form-control" type="text" :value="auth.user.name" readonly>
                                    </div>
                                    <div class="col-md-6 float-end">
                                        <label class="fw-bold">Customer</label>
                                        <VueMultiselect v-model="customer_id" label="name" track-by="name" :options="customers"></VueMultiselect>
                                    </div>
                                </div>
                                <hr>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr style="background-color: #e6e6e7;">
                                            <th scope="col">#</th>
                                            <th scope="col">Product Name</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Qty</th>
                                            <th scope="col">Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="cart in carts" :key="cart.id">
                                            <td class="text-center">
                                                <button @click.prevent="destroyCart(cart.id)" class="btn btn-danger btn-sm rounded-pill"><i class="fa fa-trash"></i></button>
                                            </td>
                                            <td>{{ cart.product.title }}</td>
                                            <td>Rp. {{ formatPrice(cart.product.sell_price) }}</td>
                                            <td class="text-center">{{ cart.qty }}</td>
                                            <td class="text-end">Rp. {{ formatPrice(cart.price) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end fw-bold" style="background-color: #e6e6e7;">TOTAL</td>
                                            <td class="text-end fw-bold" style="background-color: #e6e6e7;">Rp. {{ formatPrice(carts_total) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <hr>
                                <div class="d-flex align-items-end flex-column bd-highlight mb-3">
                                    <div class="mt-auto bd-highlight">
                                        <label>Discount (Rp.)</label>
                                        <input type="number" v-model="discount" @keyup="setDiscount" class="form-control" placeholder="Discount (Rp.)">
                                    </div>
                                    <div class="bd-highlight mt-4">
                                        <label>Pay (Rp.)</label>
                                        <input type="number" v-model="cash" @keyup="setChange" class="form-control" placeholder="Pay (Rp.)">
                                    </div>
                                </div>
                                <div class="text-end mt-4">
                                    <button class="btn btn-warning btn-md border-0 shadow text-uppercase me-2">Cancel</button>
                                    <button @click.prevent="storeTransaction" class="btn btn-purple btn-md border-0 shadow text-uppercase" :disabled="cash < grandTotal || grandTotal == 0">Pay Order & Print</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    import LayoutApp from '../../../Layouts/App.vue';
    import { Head, router } from '@inertiajs/vue3';
    import VueMultiselect from 'vue-multiselect';
    import 'vue-multiselect/dist/vue-multiselect.css';
    import { ref } from 'vue';
    import axios from 'axios';
    import Swal from 'sweetalert2';
    export default {
        layout: LayoutApp,
        components: {
            Head,
            VueMultiselect
        },
        props: {
            auth: Object,
            customers: Array,
            carts_total: Number,
            session: Object,
            carts: Array
        },
        setup(props) {
            const barcode = ref('');
            const product = ref({});
            const qty = ref(1);
            const searchProduct = () => {
                axios.post('/apps/transactions/searchProduct', {
                    barcode: barcode.value

                }).then(response => {
                    if(response.data.success) {
                        product.value = response.data.data;
                    } else {
                        product.value = {};
                    }
                });
            }
            const clearSearch = () => {
                product.value = {};
                barcode.value = '';
            }
            const grandTotal = ref(props.carts_total);
            const addToCart = () => {
                router.post('/apps/transactions/addToCart', {
                    product_id: product.value.id,
                    qty: qty.value,
                    sell_price: product.value.sell_price,
                    
                }, {
                    onSuccess: () => {
                        clearSearch();
                        qty.value = 1;
                        grandTotal.value = props.carts_total;
                        cash.value = 0;
                        change.value = 0;
                    },
                });

                
            }
            const destroyCart = (cart_id) => {
                router.post('/apps/transactions/destroyCart', {
                    cart_id: cart_id
                }, {
                    onSuccess: () => {
                        grandTotal.value = props.carts_total;
                        cash.value = 0;
                        change.value = 0;
                    },
                })
            }
            const cash      = ref(0);
            const change    = ref(0);
            const discount  = ref(0);
            const setDiscount = () => {
                grandTotal.value = props.carts_total - discount.value;
                cash.value = 0;
                change.value = 0;
            }
            const setChange = () => {
                change.value = cash.value - grandTotal.value;
            }
            const customer_id = ref('');
            const storeTransaction = () => {
                axios.post('/apps/transactions/store', {
                    customer_id: customer_id.value ? customer_id.value.id : '',
                    discount: discount.value,
                    grand_total: grandTotal.value,
                    cash: cash.value,
                    change: change.value
                })
                .then(response => {
                    clearSearch();
                    qty.value = 1;
                    grandTotal.value = props.carts_total;
                    cash.value = 0;
                    change.value = 0;
                    customer_id.value = '';
                    Swal.fire({
                        title: 'Success!',
                        text: 'Transaction Successfully.',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000
                    })
                    .then(() => {

                        setTimeout(() => {
                            window.open(`/apps/transactions/print?invoice=${response.data.data.invoice}`, '_blank');
                            location.reload();

                        }, 50);

                    })
                })

            }

            return {
                barcode,
                product,
                searchProduct,
                clearSearch,
                qty,
                grandTotal,
                addToCart,
                destroyCart,
                cash,
                change,
                discount,
                setDiscount,
                setChange,
                customer_id,
                storeTransaction
            }

        }
    }
</script>

<style>

</style>