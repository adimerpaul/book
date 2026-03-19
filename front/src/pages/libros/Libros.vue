<template>
  <q-page class="q-pa-xs">
    <q-card flat bordered class="q-mb-xs">
      <q-card-section class="row items-center q-col-gutter-sm">
        <div class="col-auto">
          <div class="text-h6 text-title">Libros</div>
          <div class="text-caption text-grey-7">Gestion de libros, portada, galeria y contenido editorial</div>
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
              v-model="filterAutorId"
              label="Autor"
              dense
              outlined
              clearable
              use-input
              input-debounce="0"
              style="width: 240px"
              :options="autoresOptionsFiltered"
              emit-value
              map-options
              @filter="filterAutores"
              @update:model-value="onSearch"
            >
              <template #option="scope">
                <q-item v-bind="scope.itemProps">
                  <q-item-section avatar>
                    <q-avatar size="30px">
                      <q-img v-if="scope.opt.fotografia" :src="imgLibro(scope.opt.fotografia)" />
                      <q-icon v-else name="person" />
                    </q-avatar>
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>{{ scope.opt.label }}</q-item-label>
                  </q-item-section>
                </q-item>
              </template>

              <template #selected-item="scope">
                <div class="row items-center no-wrap q-gutter-xs">
                  <q-avatar size="24px">
                    <q-img v-if="scope.opt.fotografia" :src="imgLibro(scope.opt.fotografia)" />
                    <q-icon v-else name="person" />
                  </q-avatar>
                  <span class="ellipsis">{{ scope.opt.label }}</span>
                </div>
              </template>
            </q-select>

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

            <q-btn color="positive" label="Nuevo" no-caps icon="add_circle_outline" :loading="loading" @click="libroNuevo" />
            <q-btn color="primary" label="Actualizar" no-caps icon="refresh" :loading="loading" @click="librosGet" />
          </div>
        </div>
      </q-card-section>
    </q-card>

    <QMajetable
      :rows="libros"
      :columns="columns"
      :pagination="pagination"
      no-data-label="Sin registros"
      @request="onRequest"
    >
      <template #body-cell-actions="{ row }">
        <q-btn-dropdown label="Opciones" no-caps size="10px" dense color="primary">
          <q-list>
            <q-item clickable v-close-popup @click="libroEditar(row)">
              <q-item-section avatar><q-icon name="edit" /></q-item-section>
              <q-item-section><q-item-label>Editar</q-item-label></q-item-section>
            </q-item>

            <q-item clickable v-close-popup @click="libroEliminar(row.id)">
              <q-item-section avatar><q-icon name="delete" /></q-item-section>
              <q-item-section><q-item-label>Eliminar</q-item-label></q-item-section>
            </q-item>
          </q-list>
        </q-btn-dropdown>
      </template>

      <template #body-cell-portada="{ row }">
        <q-avatar rounded size="42px">
          <q-img v-if="row.portada" :src="imgLibro(row.portada)" />
          <q-icon v-else name="menu_book" size="24px" />
        </q-avatar>
      </template>

      <template #body-cell-autor="{ row }">
        <div class="row items-center no-wrap q-gutter-xs">
          <q-avatar size="28px">
            <q-img v-if="row.autor?.fotografia" :src="imgLibro(row.autor.fotografia)" />
            <q-icon v-else name="person" />
          </q-avatar>
          <span>{{ row.autor?.nombre_completo || '-' }}</span>
        </div>
      </template>

      <template #body-cell-drive_indice_url="{ row }">
        <a
          v-if="row.drive_indice_url"
          :href="row.drive_indice_url"
          target="_blank"
          rel="noopener noreferrer"
          class="text-primary"
        >
          Ver indice
        </a>
        <span v-else>-</span>
      </template>

      <template #body-cell-publicado_web="{ row }">
        <q-chip dense :color="row.publicado_web ? 'positive' : 'grey-6'" text-color="white">
          {{ row.publicado_web ? 'Publicado' : 'Oculto' }}
        </q-chip>
      </template>

      <template #body-cell-fotografias_count="{ row }">
        <q-chip dense color="grey-2" text-color="dark">
          {{ row.fotografias_count || 0 }}
        </q-chip>
      </template>
    </QMajetable>

    <q-dialog v-model="libroDialog" persistent>
      <q-card style="width: 920px; max-width: 96vw">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-subtitle1 text-weight-bold">
            {{ libro.id ? 'Editar libro' : 'Nuevo libro' }}
          </div>
          <q-space />
          <q-btn icon="close" flat round dense @click="cerrarDialogo" />
        </q-card-section>

        <q-card-section class="q-pt-xs q-px-sm q-pb-sm">
          <q-form @submit.prevent="libro.id ? libroPut() : libroPost()">
            <div class="row q-col-gutter-sm">
              <div class="col-12 col-md-8">
                <div class="row q-col-gutter-xs">
                  <div class="col-12 col-md-7">
                    <q-input v-model="libro.titulo" label="Titulo" dense outlined :rules="[req]" class="q-mb-xs" />
                  </div>

                  <div class="col-12 col-md-5">
                    <div class="row items-start q-col-gutter-xs">
                      <div class="col">
                        <q-select
                          v-model="libro.autor_id"
                          label="Autor"
                          dense
                          outlined
                          use-input
                          input-debounce="0"
                          :options="autoresOptionsFiltered"
                          emit-value
                          map-options
                          :rules="[req]"
                          class="q-mb-xs"
                          @filter="filterAutores"
                        >
                          <template #option="scope">
                            <q-item v-bind="scope.itemProps">
                              <q-item-section avatar>
                                <q-avatar size="30px">
                                  <q-img v-if="scope.opt.fotografia" :src="imgLibro(scope.opt.fotografia)" />
                                  <q-icon v-else name="person" />
                                </q-avatar>
                              </q-item-section>
                              <q-item-section>
                                <q-item-label>{{ scope.opt.label }}</q-item-label>
                              </q-item-section>
                            </q-item>
                          </template>

                          <template #selected-item="scope">
                            <div class="row items-center no-wrap q-gutter-xs">
                              <q-avatar size="24px">
                                <q-img v-if="scope.opt.fotografia" :src="imgLibro(scope.opt.fotografia)" />
                                <q-icon v-else name="person" />
                              </q-avatar>
                              <span class="ellipsis">{{ scope.opt.label }}</span>
                            </div>
                          </template>
                        </q-select>
                      </div>
                      <div class="col-auto">
                        <q-btn
                          color="positive"
                          icon="add"
                          dense
                          unelevated
                          class="q-mt-xs"
                          @click="autorRapidoDialog = true"
                        />
                      </div>
                    </div>
                  </div>

                  <div class="col-12 col-md-4">
                    <q-input v-model="libro.fecha_publicacion" label="Fecha de publicacion" dense outlined type="date" class="q-mb-xs" />
                  </div>

                  <div class="col-12 col-md-4">
                    <q-select
                      v-model="libro.pais"
                      label="Pais"
                      dense
                      outlined
                      use-input
                      fill-input
                      hide-selected
                      input-debounce="0"
                      :options="paisOptions"
                      @filter="(val, update) => filterList(val, update, 'paisOptions', paisesBase)"
                    />
                  </div>

                  <div class="col-12 col-md-4">
                    <q-select
                      v-model="libro.idioma"
                      label="Idioma"
                      dense
                      outlined
                      use-input
                      fill-input
                      hide-selected
                      input-debounce="0"
                      :options="idiomaOptions"
                      @filter="(val, update) => filterList(val, update, 'idiomaOptions', idiomasBase)"
                    />
                  </div>

                  <div class="col-12 col-md-4">
                    <q-select
                      v-model="libro.genero"
                      label="Genero"
                      dense
                      outlined
                      use-input
                      fill-input
                      hide-selected
                      input-debounce="0"
                      :options="generoOptions"
                      @filter="(val, update) => filterList(val, update, 'generoOptions', generosBase)"
                    />
                  </div>

                  <div class="col-12 col-md-4">
                    <q-select
                      v-model="libro.subgenero"
                      label="Subgenero"
                      dense
                      outlined
                      use-input
                      fill-input
                      hide-selected
                      input-debounce="0"
                      :options="subgeneroOptions"
                      @filter="(val, update) => filterList(val, update, 'subgeneroOptions', subgenerosBase)"
                    />
                  </div>

                  <div class="col-12 col-md-4">
                    <q-select
                      v-model="libro.editorial"
                      label="Editorial"
                      dense
                      outlined
                      use-input
                      fill-input
                      hide-selected
                      new-value-mode="add-unique"
                      input-debounce="0"
                      :options="editorialOptions"
                      @filter="(val, update) => filterList(val, update, 'editorialOptions', editorialesBase)"
                    />
                  </div>

                  <div class="col-12 col-md-4">
                    <q-input v-model.number="libro.paginas" label="Paginas" dense outlined type="number" min="1" class="q-mb-xs" />
                  </div>

                  <div class="col-12 col-md-4">
                    <q-toggle
                      v-model="libro.publicado_web"
                      color="positive"
                      label="Publicado en web"
                      left-label
                      dense
                      class="q-mt-sm"
                    />
                  </div>

                  <div class="col-12 col-md-4">
                    <q-input v-model="libro.drive_indice_url" label="URL del drive del indice" dense outlined class="q-mb-xs" />
                  </div>

                  <div class="col-12">
                    <q-input
                      v-model="libro.resumen_contenido"
                      label="Resumen del contenido"
                      dense
                      outlined
                      type="textarea"
                      autogrow
                      input-style="min-height: 52px"
                      class="q-mb-xs"
                    />
                  </div>

                  <div class="col-12">
                    <q-input
                      v-model="libro.contenido"
                      label="Contenido"
                      dense
                      outlined
                      type="textarea"
                      autogrow
                      input-style="min-height: 78px"
                      class="q-mb-xs"
                    />
                  </div>

                  <div class="col-12">
                    <q-input
                      v-model="libro.reconocimiento"
                      label="Reconocimiento"
                      dense
                      outlined
                      type="textarea"
                      autogrow
                      input-style="min-height: 48px"
                      class="q-mb-xs"
                    />
                  </div>
                </div>
              </div>

              <div class="col-12 col-md-4">
                <div class="relative-position q-mt-xs q-mb-sm">
                  <q-btn
                    icon="edit"
                    size="10px"
                    class="absolute-top-right q-ma-xs"
                    @click="$refs.portadaInput.click()"
                    dense
                    outline
                    label="Portada"
                    no-caps
                  />
                  <img
                    v-if="portadaPreview"
                    :src="portadaPreview"
                    class="full-width rounded-borders"
                    style="height: 180px; object-fit: cover; border: 1px solid rgba(0, 0, 0, 0.08); background: #f6f7f9;"
                  />
                  <div
                    v-else
                    class="row items-center justify-center full-width rounded-borders bg-grey-2"
                    style="height: 180px; border: 1px solid rgba(0, 0, 0, 0.08);"
                  >
                    <q-icon name="menu_book" size="64px" />
                  </div>
                  <input ref="portadaInput" type="file" style="display:none" accept="image/*" @change="onPortadaChange" />
                </div>

                <div class="row items-center q-mb-xs">
                  <div class="text-caption text-grey-7">Fotografias</div>
                  <q-space />
                  <q-btn
                    icon="add_photo_alternate"
                    size="10px"
                    dense
                    outline
                    no-caps
                    label="Agregar"
                    @click="$refs.galeriaInput.click()"
                  />
                  <input ref="galeriaInput" type="file" style="display:none" accept="image/*" multiple @change="onGaleriaChange" />
                </div>

                <div class="row q-col-gutter-xs">
                  <div
                    v-for="(foto, index) in fotosPreview"
                    :key="foto.id || foto.url || index"
                    class="col-6"
                  >
                    <div class="relative-position">
                      <img
                        :src="foto.url"
                        class="full-width rounded-borders"
                        style="height: 82px; object-fit: cover; border: 1px solid rgba(0, 0, 0, 0.08); background: #f6f7f9;"
                      >
                      <q-btn
                        icon="close"
                        round
                        dense
                        flat
                        color="negative"
                        class="absolute-top-right"
                        @click="removeFoto(index, foto)"
                      />
                    </div>
                  </div>
                  <div v-if="!fotosPreview.length" class="col-12">
                    <div class="text-caption text-grey-6 q-py-sm">
                      Sin fotografias cargadas
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

    <q-dialog v-model="autorRapidoDialog" persistent>
      <q-card style="width: 360px; max-width: 92vw">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-subtitle2 text-weight-bold">Nuevo autor</div>
          <q-space />
          <q-btn icon="close" flat round dense @click="cerrarAutorRapido" />
        </q-card-section>

        <q-card-section class="q-pt-sm">
          <q-input
            v-model="autorRapido.nombre_completo"
            label="Nombre completo"
            dense
            outlined
            :rules="[req]"
            class="q-mb-sm"
          />

          <div class="row justify-end q-gutter-sm">
            <q-btn flat no-caps color="negative" label="Cancelar" @click="cerrarAutorRapido" />
            <q-btn no-caps color="primary" label="Guardar" :loading="loadingAutorRapido" @click="crearAutorRapido" />
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import QMajetable from 'components/QMajetable.vue'

const paisesBase = [
  'Argentina', 'Bolivia', 'Brasil', 'Chile', 'Colombia', 'Costa Rica', 'Cuba', 'Ecuador', 'El Salvador',
  'España', 'Estados Unidos', 'Guatemala', 'Honduras', 'Mexico', 'Nicaragua', 'Panama', 'Paraguay',
  'Peru', 'Puerto Rico', 'Republica Dominicana', 'Uruguay', 'Venezuela'
]

const idiomasBase = [
  'Espanol', 'Ingles', 'Portugues', 'Frances', 'Italiano', 'Aleman', 'Quechua', 'Aymara', 'Guarani', 'Catalan'
]

const generosBase = [
  'Novela', 'Cuento', 'Poesia', 'Ensayo', 'Cronica', 'Biografia', 'Teatro', 'Historia', 'Literatura infantil', 'Literatura juvenil'
]

const subgenerosBase = [
  'Narrativa historica', 'Realismo magico', 'Romance', 'Suspenso', 'Fantasia', 'Ciencia ficcion', 'Filosofico',
  'Autobiografico', 'Academico', 'Testimonial'
]

const editorialesBase = [
  'Latinas Editores', 'LTA Ediciones', 'Editorial Andina', 'Casa de las Letras', 'Horizonte Cultural', 'Sur Editorial'
]

export default {
  name: 'LibrosPage',
  components: {
    QMajetable
  },
  data () {
    return {
      paisesBase,
      idiomasBase,
      generosBase,
      subgenerosBase,
      editorialesBase,
      paisOptions: [...paisesBase],
      idiomaOptions: [...idiomasBase],
      generoOptions: [...generosBase],
      subgeneroOptions: [...subgenerosBase],
      editorialOptions: [...editorialesBase],
      libros: [],
      autoresOptions: [],
      autoresOptionsFiltered: [],
      libro: {},
      libroDialog: false,
      autorRapidoDialog: false,
      loading: false,
      loadingAutorRapido: false,
      filter: '',
      filterAutorId: null,
      filterPublicadoWeb: null,
      portadaFile: null,
      portadaPreview: '',
      galeriaFiles: [],
      fotosPreview: [],
      autorRapido: {
        nombre_completo: ''
      },
      estadoWebOptions: [
        { label: 'Publicado', value: '1' },
        { label: 'Oculto', value: '0' }
      ],
      pagination: {
        sortBy: 'id',
        descending: true,
        page: 1,
        rowsPerPage: 10,
        rowsNumber: 0
      },
      columns: [
        { name: 'actions', label: 'Acciones', align: 'center' },
        { name: 'portada', label: 'Portada', align: 'left', field: 'portada' },
        { name: 'titulo', label: 'Titulo', align: 'left', field: 'titulo' },
        { name: 'autor', label: 'Autor', align: 'left', field: row => row.autor?.nombre_completo || '' },
        { name: 'pais', label: 'Pais', align: 'left', field: 'pais' },
        { name: 'idioma', label: 'Idioma', align: 'left', field: 'idioma' },
        { name: 'genero', label: 'Genero', align: 'left', field: 'genero' },
        { name: 'editorial', label: 'Editorial', align: 'left', field: 'editorial' },
        { name: 'paginas', label: 'Paginas', align: 'right', field: 'paginas' },
        { name: 'fotografias_count', label: 'Fotos', align: 'center', field: 'fotografias_count' },
        { name: 'publicado_web', label: 'Web', align: 'center', field: 'publicado_web' },
        { name: 'drive_indice_url', label: 'Indice', align: 'left', field: 'drive_indice_url' }
      ]
    }
  },

  mounted () {
    this.autoresGet()
    this.librosGet()
  },

  methods: {
    req (v) {
      return !!v || 'Campo requerido'
    },

    imgLibro (archivo) {
      return `${this.$url}../../images/${archivo}`
    },

    filterList (val, update, key, baseList) {
      update(() => {
        const needle = (val || '').toLowerCase().trim()
        this[key] = !needle
          ? [...baseList]
          : baseList.filter(item => item.toLowerCase().includes(needle))
      })
    },

    async autoresGet () {
      try {
        const { data } = await this.$axios.get('autores', {
          params: { per_page: 1000, page: 1 }
        })
        this.autoresOptions = (data.data || []).map(autor => ({
          label: autor.nombre_completo,
          value: autor.id,
          fotografia: autor.fotografia || null
        }))
        this.autoresOptionsFiltered = [...this.autoresOptions]
      } catch (e) {
        this.$alert.error(e.response?.data?.message || 'No se pudo cargar autores')
      }
    },

    resetArchivos () {
      this.portadaFile = null
      this.portadaPreview = ''
      this.galeriaFiles = []
      this.fotosPreview = []
      this.paisOptions = [...this.paisesBase]
      this.idiomaOptions = [...this.idiomasBase]
      this.generoOptions = [...this.generosBase]
      this.subgeneroOptions = [...this.subgenerosBase]
      this.editorialOptions = [...this.editorialesBase]
      if (this.$refs.portadaInput) this.$refs.portadaInput.value = ''
      if (this.$refs.galeriaInput) this.$refs.galeriaInput.value = ''
    },

    libroNuevo () {
      this.libro = {
        titulo: '',
        autor_id: null,
        fecha_publicacion: '',
        pais: '',
        idioma: '',
        genero: '',
        subgenero: '',
        editorial: '',
        paginas: null,
        contenido: '',
        resumen_contenido: '',
        reconocimiento: '',
        drive_indice_url: '',
        publicado_web: false,
        portada: null,
        fotografias: []
      }
      this.resetArchivos()
      this.libroDialog = true
    },

    libroEditar (row) {
      this.libro = {
        ...row,
        autor_id: row.autor_id,
        fecha_publicacion: row.fecha_publicacion || '',
        paginas: row.paginas || null,
        publicado_web: !!row.publicado_web
      }
      this.resetArchivos()
      this.portadaPreview = row.portada ? this.imgLibro(row.portada) : ''
      this.fotosPreview = (row.fotografias || []).map(foto => ({
        id: foto.id,
        url: this.imgLibro(foto.archivo),
        existing: true
      }))
      this.libroDialog = true
    },

    cerrarDialogo () {
      this.libroDialog = false
      this.resetArchivos()
    },

    cerrarAutorRapido () {
      this.autorRapidoDialog = false
      this.autorRapido = { nombre_completo: '' }
    },

    async librosGet () {
      this.loading = true
      try {
        const { page, rowsPerPage } = this.pagination
        const { data } = await this.$axios.get('libros', {
          params: {
            page,
            per_page: rowsPerPage,
            search: this.filter || '',
            autor_id: this.filterAutorId || '',
            publicado_web: this.filterPublicadoWeb ?? ''
          }
        })

        this.libros = data.data || []
        this.pagination.page = data.current_page
        this.pagination.rowsPerPage = data.per_page
        this.pagination.rowsNumber = data.total
      } catch (e) {
        this.$alert.error(e.response?.data?.message || 'Error cargando libros')
      } finally {
        this.loading = false
      }
    },

    async subirArchivos (libroId) {
      if (this.portadaFile) {
        const portadaData = new FormData()
        portadaData.append('portada', this.portadaFile)
        await this.$axios.post(`libros/${libroId}/portada`, portadaData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        })
      }

      if (this.galeriaFiles.length) {
        const galeriaData = new FormData()
        this.galeriaFiles.forEach(file => {
          galeriaData.append('fotografias[]', file)
        })
        await this.$axios.post(`libros/${libroId}/fotografias`, galeriaData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        })
      }
    },

    async libroPost () {
      this.loading = true
      try {
        const { data } = await this.$axios.post('libros', this.libro)
        await this.subirArchivos(data.id)
        this.cerrarDialogo()
        this.$alert.success('Libro creado')
        await this.librosGet()
      } catch (e) {
        this.$alert.error(e.response?.data?.message || 'No se pudo crear')
      } finally {
        this.loading = false
      }
    },

    async libroPut () {
      this.loading = true
      try {
        await this.$axios.put(`libros/${this.libro.id}`, this.libro)
        await this.subirArchivos(this.libro.id)
        this.cerrarDialogo()
        this.$alert.success('Libro actualizado')
        await this.librosGet()
      } catch (e) {
        this.$alert.error(e.response?.data?.message || 'No se pudo actualizar')
      } finally {
        this.loading = false
      }
    },

    libroEliminar (id) {
      this.$alert.dialog('Desea eliminar el libro?')
        .onOk(async () => {
          this.loading = true
          try {
            await this.$axios.delete(`libros/${id}`)
            this.$alert.success('Libro eliminado')
            await this.librosGet()
          } catch (e) {
            this.$alert.error(e.response?.data?.message || 'No se pudo eliminar')
          } finally {
            this.loading = false
          }
        })
    },

    onPortadaChange (event) {
      const file = event.target.files[0]
      if (!file) return
      this.portadaFile = file
      this.portadaPreview = URL.createObjectURL(file)
    },

    onGaleriaChange (event) {
      const files = Array.from(event.target.files || [])
      if (!files.length) return

      files.forEach(file => {
        this.galeriaFiles.push(file)
        this.fotosPreview.push({
          url: URL.createObjectURL(file),
          existing: false,
          file
        })
      })
    },

    async removeFoto (index, foto) {
      if (foto.existing && this.libro.id) {
        try {
          await this.$axios.delete(`libros/${this.libro.id}/fotografias/${foto.id}`)
          this.$alert.success('Fotografia eliminada')
        } catch (e) {
          this.$alert.error(e.response?.data?.message || 'No se pudo eliminar la fotografia')
          return
        }
      } else if (foto.file) {
        this.galeriaFiles = this.galeriaFiles.filter(item => item !== foto.file)
      }

      this.fotosPreview.splice(index, 1)
    },

    onRequest (props) {
      this.pagination = { ...this.pagination, ...props.pagination }
      this.librosGet()
    },

    onSearch () {
      this.pagination.page = 1
      this.librosGet()
    },

    filterAutores (val, update) {
      update(() => {
        const needle = (val || '').toLowerCase().trim()
        if (!needle) {
          this.autoresOptionsFiltered = [...this.autoresOptions]
          return
        }
        this.autoresOptionsFiltered = this.autoresOptions.filter(item =>
          item.label.toLowerCase().includes(needle)
        )
      })
    },

    async crearAutorRapido () {
      if (!this.autorRapido.nombre_completo) {
        this.$alert.error('Ingrese el nombre completo del autor')
        return
      }

      this.loadingAutorRapido = true
      try {
        const { data } = await this.$axios.post('autores', {
          nombre_completo: this.autorRapido.nombre_completo
        })
        await this.autoresGet()
        this.libro.autor_id = data.id
        this.cerrarAutorRapido()
        this.$alert.success('Autor creado')
      } catch (e) {
        this.$alert.error(e.response?.data?.message || 'No se pudo crear el autor')
      } finally {
        this.loadingAutorRapido = false
      }
    }
  }
}
</script>
