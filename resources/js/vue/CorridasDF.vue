<template>
  <div class="row margin-navbar">
    <div class="col-12">
      <!-- Header com controles -->
      <div class="card mx-4 mt-5">
        <div class="card-body">
          <div class="row">
            <!-- Título -->
            <div class="col-md-6">
              <h4>Corridas no DF</h4>
            </div>
            <!-- Controles -->
            <div class="col-md-6 d-flex justify-content-end">
              <!-- Busca -->
              <div class="input-group w-50">
                <span class="input-group-text"><i class="ti ti-search"></i></span>
                <input 
                  type="text" 
                  class="form-control" 
                  placeholder="Busca Livre"
                  v-model="searchTerm"
                  @input="debounceSearch"
                >
              </div>

              <!-- Toggle View -->
              <button 
                type="button" 
                class="btn btn-primary waves-effect waves-light mx-2" 
                @click="toggleView"
              >
                <span class="ti-xs ti ti-layout-grid me-1"></span>
                <span>{{ viewMode === 'table' ? 'Cards' : 'Tabela' }}</span>
              </button>

              <!-- Items per page -->
              <select 
                class="form-select w-auto d-inline-block"
                v-model="perPage"
                @change="changePerPage"
              >
                <option value="10">10</option>
                <option value="30">30</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>

              <!-- Total -->
              <button type="button" class="btn btn-primary waves-effect waves-light mx-2">
                Total
                <span class="badge bg-white text-primary badge-center ms-1">{{ totalItems }}</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="text-center my-5">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Carregando...</span>
        </div>
      </div>

      <!-- Visualização em Tabela -->
      <div v-else-if="viewMode === 'table'" class="card mx-4 mt-4 mb-5">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Nome</th>
                  <th>Distância</th>
                  <th>Local</th>
                  <th>Valor</th>
                  <th>Horário</th>
                  <th>Link</th>
                  <th>Data do Evento</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="corrida in corridas" :key="corrida.id">
                  <td>{{ corrida.nome }}</td>
                  <td>{{ corrida.distancia }}</td>
                  <td>{{ corrida.local }}</td>
                  <td>{{ corrida.valor || 'Não informado' }}</td>
                  <td>{{ corrida.horario }}</td>
                  <td><a :href="corrida.inscricao" target="_blank">Inscrição</a></td>
                  <td>{{ formatDate(corrida.data_evento) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Visualização em Cards -->
      <div v-else class="container-fluid mt-4">
        <div class="row">
          <div 
            v-for="corrida in corridas" 
            :key="corrida.id"
            class="col-md-6 col-lg-4 col-xl-3 mb-4"
          >
            <div class="card h-100 border-0">
              <div class="card-body">
                <img 
                  class="img-fluid rounded-3 mb-3"
                  :src="`/corrida/imagem/${corrida.id}`"
                  :alt="corrida.nome"
                  @error="handleImageError"
                />

                <component 
                  :is="getTitleComponent(corrida.nome)" 
                  class="mb-2"
                >
                  {{ corrida.nome }}
                </component>

                <!-- Localização -->
                <div v-if="corrida.local" class="mb-3">
                  <span class="timeline-indicator-advanced timeline-indicator-primary">
                    <i class="ti ti-map-pin"></i>
                  </span>
                  <div class="timeline-event ps-0 pb-0">
                    <div class="timeline-header">
                      <small class="text-primary text-uppercase fw-medium">Localização</small>
                    </div>
                    <h6 class="mb-0">{{ corrida.local }}</h6>
                    <p class="text-muted mb-0">{{ corrida.distancia }}</p>
                  </div>
                </div>

                <!-- Valor -->
                <div class="row mb-3 g-3">
                  <div class="d-flex">
                    <div class="badge bg-label-info me-3 rounded p-2">
                      <i class="ti ti-currency-dollar ti-sm"></i>
                    </div>
                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                      <div class="me-2">
                        <h6 class="mb-0 text-success">{{ corrida.valor || 'Não informado' }}</h6>
                        <small class="text-muted d-block mb-1">Valor</small>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Data e Horário -->
                <div class="row mb-3 g-3">
                  <div class="col-6">
                    <div class="d-flex">
                      <div class="avatar flex-shrink-0 me-2">
                        <span class="avatar-initial rounded bg-label-primary">
                          <i class="ti ti-calendar-event ti-md"></i>
                        </span>
                      </div>
                      <div>
                        <h6 class="mb-0 text-nowrap">{{ formatDateShort(corrida.data_evento) }}</h6>
                        <small>Date</small>
                      </div>
                    </div>
                  </div>

                  <div class="col-6">
                    <div class="d-flex">
                      <div class="avatar flex-shrink-0 me-2">
                        <span class="avatar-initial rounded bg-label-primary">
                          <i class="ti ti-clock ti-md"></i>
                        </span>
                      </div>
                      <div>
                        <h6 class="mb-0 text-nowrap">{{ corrida.horario }}</h6>
                        <small>Horário</small>
                      </div>
                    </div>
                  </div>
                </div>

                <a :href="corrida.inscricao" target="_blank" class="btn btn-primary w-100">
                  Inscrição
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Paginação -->
      <div v-if="!loading && lastPage > 1" class="card mx-4 mb-5">
        <div class="card-body">
          <nav aria-label="Navegação da página">
            <ul class="pagination justify-content-center">
              <li class="page-item" :class="{ disabled: currentPage === 1 }">
                <button class="page-link" @click="goToPage(currentPage - 1)" :disabled="currentPage === 1">
                  Anterior
                </button>
              </li>
              
              <li 
                v-for="page in visiblePages" 
                :key="page"
                class="page-item" 
                :class="{ active: page === currentPage }"
              >
                <button class="page-link" @click="goToPage(page)">
                  {{ page }}
                </button>
              </li>
              
              <li class="page-item" :class="{ disabled: currentPage === lastPage }">
                <button class="page-link" @click="goToPage(currentPage + 1)" :disabled="currentPage === lastPage">
                  Próxima
                </button>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'CorridasDF',
  data() {
    return {
      corridas: [],
      loading: false,
      searchTerm: '',
      viewMode: 'table',
      perPage: 10,
      currentPage: 1,
      lastPage: 1,
      totalItems: 0,
      searchTimeout: null
    }
  },
  computed: {
    visiblePages() {
      const delta = 2
      const range = []
      const rangeWithDots = []

      for (let i = Math.max(2, this.currentPage - delta);
           i <= Math.min(this.lastPage - 1, this.currentPage + delta);
           i++) {
        range.push(i)
      }

      if (this.currentPage - delta > 2) {
        rangeWithDots.push(1, '...')
      } else {
        rangeWithDots.push(1)
      }

      rangeWithDots.push(...range)

      if (this.currentPage + delta < this.lastPage - 1) {
        rangeWithDots.push('...', this.lastPage)
      } else {
        if (this.lastPage > 1) {
          rangeWithDots.push(this.lastPage)
        }
      }

      return rangeWithDots.filter(page => page !== '...' || rangeWithDots.length > 1)
    }
  },
  methods: {
    async fetchCorridas() {
      this.loading = true
      try {
        const response = await axios.get('/api/corridas-df', {
          params: {
            page: this.currentPage,
            per_page: this.perPage,
            search: this.searchTerm
          }
        })

        this.corridas = response.data.data
        this.currentPage = response.data.current_page
        this.lastPage = response.data.last_page
        this.totalItems = response.data.total
      } catch (error) {
        console.error('Erro ao carregar corridas:', error)
      } finally {
        this.loading = false
      }
    },
    debounceSearch() {
      if (this.searchTimeout) {
        clearTimeout(this.searchTimeout)
      }
      this.searchTimeout = setTimeout(() => {
        this.currentPage = 1
        this.fetchCorridas()
      }, 500)
    },
    toggleView() {
      this.viewMode = this.viewMode === 'table' ? 'card' : 'table'
    },
    changePerPage() {
      this.currentPage = 1
      this.fetchCorridas()
    },
    goToPage(page) {
      if (page >= 1 && page <= this.lastPage) {
        this.currentPage = page
        this.fetchCorridas()
      }
    },
    formatDate(date) {
      if (!date) return ''
      return new Date(date).toLocaleDateString('pt-BR')
    },
    formatDateShort(date) {
      if (!date) return ''
      return new Date(date).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: 'short',
        year: '2-digit'
      })
    },
    getTitleComponent(nome) {
      if (nome.length < 20) return 'h4'
      if (nome.length < 40) return 'h5'
      return 'h6'
    },
    handleImageError(event) {
      event.target.style.display = 'none'
    }
  },
  mounted() {
    this.fetchCorridas()
  }
}
</script>

<style scoped>
.margin-navbar {
  margin-top: 50px;
}

.card {
  border-radius: 0.5rem;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.timeline-indicator-advanced {
  display: inline-block;
  width: 0.75rem;
  height: 0.75rem;
  border-radius: 50%;
  margin-right: 0.5rem;
}

.timeline-indicator-primary {
  background-color: #696cff;
  color: white;
  font-size: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.avatar {
  width: 2.375rem;
  height: 2.375rem;
}

.avatar-initial {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%;
  border-radius: 0.375rem;
}

.bg-label-primary {
  background-color: rgba(105, 108, 255, 0.12) !important;
  color: #696cff !important;
}

.bg-label-info {
  background-color: rgba(3, 195, 236, 0.12) !important;
  color: #03c3ec !important;
}

.pagination .page-link {
  color: #696cff;
  border-color: #d9dee3;
}

.pagination .page-item.active .page-link {
  background-color: #696cff;
  border-color: #696cff;
}

.pagination .page-link:hover {
  color: #5f63f2;
  background-color: #f8f9fa;
  border-color: #d9dee3;
}
</style> 