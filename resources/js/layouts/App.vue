<template>
    <div class="c-app">
      <div class="c-sidebar c-sidebar-dark c-sidebar-fixed" :class="{ 'c-sidebar-lg-show': !$page.url.startsWith('/apps/transactions') }" id="sidebar">
        <div class="c-sidebar-brand d-lg-down-none" style="background: #42b883;">
          <img src="/images/vue1.png" class="vue-3d-spin" width="100px"> <span class="ml-2 font-weight-bold">CAFE-MAMI</span>
        </div>
  
        <!-- sidebar -->
        <Sidebar />
        <!-- end sidebar -->
  
      </div>
      <div class="c-wrapper c-fixed-components">
        
        <!-- header -->
        <Header />
        <!-- end header -->
  
        <div class="c-body">
  
          <!-- content -->
          <slot />
          <!-- end content -->
  
          <footer class="c-footer">
            <div><strong>CAFE-MAMI</strong> &copy; 2025 - All rights reserved</div>
          </footer>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  
    import Header from '../Components/Header.vue'
    import Sidebar from '../Components/Sidebar.vue'
  
    export default {
  
      //register components
      components: {
        Header,
        Sidebar
      }
  
    }
  </script>
  
  <style>
    .vue-3d-spin {
    transform-style: preserve-3d;
    animation: spin3d 3s linear infinite;
    display: inline-block;
    perspective: 1000px;
  }

  @keyframes spin3d {
    from {
      transform: rotateY(0deg);
    }
    to {
      transform: rotateY(360deg);
    }
  }
  </style>