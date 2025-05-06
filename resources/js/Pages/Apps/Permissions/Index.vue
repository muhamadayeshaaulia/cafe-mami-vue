<template>
    <Head>
        <title>Permissions - CAFE-MAMI</title>
        <link rel="icon" href="/images/vue2.png" type="image/png">
    </Head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 rounded-3 shadow border-top-purple">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-key"></i> PERMISSIONS</span>
                            </div>
                            <div class="card-body">
                                <form @submit.prevent="handleSearch">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" v-model="search" placeholder="search by permission name...">
                                        <button class="btn btn-primary input-group-text" type="submit"> <i class="fa fa-search me-2"></i> SEARCH</button>
                                    </div>
                                </form>
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Permission Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(permission, index) in permissions.data" :key="index">
                                            <td>{{ permission.name }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <Pagination :links="permissions.links" align="end"/>
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
    import Pagination from '../../../Components/Pagination.vue';
    import { Head, Link, router } from '@inertiajs/vue3';
    import { ref, watch } from 'vue';
   

    export default {
        layout: LayoutApp,
        components: {
            Head,
            Link,
            Pagination
        },
        props: {
            permissions: Object,
        },

        setup() {
            const search = ref('' || (new URL(document.location)).searchParams.get('q'));
            const handleSearch = () => {
                router.get('/apps/permissions', {
                    

                    q: search.value,
                });
            }

        watch(search, (newVal) => {
            if (newVal === '') {
                router.get('/apps/permissions', {}, {
                    preserveState: true,
                    preserveScroll: true
                });
            }
        });
            return {
                search,
                handleSearch,
            }

        }
    }
</script>

<style>

</style>