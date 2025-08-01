<template>
    <div class="row margin-navbar">
      <div class="col-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">
              <i class="ti ti-test-pipe me-2"></i>
              Teste Vue.js com Inertia.js
            </h4>
          </div>
          <div class="card-body">
            <div class="row justify-content-center">
              <div class="col-md-8 text-center">
                <div class="alert alert-primary" role="alert">
                  <h1 class="alert-heading mb-3">{{ mensagem }}</h1>
                  <p class="mb-0">
                    Esta é uma página Vue.js integrada com Laravel usando Inertia.js e o template Vuexy
                  </p>
                </div>
                
                <div class="mt-4">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                          <i class="ti ti-brand-vue ti-3x mb-3"></i>
                          <h5>Vue.js</h5>
                          <p class="mb-0">Framework Frontend</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="card bg-success text-white">
                        <div class="card-body text-center">
                          <i class="ti ti-brand-laravel ti-3x mb-3"></i>
                          <h5>Laravel</h5>
                          <p class="mb-0">Framework Backend</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="card bg-info text-white">
                        <div class="card-body text-center">
                          <i class="ti ti-arrows-exchange ti-3x mb-3"></i>
                          <h5>Inertia.js</h5>
                          <p class="mb-0">Integração SPA</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'Teste',
    props: {
      mensagem: {
        type: String,
        required: true
      }
    }
  }
  </script>
  
  <style scoped>
  .card {
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
  }
  
  .alert {
    border-radius: 0.5rem;
  }
  
  .alert-heading {
    font-size: 2.5rem;
    font-weight: 600;
  }
  
  .ti-3x {
    font-size: 3rem;
  }
  
   .margin-navbar{
    margin-top: 50px;
  }
  </style> 
  
  