<template>
    <Head>
        <title>Categories - CAFE-MAMI</title>
        <link rel="icon" href="/images/vue2.png" type="image/png">
    </Head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 rounded-3 shadow border-top-purple">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-folder"></i> CATEGORIES</span>
                            </div>
                            <div class="card-body">
                                <form @submit.prevent="handleSearch">
                                    <div class="input-group mb-3">
                                        <Link href="/apps/categories/create" v-if="hasAnyPermission(['categories.create'])" class="btn btn-primary input-group-text">
                                            <i class="fa fa-plus-circle me-2"></i> NEW
                                        </Link>
                                        <input type="text" class="form-control" v-model="search" placeholder="search by category name...">
                                        <button class="btn btn-primary input-group-text" type="submit">
                                            <i class="fa fa-search me-2"></i> SEARCH
                                        </button>
                                    </div>
                                </form>

                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Name</th>
                                            <th scope="col">Image</th>
                                            <th scope="col" style="width:20%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(category, index) in categories.data" :key="index">
                                            <td>{{ category.name }}</td>
                                            <td class="text-center">
                                                <img :src="category.image" width="40" style="cursor: pointer;" @click="openImageModal(category.image)" />
                                            </td>
                                            <td class="text-center">
                                                <Link :href="`/apps/categories/${category.id}/edit`" v-if="hasAnyPermission(['categories.edit'])" class="btn btn-success btn-sm me-2">
                                                    <i class="fa fa-pencil-alt me-1"></i> EDIT
                                                </Link>
                                                <button @click.prevent="destroy(category.id)" v-if="hasAnyPermission(['categories.delete'])" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-trash"></i> DELETE
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <Pagination :links="categories.links" align="end" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Gambar -->
        <div v-if="showModal" class="image-modal" @click.self="closeModal">
            <div class="image-modal-content">
                <img :src="selectedImage" alt="Preview" />
                <button class="close-btn" @click="closeModal">&times;</button>
            </div>
        </div>
    </main>
</template>

<script>
import LayoutApp from '../../../Layouts/App.vue';
import Pagination from '../../../Components/Pagination.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import Swal from 'sweetalert2';

export default {
    layout: LayoutApp,
    components: {
        Head,
        Link,
        Pagination
    },
    props: {
        categories: Object,
    },
    setup() {
        const search = ref('' || (new URL(document.location)).searchParams.get('q'));
        const selectedImage = ref(null);
        const showModal = ref(false);

        const handleSearch = () => {
            router.get('/apps/categories', {
                q: search.value,
            });
        }

        const openImageModal = (imageUrl) => {
            selectedImage.value = imageUrl;
            showModal.value = true;
        }

        const closeModal = () => {
            selectedImage.value = null;
            showModal.value = false;
        }

        const destroy = (id) => {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    router.delete(`/apps/categories/${id}`);
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'Category deleted successfully.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                    });
                }
            });
        }

        watch(search, (newVal) => {
            if (newVal === '') {
                router.get('/apps/categories', {}, {
                    preserveState: true,
                    preserveScroll: true
                });
            }
        });

        return {
            search,
            handleSearch,
            destroy,
            selectedImage,
            showModal,
            openImageModal,
            closeModal
        }
    }
}
</script>

<style scoped>
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
    max-width: 90%;
    max-height: 90%;
}

.image-modal-content img {
    max-width: 100%;
    max-height: 100%;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
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
    line-height: 30px;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}
</style>
