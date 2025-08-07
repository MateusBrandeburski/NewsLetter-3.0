<template>
  <div class="row margin-navbar">
    <div class="col-12">
      <!-- Header com controles -->
      <div class="card mx-4">
        <div class="card-body p-3">
          <div class="d-flex justify-content-between align-items-center flex-wrap">
            <!-- Título -->
            <div>
              <h4 class="d-flex align-items-center mb-0 ms-3">
                <!-- <i class="ti ti-run me-2 text-primary"></i> -->
                Corridas DF e Entorno
              </h4>
              <small class="text-muted ms-4">{{ pagination.total }} corridas ainda para acontecer</small>
            </div>

            <!-- Controles -->
            <div class="d-flex align-items-center gap-2 flex-shrink-0">
              <!-- Busca -->
              <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text"><i class="ti ti-search"></i></span>
                <input 
                  type="text" 
                  class="form-control" 
                  placeholder="Buscar..."
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
                  aria-expanded="false">
                  <i class="ti ti-list me-1"></i>
                  {{ form.per_page }} itens
                </button>
                <ul class="dropdown-menu">
                  <li>
                    <a 
                      class="dropdown-item" 
                      href="#" 
                      @click.prevent="changePerPage(10)"
                      :class="{ active: form.per_page == 10 }">
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

              <!-- Ordenação -->
              <button 
                class="btn btn-outline-secondary d-flex align-items-center" 
                @click="toggleSort"
                :title="'Ordenar por data do evento: ' + (form.sort_order === 'asc' ? 'Mais antigas primeiro' : 'Mais recentes primeiro')"
                style="min-width: 120px; height: 38px;"
              >
                <i class="ti ti-calendar me-1"></i>
                <i :class="form.sort_order === 'asc' ? 'ti ti-sort-ascending' : 'ti ti-sort-descending'"></i>
              </button>

              <!-- Toggle View -->
              <div class="btn-group view-toggle" role="group">
                <input 
                  type="radio" 
                  class="btn-check" 
                  id="tableView" 
                  :checked="viewMode === 'table'"
                  @change="viewMode = 'table'"
                >
                <label class="btn btn-outline-secondary" for="tableView">
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
                <label class="btn btn-outline-secondary" for="cardView">
                  <i class="ti ti-layout-grid me-1"></i>
                  Card
                </label>
              </div>
            </div>
          </div>
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
              class="btn btn-outline-primary">
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
      <div v-else class="container-fluid mt-4" style="padding-left: 1rem; padding-right: 1rem;">
        <div class="row">
          <div 
            v-for="corrida in corridas" 
            :key="corrida.id"
            class="col-md-6 col-lg-4 col-xl-3 mb-4"
          >
            <div class="card border-0" style="height: 500px;">
              <div class="card-body p-3 d-flex flex-column">
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
                  <div class="timeline-event">
                    <div class="timeline-header">
                      <small class="text-primary text-uppercase fw-medium">
                        <i class="ti ti-map-pin mb-1"></i>
                        Localização
                      </small>
                    </div>
                    <h6 class="mb-0">{{ corrida.local }}</h6>
                    <p class="text-muted mb-0">{{ corrida.distancia }}</p>
                  </div>
                </div>

                <!-- Valor -->
                 <div class="d-flex align-items-center">
                    <div class="badge bg-label-info me-3 rounded p-2">
                          <i class="ti ti-currency-dollar ti-sm"></i>
                    </div>
                    <div class="row mb-3 g-3">
                      <div class="d-flex">
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                          <div class="me-2">
                            <h6 
                              class="mb-0 text-success text-truncate-custom" 
                              :title="(corrida.valor || 'Não informado').length > 60 ? (corrida.valor || 'Não informado') : null"
                            >
                              {{ truncateValue(corrida.valor || 'Não informado') }}
                            </h6>
                          </div>
                        </div>
                      </div>
                  </div>
                </div>

                <!-- Data e Horário -->
                <div class="row mb-3 g-3 mt-1">
                  <div class="col-6">
                    <div class="d-flex">
                      <div class="avatar flex-shrink-0 me-2">
                        <span class="avatar-initial rounded bg-label-primary">
                          <i class="ti ti-calendar-event ti-md"></i>
                        </span>
                      </div>
                      <div>
                        <h6 class="mb-0">{{ formatDateShort(corrida.data_evento) }}</h6>
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

                <!-- Espaçador para empurrar o botão para o final -->
                <div class="flex-grow-1"></div>

                <a :href="corrida.inscricao" target="_blank" class="btn btn-primary w-100 mt-auto">
                  Inscrição
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Paginação -->
      <div v-if="pagination.last_page > 1" class="d-flex align-items-center justify-content-center mt-4 mb-5 mx-4">
        <button 
          class="btn btn-sm btn-outline-secondary me-2" 
          @click="goToPage(pagination.current_page - 1)" 
          :disabled="pagination.current_page === 1"
        >
          <i class="ti ti-chevron-left"></i>
        </button>
        
        <template v-for="page in visiblePages" :key="page">
          <button
            v-if="page !== '...'"
            class="btn btn-sm me-1"
            :class="page === pagination.current_page ? 'btn-primary' : 'btn-outline-secondary'"
            @click="goToPage(page)"
          >
            {{ page }}
          </button>
          <span v-else class="px-2 text-muted">...</span>
        </template>
        
        <button 
          class="btn btn-sm btn-outline-secondary ms-2" 
          @click="goToPage(pagination.current_page + 1)" 
          :disabled="pagination.current_page === pagination.last_page"
        >
          <i class="ti ti-chevron-right"></i>
        </button>
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
      viewMode: 'card',
      searchTimeout: null,
      form: {
        search: this.filters.search || '',
        per_page: this.filters.per_page || 10,
        page: this.pagination.current_page || 1,
        sort_order: this.filters.sort_order || 'desc'
      }
    }
  },
  computed: {
    visiblePages() {
      const current = this.pagination.current_page
      const total = this.pagination.last_page
      const pages = []
      
      // Sempre mostrar página 1
      if (total > 0) pages.push(1)
      
      // Se tem muitas páginas, mostrar ... e páginas próximas
      if (current > 3) pages.push('...')
      
      // Mostrar páginas ao redor da atual
      for (let i = Math.max(2, current - 1); i <= Math.min(total - 1, current + 1); i++) {
        if (!pages.includes(i)) pages.push(i)
      }
      
      // Se não é a última página e tem espaço, mostrar ... e última
      if (current < total - 2) pages.push('...')
      if (total > 1 && !pages.includes(total)) pages.push(total)
      
      return pages
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
    },
    toggleSort() {
      this.form.sort_order = this.form.sort_order === 'asc' ? 'desc' : 'asc'
      this.updateFilters()
    },
    truncateValue(value) {
      if (value.length <= 60) return value
      return value.substring(0, 60) + '...'
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

.text-truncate-custom {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
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