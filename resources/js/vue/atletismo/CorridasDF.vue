<template>
  <div class="row margin-navbar">
    <div class="col-12">
      <!-- Header com controles -->
      <div class="card mx-4">
        <div class="card-body p-1">
          <div class="row">
            <!-- Título -->
            <div class="col-md-4 ms-3">
              <div class="d-flex align-items-center mt-2">
                <h4 class="d-flex align-items-center">
                  <i class="ti ti-run me-2 text-primary"></i>
                  Corridas DF e entorno
                </h4>

              </div>
            </div>
            <!-- Total -->
            <!-- <div class="col-md-2 d-flex align-items-center">
              <div class="badge bg-primary text-white fs-6 px-3 py-2">
                <i class="ti ti-trophy me-1"></i>
                {{ pagination.total }} corridas
              </div>
            </div> -->
            <!-- Controles -->
            <div class="col-md-6 d-flex justify-content-end align-items-center gap-2">
              <!-- Busca -->
              <div class="input-group" style="max-width: 250px;">
                <span class="input-group-text"><i class="ti ti-search"></i></span>
                <input 
                  type="text" 
                  class="form-control" 
                  placeholder="Busca Livre"
                  v-model="form.search"
                  @input="debounceSearch"
                >
              </div>

              <!-- Dropdown Items per page -->
              <div class="dropdown">
                <button 
                  class="btn btn-outline-secondary dropdown-toggle" 
                  type="button" 
                  data-bs-toggle="dropdown" 
                  aria-expanded="false"
                >
                  <i class="ti ti-list me-1"></i>
                  {{ form.per_page }} itens
                </button>
                <ul class="dropdown-menu">
                  <li>
                    <a 
                      class="dropdown-item" 
                      href="#" 
                      @click.prevent="changePerPage(10)"
                      :class="{ active: form.per_page == 10 }"
                    >
                      <i class="ti ti-circle-filled me-2" style="font-size: 6px;"></i>
                      10 itens
                    </a>
                  </li>
                  <li>
                    <a 
                      class="dropdown-item" 
                      href="#" 
                      @click.prevent="changePerPage(30)"
                      :class="{ active: form.per_page == 30 }"
                    >
                      <i class="ti ti-circle-filled me-2" style="font-size: 6px;"></i>
                      30 itens
                    </a>
                  </li>
                  <li>
                    <a 
                      class="dropdown-item" 
                      href="#" 
                      @click.prevent="changePerPage(50)"
                      :class="{ active: form.per_page == 50 }"
                    >
                      <i class="ti ti-circle-filled me-2" style="font-size: 6px;"></i>
                      50 itens
                    </a>
                  </li>
                  <li>
                    <a 
                      class="dropdown-item" 
                      href="#" 
                      @click.prevent="changePerPage(100)"
                      :class="{ active: form.per_page == 100 }"
                    >
                      <i class="ti ti-circle-filled me-2" style="font-size: 6px;"></i>
                      100 itens
                    </a>
                  </li>
                </ul>
              </div>

              <!-- Toggle View -->
              <div class="btn-group view-toggle" role="group">
                <input 
                  type="radio" 
                  class="btn-check" 
                  id="tableView" 
                  :checked="viewMode === 'table'"
                  @change="viewMode = 'table'"
                >
                <label class="btn btn-outline-primary" for="tableView">
                  <i class="ti ti-table me-1"></i>
                  Tabela
                </label>
                
                <input 
                  type="radio" 
                  class="btn-check" 
                  id="cardView" 
                  :checked="viewMode === 'card'"
                  @change="viewMode = 'card'"
                >
                <label class="btn btn-outline-primary" for="cardView">
                  <i class="ti ti-layout-grid me-1"></i>
                  Card
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Badge de total de resultados -->
      <div v-if="corridas.length > 0" class="mx-4 mb-2">
        <div class="d-flex justify-content-end">
          <small class="text-muted">{{ pagination.total }} corridas encontradas</small>
        </div>
      </div>

      <!-- Tela de Nenhum Resultado -->
      <div v-if="corridas.length === 0" class="card mx-4 mt-4 mb-5">
        <div class="card-body p-5">
          <div class="text-center">
            <div class="mb-4">
              <i class="ti ti-search-off text-muted" style="font-size: 5rem;"></i>
            </div>
            <h4 class="mb-3 text-muted">Nenhum resultado encontrado</h4>
            <p class="text-muted mb-4">
              Não foram encontrados resultados para essa pesquisa.<br>
              Tente usar termos diferentes ou limpe os filtros.
            </p>
            <button 
              v-if="form.search" 
              @click="clearSearch" 
              class="btn btn-outline-primary"
            >
              <i class="ti ti-x me-1"></i>
              Limpar pesquisa
            </button>
          </div>
        </div>
      </div>

      <!-- Visualização em Tabela -->
      <div v-else-if="viewMode === 'table'" class="card mx-4 mt-4 mb-5">
        <div class="card-body p-3">
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
              <div class="card-body p-3">
                <img 
                  class="img-fluid rounded-3 mb-3"
                  :src="`/corrida/imagem?codigo=corridas_df&id=${corrida.id}`"
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
      <div v-if="pagination.last_page > 1" class="card mx-4 mb-5">
        <div class="card-body p-2">
          <nav aria-label="Navegação da página">
            <ul class="pagination justify-content-center">
              <li class="page-item" :class="{ disabled: pagination.current_page === 1 }">
                <button class="page-link" @click="goToPage(pagination.current_page - 1)" :disabled="pagination.current_page === 1">
                  Anterior
                </button>
              </li>
              
              <li 
                v-for="page in visiblePages" 
                :key="page"
                class="page-item" 
                :class="{ active: page === pagination.current_page }"
              >
                <button class="page-link" @click="goToPage(page)">
                  {{ page }}
                </button>
              </li>
              
              <li class="page-item" :class="{ disabled: pagination.current_page === pagination.last_page }">
                <button class="page-link" @click="goToPage(pagination.current_page + 1)" :disabled="pagination.current_page === pagination.last_page">
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
import { router } from '@inertiajs/vue3'

export default {
  name: 'CorridasDF',
  props: {
    corridas: {
      type: Array,
      default: () => []
    },
    pagination: {
      type: Object,
      default: () => ({})
    },
    filters: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      viewMode: 'table',
      searchTimeout: null,
      form: {
        search: this.filters.search || '',
        per_page: this.filters.per_page || 10,
        page: this.pagination.current_page || 1
      }
    }
  },
  computed: {
    visiblePages() {
      const delta = 2
      const range = []
      const rangeWithDots = []

      for (let i = Math.max(2, this.pagination.current_page - delta);
           i <= Math.min(this.pagination.last_page - 1, this.pagination.current_page + delta);
           i++) {
        range.push(i)
      }

      if (this.pagination.current_page - delta > 2) {
        rangeWithDots.push(1, '...')
      } else {
        rangeWithDots.push(1)
      }

      rangeWithDots.push(...range)

      if (this.pagination.current_page + delta < this.pagination.last_page - 1) {
        rangeWithDots.push('...', this.pagination.last_page)
      } else {
        if (this.pagination.last_page > 1) {
          rangeWithDots.push(this.pagination.last_page)
        }
      }

      return rangeWithDots.filter(page => page !== '...' || rangeWithDots.length > 1)
    }
  },
  methods: {
    // *** DIFERENÇA PRINCIPAL: Usa router.visit ao invés de router.get ***
    updateFilters() {
      console.log('updateFilters chamado com:', this.form)
      this.form.page = 1 // Reset para primeira página
      router.visit('/corridasDF', {
        method: 'post',
        data: this.form,
        only: ['corridas', 'pagination'],
        preserveState: true,
        preserveScroll: true,
        onSuccess: (page) => {
          console.log('Sucesso na navegação:', page)
        },
        onError: (errors) => {
          console.error('Erro na navegação:', errors)
        }
      })
    },
    debounceSearch() {
      console.log('debounceSearch chamado, termo:', this.form.search)
      if (this.searchTimeout) {
        clearTimeout(this.searchTimeout)
      }
      this.searchTimeout = setTimeout(() => {
        console.log('Executando busca após delay')
        this.updateFilters()
      }, 500)
    },
    goToPage(page) {
      console.log('goToPage chamado para página:', page)
      if (page >= 1 && page <= this.pagination.last_page) {
        this.form.page = page
        router.visit('/corridasDF', {
          method: 'post',
          data: this.form,
          only: ['corridas', 'pagination'],
          preserveState: true,
          preserveScroll: true,
          onSuccess: (pageData) => {
            console.log('Paginação bem-sucedida:', pageData)
          },
          onError: (errors) => {
            console.error('Erro na paginação:', errors)
          }
        })
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
    },
    changePerPage(perPage) {
      this.form.per_page = perPage
      this.updateFilters()
    },
    clearSearch() {
      this.form.search = ''
      this.updateFilters()
    }
  },
  mounted() {
    console.log('Componente montado!')
    console.log('Props recebidas:')
    console.log('- corridas:', this.corridas)
    console.log('- pagination:', this.pagination)
    console.log('- filters:', this.filters)
    console.log('Form atual:', this.form)
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

/* Toggle View Switch */
.view-toggle .btn {
  min-width: 100px;
  font-weight: 500;
}

.view-toggle .btn-check:checked + .btn {
  background-color: #696cff;
  border-color: #696cff;
  color: white;
}

.view-toggle .btn:hover {
  background-color: rgba(105, 108, 255, 0.1);
  border-color: #696cff;
  color: #696cff;
}

.view-toggle .btn-check:checked + .btn:hover {
  background-color: #5f63f2;
  border-color: #5f63f2;
  color: white;
}
</style> 