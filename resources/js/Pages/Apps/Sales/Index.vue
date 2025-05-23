<template>
  <Head>
    <title>Report Sales - CAFE-MAMI</title>
    <link rel="icon" href="/images/vue2.png" />
  </Head>
  <main class="c-main">
    <div class="container-fluid">
      <div class="fade-in">
        <div class="row">
          <div class="col-md-12">
            <div class="card border-0 rounded-3 shadow border-top-purple">
              <div class="card-header">
                <span class="font-weight-bold">
                  <i class="fa fa-chart-bar"></i> REPORT SALES
                </span>
              </div>

              <div class="card-body">
                <form @submit.prevent="filter">
                  <div class="row">
                    <div class="col-md-5">
                      <div class="mb-3">
                        <label class="form-label fw-bold">START DATE</label>
                        <input type="date" v-model="start_date" class="form-control" />
                      </div>
                      <div v-if="errors.start_date" class="alert alert-danger">
                        {{ errors.start_date }}
                      </div>
                    </div>
                    <div class="col-md-5">
                      <div class="mb-3">
                        <label class="form-label fw-bold">END DATE</label>
                        <input type="date" v-model="end_date" class="form-control" />
                      </div>
                      <div v-if="errors.end_date" class="alert alert-danger">
                        {{ errors.end_date }}
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="mb-3">
                        <label class="form-label fw-bold text-white">*</label>
                        <button class="btn btn-md btn-purple border-0 shadow w-100">
                          <i class="fa fa-filter"></i> FILTER
                        </button>
                      </div>
                    </div>
                  </div>
                </form>

                <div v-if="sales">
                  <hr />
                  <div class="export text-end mb-3">
                    <a
                      :href="`/apps/sales/export?start_date=${start_date}&end_date=${end_date}`"
                      target="_blank"
                      class="btn btn-success btn-md border-0 shadow me-3"
                    >
                      <i class="fa fa-file-excel"></i> EXCEL
                    </a>
                    <a
                      :href="`/apps/sales/pdf?start_date=${start_date}&end_date=${end_date}`"
                      target="_blank"
                      class="btn btn-secondary btn-md border-0 shadow"
                    >
                      <i class="fa fa-file-pdf"></i> PDF
                    </a>
                  </div>

                  <div class="table-scroll-wrapper">
                    <button class="scroll-btn left" @click="scrollTable('left')">&#8592;</button>

                    <div class="table-responsive" ref="tableScroll">
                      <table class="table table-bordered">
                        <thead>
                          <tr style="background-color: #e6e6e7;">
                            <th scope="col">Date</th>
                            <th scope="col">Invoice</th>
                            <th scope="col">Cashier</th>
                            <th scope="col">Customer</th>
                            <th scope="col">Total</th>
                            <th scope="col" class="text-center">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="sale in sales" :key="sale.id">
                            <td>{{ sale.created_at }}</td>
                            <td class="text-center">{{ sale.invoice }}</td>
                            <td>{{ sale.cashier.name }}</td>
                            <td>{{ sale.customer ? sale.customer.name : 'Umum' }}</td>
                            <td class="text-end">Rp. {{ formatPrice(sale.grand_total) }}</td>
                            <td class="text-center">
                              <a
                                class="btn btn-sm btn-success"
                                :href="`/apps/sales/print/${sale.id}`"
                                target="_blank"
                                title="Print Nota"
                              >
                                <i class="fa fa-print"></i>
                              </a>
                              <button
                                @click="openEmailModal(sale.id)"
                                class="btn btn-sm btn-primary"
                                title="Send Invoice"
                              >
                                <i class="fa fa-envelope"></i>
                              </button>
                            </td>
                          </tr>
                          <tr>
                            <td colspan="5" class="text-end fw-bold" style="background-color: #e6e6e7;">
                              TOTAL
                            </td>
                            <td class="text-end fw-bold" style="background-color: #e6e6e7;">
                              Rp. {{ formatPrice(total) }}
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <button class="scroll-btn right" @click="scrollTable('right')">&#8594;</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <div v-if="showEmailModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Send Invoice to Email</h5>
          <button type="button" class="close" @click="closeEmailModal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="email">Recipient's Email</label>
            <input
              type="email"
              v-model="email"
              class="form-control"
              placeholder="Enter email"
            />
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeEmailModal">Close</button>
          <button type="button" class="btn btn-primary" @click="sendEmail">Send Email</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Swal from 'sweetalert2';
import LayoutApp from "../../../layouts/App.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref } from "vue";

export default {
  layout: LayoutApp,
  components: {
    Head,
    Link,
  },
  props: {
    errors: Object,
    sales: Array,
    total: Number,
  },
  setup() {
    const start_date = ref(
      "" || new URL(document.location).searchParams.get("start_date")
    );
    const end_date = ref(
      "" || new URL(document.location).searchParams.get("end_date")
    );
    const tableScroll = ref(null);
    const email = ref("");
    const showEmailModal = ref(false);
    const selectedSaleId = ref(null);

    const filter = () => {
      router.get("/apps/sales/filter", {
        start_date: start_date.value,
        end_date: end_date.value,
      });
    };

    const openEmailModal = (saleId) => {
      selectedSaleId.value = saleId;
      showEmailModal.value = true;
    };

    const closeEmailModal = () => {
      showEmailModal.value = false;
      email.value = "";
    };

    const sendEmail = () => {
      if (!email.value || !email.value.includes("@")) {
        Swal.fire({
          icon: 'error',
          title: 'Invalid Email',
          text: 'Please enter a valid email address.',
        });
        return;
      }
      Swal.fire({
        title: 'Konfirmasi Pengiriman',
        text: `Apakah Anda yakin ingin mengirim nota ke email ${email.value}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Kirim',
        cancelButtonText: 'Batal',
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            title: 'Sending Invoice...',
            text: 'Please wait while we send the invoice email.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
              Swal.showLoading();
            },
          });
          router.post(
            "/apps/sales/send-email",
            { email: email.value, sale_id: selectedSaleId.value },
            {
              preserveScroll: true,
              onSuccess: () => {
                Swal.fire({
                  icon: 'success',
                  title: 'Email Sent',
                  text: `Invoice successfully sent to ${email.value}`,
                });
                closeEmailModal();
              },
              onError: (errors) => {
                Swal.fire({
                  icon: 'error',
                  title: 'Failed to Send Email',
                  text: errors.email || 'An unexpected error occurred while sending email.',
                });
              },
            }
          );
        }
      });
    };

    const scrollTable = (direction) => {
      const el = tableScroll.value;
      if (!el) return;
      const amount = 200;
      el.scrollLeft += direction === "right" ? amount : -amount;
    };

    const formatPrice = (value) => {
      return value
        .toString()
        .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    };

    return {
      start_date,
      end_date,
      filter,
      tableScroll,
      scrollTable,
      formatPrice,
      email,
      showEmailModal,
      selectedSaleId,
      openEmailModal,
      closeEmailModal,
      sendEmail,
    };
  },
};
</script>

<style>
.table-scroll-wrapper {
  display: flex;
  align-items: center;
  overflow: hidden;
  margin-bottom: 1rem;
}

.table-responsive {
  width: 100%;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  scroll-behavior: smooth;
  flex: 1;
}

.scroll-btn {
  background: #42b883;
  color: white;
  border: none;
  padding: 6px 10px;
  font-size: 1.2rem;
  cursor: pointer;
  z-index: 2;
  border-radius: 4px;
}

@media (min-width: 768px) {
  .scroll-btn {
    display: none;
  }
}

.btn-sm.btn-dark {
  font-size: 0.8rem;
  padding: 4px 8px;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1050;
  width: 100vw;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: rgba(0, 0, 0, 0.5);
}

.modal-dialog {
  max-width: 500px;
  width: 100%;
  margin: 0;
  animation: fadeInDown 0.3s ease;
}

.modal-content {
  padding: 20px;
  background-color: #fff;
  border-radius: 10px;
  width: 100%;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #dee2e6;
  margin-bottom: 10px;
}

.modal-title {
  font-size: 1.25rem;
  font-weight: 600;
}

.modal-body {
  padding: 0;
}

.modal-footer {
  margin-top: 15px;
  display: flex;
  gap: 10px;
}

.modal-footer button {
  flex: 1;
}

.close {
  background: none;
  border: none;
  font-size: 1.5rem;
  line-height: 1;
  cursor: pointer;
}

@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-20%);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
