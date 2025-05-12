<template>
  <Head>
    <title>Products - CAFE-MAMI</title>
    <link rel="icon" href="/images/vue2.png" type="image/png" />
  </Head>

  <main class="c-main">
    <div class="container-fluid">
      <div class="fade-in">
        <div class="row">
          <div class="col-md-12">
            <div class="card border-0 rounded-3 shadow border-top-purple">
              <div class="card-header">
                <span class="font-weight-bold">
                  <i class="fa fa-shopping-bag"></i> PRODUCTS
                </span>
              </div>
              <div class="card-body">
                <form @submit.prevent="handleSearch">
                  <div class="input-group mb-3">
                    <Link
                      href="/apps/products/create"
                      v-if="hasAnyPermission(['products.create'])"
                      class="btn btn-primary input-group-text"
                    >
                      <i class="fa fa-plus-circle me-2"></i> NEW
                    </Link>
                    <input
                      type="text"
                      class="form-control"
                      v-model="search"
                      placeholder="search by product title..."
                    />
                    <button class="btn btn-primary input-group-text" type="submit">
                      <i class="fa fa-search me-2"></i> SEARCH
                    </button>
                  </div>
                </form>

                <!-- Scrollable Table + Buttons -->
                <div class="table-scroll-wrapper">
                  <button class="scroll-btn left" @click="scrollTable('left')">&#8592;</button>

                  <div class="table-responsive" ref="tableScroll">
                    <table class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th scope="col">Barcode</th>
                          <th scope="col">Title</th>
                          <th scope="col">Buy Price</th>
                          <th scope="col">Sell Price</th>
                          <th scope="col">Stock</th>
                          <th scope="col" style="width:20%">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(product, index) in products.data" :key="index">
                          <td
                            class="text-center"
                            @click="openBarcodeModal(product.barcode)"
                            style="cursor: pointer"
                          >
                            <Barcode
                              :value="product.barcode"
                              format="CODE39"
                              lineColor="#000"
                              :width="1"
                              :height="20"
                            />
                          </td>
                          <td>{{ product.title }}</td>
                          <td>Rp. {{ formatPrice(product.buy_price) }}</td>
                          <td>Rp. {{ formatPrice(product.sell_price) }}</td>
                          <td>{{ product.stock }}</td>
                          <td class="text-center">
                            <Link
                              :href="`/apps/products/${product.id}/edit`"
                              v-if="hasAnyPermission(['products.edit'])"
                              class="btn btn-success btn-sm me-2"
                            >
                              <i class="fa fa-pencil-alt me-1"></i> EDIT
                            </Link>
                            <button
                              @click.prevent="destroy(product.id)"
                              v-if="hasAnyPermission(['products.delete'])"
                              class="btn btn-danger btn-sm"
                            >
                              <i class="fa fa-trash"></i> DELETE
                            </button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <button class="scroll-btn right" @click="scrollTable('right')">&#8594;</button>
                </div>

                <Pagination :links="products.links" align="end" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Barcode Modal -->
    <div v-if="showBarcodeModal" class="image-modal" @click.self="closeBarcodeModal">
      <div class="image-modal-content">
        <div id="barcode-to-image">
          <Barcode
            :value="selectedBarcode"
            format="CODE39"
            lineColor="#000"
            :width="2"
            :height="80"
          />
        </div>
        <div style="margin-top: 15px;">
          <button class="btn btn-success" @click="downloadBarcodeImage">
            ⬇ Download Barcode
          </button>
        </div>
        <button class="close-btn" @click="closeBarcodeModal">&times;</button>
      </div>
    </div>
  </main>
</template>

<script>
import LayoutApp from "../../../layouts/App.vue";
import Pagination from "../../../Components/Pagination.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import Swal from "sweetalert2";
import Barcode from "../../../Components/Barcode.vue";
import * as htmlToImage from 'html-to-image';

export default {
  layout: LayoutApp,
  components: {
    Head,
    Link,
    Pagination,
    Barcode,
  },
  props: {
    products: Object,
  },
  setup() {
    const search = ref((new URL(document.location)).searchParams.get("q") || "");
    const showBarcodeModal = ref(false);
    const selectedBarcode = ref(null);
    const tableScroll = ref(null);

    const openBarcodeModal = (barcode) => {
      selectedBarcode.value = barcode;
      showBarcodeModal.value = true;
    };

    const closeBarcodeModal = () => {
      selectedBarcode.value = null;
      showBarcodeModal.value = false;
    };

    const handleSearch = () => {
      router.get("/apps/products", {
        q: search.value,
      });
    };

    const destroy = (id) => {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.isConfirmed) {
          router.delete(`/apps/products/${id}`, {
            onSuccess: () => {
              Swal.fire({
                title: "Deleted!",
                text: "Product deleted successfully.",
                icon: "success",
                timer: 2000,
                showConfirmButton: false,
              }).then(() => {
                location.reload();
              });
            },
          });
        }
      });
    };

    const scrollTable = (direction) => {
      const el = tableScroll.value;
      if (!el) return;
      const amount = 200;
      el.scrollLeft += direction === "right" ? amount : -amount;
    };

    const downloadBarcodeImage = () => {
  Swal.fire({
    title: 'Download Barcode?',
    text: 'Apakah kamu ingin mengunduh barcode ini?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya, Unduh!',
    cancelButtonText: 'Batal',
  }).then((result) => {
    if (result.isConfirmed) {
      const node = document.getElementById('barcode-to-image');
      if (!node) return;

      htmlToImage.toPng(node)
        .then((dataUrl) => {
          const link = document.createElement('a');
          link.download = `barcode-${selectedBarcode.value}.png`;
          link.href = dataUrl;
          link.click();
        })
        .catch((error) => {
          console.error('Gagal menyimpan barcode:', error);
          Swal.fire('Error', 'Gagal menyimpan barcode!', 'error');
        });
    }
  });
};


    watch(search, (newVal) => {
      if (newVal === "") {
        router.get("/apps/products", {}, {
          preserveState: true,
          preserveScroll: true,
        });
      }
    });

    return {
      search,
      handleSearch,
      destroy,
      showBarcodeModal,
      selectedBarcode,
      openBarcodeModal,
      closeBarcodeModal,
      tableScroll,
      scrollTable,
      downloadBarcodeImage,
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

.table-responsive table {
  min-width: 768px;
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

.image-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.75);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1050;
}

.image-modal-content {
  position: relative;
  padding: 20px;
  background: white;
  border-radius: 10px;
  max-width: 80%;
  max-height: 80%;
  text-align: center;
}

.close-btn {
  position: absolute;
  top: -10px;
  right: -10px;
  background: white;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  border-radius: 50%;
  width: 30px;
  height: 30px;
  text-align: center;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}
</style>
