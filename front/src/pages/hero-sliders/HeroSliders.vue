<template>
  <q-page class="q-pa-xs">
    <q-card flat bordered class="q-mb-xs">
      <q-card-section class="row items-center q-col-gutter-sm">
        <div class="col-auto">
          <div class="text-h6 text-title">Hero Sliders</div>
          <div class="text-caption text-grey-7">Gestion del carrusel principal de la web y sus libros destacados</div>
        </div>
        <q-space />
        <div class="col-12 col-md-auto">
          <div class="row items-center q-gutter-sm justify-end">
            <q-input
              v-model="filter"
              label="Buscar"
              dense
              outlined
              debounce="350"
              style="width: 220px"
              @update:model-value="onSearch"
            >
              <template #append><q-icon name="search" /></template>
            </q-input>

            <q-select
              v-model="filterPublicadoWeb"
              label="Web"
              dense
              outlined
              clearable
              style="width: 130px"
              :options="estadoWebOptions"
              emit-value
              map-options
              @update:model-value="onSearch"
            />

            <q-btn color="positive" label="Nuevo" no-caps icon="add_circle_outline" :loading="loading" @click="sliderNuevo" />
            <q-btn color="primary" label="Actualizar" no-caps icon="refresh" :loading="loading" @click="heroSlidersGet" />
          </div>
        </div>
      </q-card-section>
    </q-card>

    <QMajetable
      :rows="heroSliders"
      :columns="columns"
      :pagination="pagination"
      no-data-label="Sin registros"
      @request="onRequest"
    >
      <template #body-cell-actions="{ row }">
        <q-btn-dropdown label="Opciones" no-caps size="10px" dense color="primary">
          <q-list>
            <q-item clickable v-close-popup @click="sliderEditar(row)">
              <q-item-section avatar><q-icon name="edit" /></q-item-section>
              <q-item-section><q-item-label>Editar</q-item-label></q-item-section>
            </q-item>
            <q-item clickable v-close-popup @click="sliderEliminar(row.id)">
              <q-item-section avatar><q-icon name="delete" /></q-item-section>
              <q-item-section><q-item-label>Eliminar</q-item-label></q-item-section>
            </q-item>
          </q-list>
        </q-btn-dropdown>
      </template>

      <template #body-cell-theme="{ row }">
        <q-chip dense color="primary" text-color="white">{{ labelTheme(row.theme) }}</q-chip>
      </template>

      <template #body-cell-publicado_web="{ row }">
        <q-chip dense :color="row.publicado_web ? 'positive' : 'grey-6'" text-color="white">
          {{ row.publicado_web ? 'Publicado' : 'Oculto' }}
        </q-chip>
      </template>

      <template #body-cell-libros="{ row }">
        <div class="column q-gutter-xs">
          <span>{{ row.libro_principal?.titulo || '-' }}</span>
          <span class="text-caption text-grey-7">{{ row.libro_secundario?.titulo || '-' }}</span>
          <span class="text-caption text-grey-7">{{ row.libro_terciario?.titulo || '-' }}</span>
        </div>
      </template>
    </QMajetable>

    <q-dialog v-model="sliderDialog" persistent>
      <q-card style="width: 980px; max-width: 96vw">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-subtitle1 text-weight-bold">
            {{ slider.id ? 'Editar hero slider' : 'Nuevo hero slider' }}
          </div>
          <q-space />
          <q-btn icon="close" flat round dense @click="cerrarDialogo" />
        </q-card-section>

        <q-card-section class="q-pt-xs q-px-sm q-pb-sm">
          <q-form @submit.prevent="slider.id ? sliderPut() : sliderPost()">
            <div class="row q-col-gutter-sm">
              <div class="col-12 col-md-8">
                <div class="row q-col-gutter-xs">
                  <div class="col-12 col-md-4">
                    <q-input v-model="slider.eyebrow" label="Eyebrow" dense outlined :rules="[req]" class="q-mb-xs" />
                  </div>

                  <div class="col-12 col-md-8">
                    <q-input v-model="slider.title" label="Titulo principal" dense outlined :rules="[req]" class="q-mb-xs" />
                  </div>

                  <div class="col-12">
                    <q-input
                      v-model="slider.description"
                      label="Descripcion"
                      dense
                      outlined
                      type="textarea"
                      autogrow
                      input-style="min-height: 78px"
                      :rules="[req]"
                      class="q-mb-xs"
                    />
                  </div>

                  <div class="col-12 col-md-5">
                    <q-input v-model="slider.badge" label="Badge" dense outlined class="q-mb-xs" />
                  </div>

                  <div class="col-12 col-md-3">
                    <q-select
                      v-model="slider.theme"
                      label="Tema visual"
                      dense
                      outlined
                      emit-value
                      map-options
                      :options="themeOptions"
                      :rules="[req]"
                      class="q-mb-xs"
                    />
                  </div>

                  <div class="col-12 col-md-2">
                    <q-input v-model.number="slider.orden" label="Orden" dense outlined type="number" min="0" class="q-mb-xs" />
                  </div>

                  <div class="col-12 col-md-2">
                    <q-toggle
                      v-model="slider.publicado_web"
                      color="positive"
                      label="Publicado"
                      left-label
                      dense
                      class="q-mt-sm"
                    />
                  </div>

                  <div class="col-12 col-md-6">
                    <q-input v-model="slider.primary_cta_label" label="Texto CTA principal" dense outlined class="q-mb-xs" />
                  </div>

                  <div class="col-12 col-md-6">
                    <q-input v-model="slider.primary_cta_url" label="URL CTA principal" dense outlined class="q-mb-xs" />
                  </div>

                  <div class="col-12 col-md-6">
                    <q-input v-model="slider.secondary_cta_label" label="Texto CTA secundario" dense outlined class="q-mb-xs" />
                  </div>

                  <div class="col-12 col-md-6">
                    <q-input v-model="slider.secondary_cta_url" label="URL CTA secundario" dense outlined class="q-mb-xs" />
                  </div>

                </div>
              </div>

              <div class="col-12 col-md-4">
                <div class="text-caption text-grey-7 q-mb-sm">Portadas de libros en el carrusel</div>

                <q-select
                  v-model="slider.libro_principal_id"
                  label="Libro principal"
                  dense
                  outlined
                  clearable
                  use-input
                  input-debounce="0"
                  :options="librosOptionsFiltered"
                  emit-value
                  map-options
                  class="q-mb-sm"
                  @filter="filterLibros"
                >
                  <template #option="scope">
                    <q-item v-bind="scope.itemProps">
                      <q-item-section avatar>
                        <q-avatar rounded size="32px">
                          <q-img v-if="scope.opt.portada" :src="imgLibro(scope.opt.portada)" />
                          <q-icon v-else name="menu_book" />
                        </q-avatar>
                      </q-item-section>
                      <q-item-section>
                        <q-item-label>{{ scope.opt.label }}</q-item-label>
                      </q-item-section>
                    </q-item>
                  </template>
                </q-select>

                <q-select
                  v-model="slider.libro_secundario_id"
                  label="Libro secundario"
                  dense
                  outlined
                  clearable
                  use-input
                  input-debounce="0"
                  :options="librosOptionsFiltered"
                  emit-value
                  map-options
                  class="q-mb-sm"
                  @filter="filterLibros"
                />

                <q-select
                  v-model="slider.libro_terciario_id"
                  label="Libro terciario"
                  dense
                  outlined
                  clearable
                  use-input
                  input-debounce="0"
                  :options="librosOptionsFiltered"
                  emit-value
                  map-options
                  class="q-mb-sm"
                  @filter="filterLibros"
                />

                <div class="slider-preview q-mt-md">
                  <div class="text-caption text-grey-7 q-mb-xs">Vista referencial</div>
                  <div class="preview-stack">
                    <div v-for="book in selectedBooks" :key="book.value" class="preview-book">
                      <img v-if="book.portada" :src="imgLibro(book.portada)" :alt="book.label">
                      <div v-else class="preview-book-empty">
                        <q-icon name="menu_book" size="28px" />
                      </div>
                    </div>
                    <div v-if="!selectedBooks.length" class="preview-book placeholder">
                      <div class="preview-book-empty">
                        <q-icon name="view_carousel" size="28px" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row justify-end q-gutter-sm q-mt-xs">
              <q-btn color="negative" label="Cancelar" no-caps flat @click="cerrarDialogo" :disable="loading" />
              <q-btn color="primary" label="Guardar" no-caps type="submit" :loading="loading" />
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import QMajetable from 'components/QMajetable.vue'

export default {
  name: 'HeroSlidersPage',
  components: {
    QMajetable
  },
  data () {
    return {
      heroSliders: [],
      librosOptions: [],
      librosOptionsFiltered: [],
      slider: {},
      sliderDialog: false,
      loading: false,
      filter: '',
      filterPublicadoWeb: null,
      themeOptions: [
        { label: 'Heritage', value: 'heritage' },
        { label: 'Catalog', value: 'catalog' },
        { label: 'Community', value: 'community' }
      ],
      estadoWebOptions: [
        { label: 'Publicado', value: '1' },
        { label: 'Oculto', value: '0' }
      ],
      pagination: {
        sortBy: 'orden',
        descending: false,
        page: 1,
        rowsPerPage: 10,
        rowsNumber: 0
      },
      columns: [
        { name: 'actions', label: 'Acciones', align: 'center' },
        { name: 'orden', label: 'Orden', align: 'right', field: 'orden' },
        { name: 'eyebrow', label: 'Eyebrow', align: 'left', field: 'eyebrow' },
        { name: 'title', label: 'Titulo', align: 'left', field: 'title' },
        { name: 'theme', label: 'Tema', align: 'center', field: 'theme' },
        { name: 'libros', label: 'Libros vinculados', align: 'left', field: 'libro_principal?.titulo' },
        { name: 'publicado_web', label: 'Web', align: 'center', field: 'publicado_web' }
      ]
    }
  },

  computed: {
    selectedBooks () {
      return [
        this.findLibroOption(this.slider.libro_principal_id),
        this.findLibroOption(this.slider.libro_secundario_id),
        this.findLibroOption(this.slider.libro_terciario_id)
      ].filter(Boolean)
    }
  },

  mounted () {
    this.librosGet()
    this.heroSlidersGet()
  },

  methods: {
    req (v) {
      return !!v || 'Campo requerido'
    },

    imgLibro (archivo) {
      return `${this.$url}../../images/${archivo}`
    },

    labelTheme (theme) {
      return this.themeOptions.find(item => item.value === theme)?.label || theme
    },

    findLibroOption (id) {
      return this.librosOptions.find(item => item.value === id) || null
    },

    async librosGet () {
      try {
        const { data } = await this.$axios.get('libros', {
          params: {
            per_page: 1000,
            page: 1
          }
        })
        this.librosOptions = (data.data || []).map(libro => ({
          label: libro.titulo,
          value: libro.id,
          portada: libro.portada || null
        }))
        this.librosOptionsFiltered = [...this.librosOptions]
      } catch (e) {
        this.$alert.error(e.response?.data?.message || 'No se pudo cargar libros')
      }
    },

    sliderNuevo () {
      this.slider = {
        eyebrow: '',
        title: '',
        description: '',
        badge: '',
        theme: 'heritage',
        primary_cta_label: 'Ver libros',
        primary_cta_url: '/libros',
        secondary_cta_label: 'Contactanos',
        secondary_cta_url: '/#contacto',
        libro_principal_id: null,
        libro_secundario_id: null,
        libro_terciario_id: null,
        orden: 0,
        publicado_web: false
      }
      this.librosOptionsFiltered = [...this.librosOptions]
      this.sliderDialog = true
    },

    sliderEditar (row) {
      this.slider = {
        ...row,
        libro_principal_id: row.libro_principal_id || null,
        libro_secundario_id: row.libro_secundario_id || null,
        libro_terciario_id: row.libro_terciario_id || null,
        publicado_web: !!row.publicado_web
      }
      this.librosOptionsFiltered = [...this.librosOptions]
      this.sliderDialog = true
    },

    cerrarDialogo () {
      this.sliderDialog = false
    },

    async heroSlidersGet () {
      this.loading = true
      try {
        const { page, rowsPerPage } = this.pagination
        const { data } = await this.$axios.get('hero-sliders', {
          params: {
            page,
            per_page: rowsPerPage,
            search: this.filter || '',
            publicado_web: this.filterPublicadoWeb ?? ''
          }
        })

        this.heroSliders = data.data || []
        this.pagination.page = data.current_page
        this.pagination.rowsPerPage = data.per_page
        this.pagination.rowsNumber = data.total
      } catch (e) {
        this.$alert.error(e.response?.data?.message || 'Error cargando hero sliders')
      } finally {
        this.loading = false
      }
    },

    async sliderPost () {
      this.loading = true
      try {
        await this.$axios.post('hero-sliders', this.slider)
        this.cerrarDialogo()
        this.$alert.success('Hero slider creado')
        await this.heroSlidersGet()
      } catch (e) {
        this.$alert.error(e.response?.data?.message || 'No se pudo crear')
      } finally {
        this.loading = false
      }
    },

    async sliderPut () {
      this.loading = true
      try {
        await this.$axios.put(`hero-sliders/${this.slider.id}`, this.slider)
        this.cerrarDialogo()
        this.$alert.success('Hero slider actualizado')
        await this.heroSlidersGet()
      } catch (e) {
        this.$alert.error(e.response?.data?.message || 'No se pudo actualizar')
      } finally {
        this.loading = false
      }
    },

    sliderEliminar (id) {
      this.$alert.dialog('Desea eliminar el hero slider?')
        .onOk(async () => {
          this.loading = true
          try {
            await this.$axios.delete(`hero-sliders/${id}`)
            this.$alert.success('Hero slider eliminado')
            await this.heroSlidersGet()
          } catch (e) {
            this.$alert.error(e.response?.data?.message || 'No se pudo eliminar')
          } finally {
            this.loading = false
          }
        })
    },

    onRequest (props) {
      this.pagination = { ...this.pagination, ...props.pagination }
      this.heroSlidersGet()
    },

    onSearch () {
      this.pagination.page = 1
      this.heroSlidersGet()
    },

    filterLibros (val, update) {
      update(() => {
        const needle = (val || '').toLowerCase().trim()
        if (!needle) {
          this.librosOptionsFiltered = [...this.librosOptions]
          return
        }

        this.librosOptionsFiltered = this.librosOptions.filter(item =>
          item.label.toLowerCase().includes(needle)
        )
      })
    }
  }
}
</script>

<style scoped>
.slider-preview {
  border: 1px solid rgba(0, 0, 0, 0.08);
  border-radius: 14px;
  padding: 12px;
  background: #faf7f5;
}

.preview-stack {
  display: flex;
  gap: 10px;
}

.preview-book {
  width: 86px;
  height: 120px;
  border-radius: 12px;
  overflow: hidden;
  background: #efe7e3;
  border: 1px solid rgba(0, 0, 0, 0.08);
}

.preview-book img,
.preview-book-empty {
  width: 100%;
  height: 100%;
}

.preview-book-empty {
  display: flex;
  align-items: center;
  justify-content: center;
  color: #6d5e5a;
}

.placeholder {
  width: 100%;
}
</style>
