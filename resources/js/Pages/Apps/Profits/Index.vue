<template>
  <Head>
    <title>Report Profits - Aplikasi Kasir</title>
  </Head>
  <main class="c-main">
    <div class="container-fluid">
      <div class="fade-in">
        <div class="row">
          <div class="col-md-12">
            <div class="card border-0 rounded-3 shadow border-top-purple">
              <div class="card-header">
                <span class="font-weight-bold"><i class="fa fa-chart-line"></i> REPORT PROFITS</span>
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

                <div v-if="profits">
                  <hr />
                  <div class="export text-end mb-3">
                    <a
                      :href="`/apps/profits/export?start_date=${start_date}&end_date=${end_date}`"
                      target="_blank"
                      class="btn btn-success btn-md border-0 shadow me-3"
                    >
                      <i class="fa fa-file-excel"></i> EXCEL
                    </a>
                    <a
                      :href="`/apps/profits/pdf?start_date=${start_date}&end_date=${end_date}`"
                      target="_blank"
                      class="btn btn-secondary btn-md border-0 shadow"
                    >
                      <i class="fa fa-file-pdf"></i> PDF
                    </a>
                  </div>

                  <!-- Scrollable Table -->
                  <div class="table-scroll-wrapper">
                    <button class="scroll-btn left" @click="scrollTable('left')">&#8592;</button>

                    <div class="table-responsive" ref="tableScroll">
                      <table class="table table-bordered">
                        <thead>
                          <tr style="background-color: #e6e6e7;">
                            <th scope="col">Date</th>
                            <th scope="col">Invoice</th>
                            <th scope="col">Total</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="profit in profits" :key="profit.id">
                            <td>{{ profit.created_at }}</td>
                            <td class="text-center">{{ profit.transaction.invoice }}</td>
                            <td class="text-end">Rp. {{ formatPrice(profit.total) }}</td>
                          </tr>
                          <tr>
                            <td colspan="2" class="text-end fw-bold" style="background-color: #e6e6e7;">TOTAL</td>
                            <td class="text-end fw-bold" style="background-color: #e6e6e7;">Rp. {{ formatPrice(total) }}</td>
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
</template>

<script>
import LayoutApp from '../../../layouts/App.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

export default {
  layout: LayoutApp,
  components: {
    Head,
    Link
  },
  props: {
    errors: Object,
    profits: Array,
    total: Number
  },
  setup() {
    const start_date = ref('' || new URL(document.location).searchParams.get('start_date'));
    const end_date = ref('' || new URL(document.location).searchParams.get('end_date'));
    const tableScroll = ref(null);

    const filter = () => {
      router.get('/apps/profits/filter', {
        start_date: start_date.value,
        end_date: end_date.value,
      });
    };

    const scrollTable = (direction) => {
      const el = tableScroll.value;
      if (!el) return;
      const amount = 200;
      el.scrollLeft += direction === 'right' ? amount : -amount;
    };

    const formatPrice = (value) => {
      return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    };

    return {
      start_date,
      end_date,
      filter,
      tableScroll,
      scrollTable,
      formatPrice
    };
  }
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
  background: #6f42c1;
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
</style>
