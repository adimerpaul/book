<template>
  <q-page class="q-pa-xs">
    <q-card flat bordered class="q-mb-xs">
      <q-card-section class="row items-center">
        <div>
          <div class="text-h6 text-title">Autores</div>
          <div class="text-caption text-grey-7">Gestion de autores, datos biograficos y fotografia</div>
        </div>
        <q-space />
        <div class="row items-center q-gutter-sm">
          <q-input
            v-model="filter"
            label="Buscar"
            dense
            outlined
            debounce="400"
            style="width: 280px"
            @update:model-value="onSearch"
          >
            <template v-slot:append><q-icon name="search" /></template>
          </q-input>
          <q-btn
            color="positive"
            label="Nuevo"
            no-caps
            icon="add_circle_outline"
            :loading="loading"
            @click="autorNuevo"
          />
          <q-btn
            color="primary"
            label="Actualizar"
            no-caps
            icon="refresh"
            :loading="loading"
            @click="autoresGet"
          />
        </div>
      </q-card-section>
    </q-card>

    <QMajetable
      :rows="autores"
      :columns="columns"
      :pagination="pagination"
      no-data-label="Sin registros"
      @request="onRequest"
    >
      <template #body-cell-actions="{ row }">
        <q-btn-dropdown label="Opciones" no-caps size="10px" dense color="primary">
          <q-list>
            <q-item clickable v-close-popup @click="autorEditar(row)">
              <q-item-section avatar><q-icon name="edit" /></q-item-section>
              <q-item-section><q-item-label>Editar</q-item-label></q-item-section>
            </q-item>

            <q-item clickable v-close-popup @click="autorEliminar(row.id)">
              <q-item-section avatar><q-icon name="delete" /></q-item-section>
              <q-item-section><q-item-label>Eliminar</q-item-label></q-item-section>
            </q-item>
          </q-list>
        </q-btn-dropdown>
      </template>

      <template #body-cell-fotografia="{ row }">
        <q-avatar rounded size="42px">
          <q-img v-if="row.fotografia" :src="imgAutor(row.fotografia)" />
          <q-icon v-else name="person" size="28px" />
        </q-avatar>
      </template>

      <template #body-cell-premios="{ row }">
        <div class="ellipsis-cell">
          {{ row.premios || '-' }}
        </div>
      </template>
    </QMajetable>

    <q-dialog v-model="autorDialog" persistent>
      <q-card style="width: 640px; max-width: 94vw">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-subtitle1 text-weight-bold">
            {{ autor.id ? 'Editar autor' : 'Nuevo autor' }}
          </div>
          <q-space />
          <q-btn icon="close" flat round dense @click="cerrarDialogo" />
        </q-card-section>

        <q-card-section class="q-pt-xs q-px-sm q-pb-sm">
          <q-form @submit.prevent="autor.id ? autorPut() : autorPost()">
            <div class="row q-col-gutter-sm">
              <div class="col-12 col-md-8">
                <div class="row q-col-gutter-xs">
                  <div class="col-12 col-md-6">
                    <q-input v-model="autor.nombre_completo" label="Nombre completo" dense outlined :rules="[req]" class="q-mb-xs" />
                  </div>
                  <div class="col-12 col-md-6">
                    <q-input v-model="autor.profesion" label="Profesion" dense outlined class="q-mb-xs" />
                  </div>

                  <div class="col-12 col-md-6">
                    <q-input v-model="autor.fecha_nacimiento" label="Fecha de nacimiento" dense outlined type="date" class="q-mb-xs" />
                  </div>
                  <div class="col-12 col-md-6">
                    <q-input v-model="autor.fecha_fallecimiento" label="Fecha de fallecimiento" dense outlined type="date" class="q-mb-xs" />
                  </div>

                  <div class="col-12 col-md-6">
                    <q-input v-model="autor.lugar_nacimiento" label="Lugar de nacimiento" dense outlined class="q-mb-xs" />
                  </div>
                  <div class="col-12 col-md-6">
                    <q-select
                      v-model="autor.genero_literario"
                      label="Genero literario"
                      dense
                      outlined
                      :options="generosLiterarios"
                      emit-value
                      map-options
                      clearable
                      class="q-mb-xs"
                    />
                  </div>

                  <div class="col-12">
                    <q-input
                      v-model="autor.premios"
                      label="Premios"
                      dense
                      outlined
                      type="textarea"
                      autogrow
                      input-style="min-height: 56px"
                      class="q-mb-sm"
                    />
                  </div>
                </div>
              </div>

              <div class="col-12 col-md-4">
                <div class="relative-position q-mt-xs">
                  <q-btn
                    icon="edit"
                    size="10px"
                    class="absolute-top-right q-ma-xs"
                    @click="$refs.fileInput.click()"
                    dense
                    outline
                    label="Cambiar"
                    no-caps
                  />
                  <img
                    v-if="fotografiaPreview"
                    :src="fotografiaPreview"
                    class="full-width rounded-borders"
                    style="height: 170px; object-fit: cover; border: 1px solid rgba(0, 0, 0, 0.08); background: #f6f7f9;"
                  />
                  <div
                    v-else
                    class="row items-center justify-center full-width rounded-borders bg-grey-2"
                    style="height: 170px; border: 1px solid rgba(0, 0, 0, 0.08);"
                  >
                    <q-icon name="person" size="64px" />
                  </div>
                  <input ref="fileInput" type="file" style="display:none" @change="onFileChange" accept="image/*" />
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
  name: 'AutoresPage',
  components: {
    QMajetable
  },
  data () {
    return {
      autores: [],
      autor: {},
      autorDialog: false,
      loading: false,
      filter: '',
      fotografiaFile: null,
      fotografiaPreview: '',
      pagination: {
        sortBy: 'id',
        descending: true,
        page: 1,
        rowsPerPage: 10,
        rowsNumber: 0
      },
      generosLiterarios: [
        { label: 'Novela', value: 'Novela' },
        { label: 'Cuento', value: 'Cuento' },
        { label: 'Poesia', value: 'Poesia' },
        { label: 'Ensayo', value: 'Ensayo' },
        { label: 'Cronica', value: 'Cronica' },
        { label: 'Biografia', value: 'Biografia' },
        { label: 'Teatro', value: 'Teatro' },
        { label: 'Literatura infantil', value: 'Literatura infantil' },
        { label: 'Literatura juvenil', value: 'Literatura juvenil' },
        { label: 'Narrativa historica', value: 'Narrativa historica' }
      ],
      columns: [
        { name: 'actions', label: 'Acciones', align: 'center' },
        { name: 'fotografia', label: 'Foto', align: 'left', field: 'fotografia' },
        { name: 'nombre_completo', label: 'Nombre completo', align: 'left', field: 'nombre_completo' },
        { name: 'fecha_nacimiento', label: 'Nacimiento', align: 'left', field: 'fecha_nacimiento' },
        { name: 'lugar_nacimiento', label: 'Lugar de nacimiento', align: 'left', field: 'lugar_nacimiento' },
        { name: 'fecha_fallecimiento', label: 'Fallecimiento', align: 'left', field: 'fecha_fallecimiento' },
        { name: 'profesion', label: 'Profesion', align: 'left', field: 'profesion' },
        { name: 'genero_literario', label: 'Genero literario', align: 'left', field: 'genero_literario' },
        { name: 'premios', label: 'Premios', align: 'left', field: 'premios' }
      ]
    }
  },

  mounted () {
    this.autoresGet()
  },

  methods: {
    req (v) {
      return !!v || 'Campo requerido'
    },

    imgAutor (fotografia) {
      return `${this.$url}../../images/${fotografia}`
    },

    resetFotografiaState () {
      this.fotografiaFile = null
      this.fotografiaPreview = ''
      if (this.$refs.fileInput) this.$refs.fileInput.value = ''
    },

    autorNuevo () {
      this.autor = {
        nombre_completo: '',
        fecha_nacimiento: '',
        lugar_nacimiento: '',
        fecha_fallecimiento: '',
        profesion: '',
        genero_literario: '',
        premios: '',
        fotografia: null
      }
      this.resetFotografiaState()
      this.autorDialog = true
    },

    autorEditar (autor) {
      this.autor = {
        ...autor,
        fecha_nacimiento: autor.fecha_nacimiento || '',
        fecha_fallecimiento: autor.fecha_fallecimiento || ''
      }
      this.resetFotografiaState()
      this.fotografiaPreview = autor.fotografia ? this.imgAutor(autor.fotografia) : ''
      this.autorDialog = true
    },

    cerrarDialogo () {
      this.autorDialog = false
      this.resetFotografiaState()
    },

    async autoresGet () {
      this.loading = true
      try {
        const { page, rowsPerPage } = this.pagination
        const { data } = await this.$axios.get('autores', {
          params: {
            page,
            per_page: rowsPerPage,
            search: this.filter || ''
          }
        })

        this.autores = (data.data || []).sort((a, b) => b.id - a.id)
        this.pagination.page = data.current_page
        this.pagination.rowsPerPage = data.per_page
        this.pagination.rowsNumber = data.total
      } catch (e) {
        this.$alert.error(e.response?.data?.message || 'Error cargando autores')
      } finally {
        this.loading = false
      }
    },

    async subirFotografiaSiCorresponde (autorId) {
      if (!this.fotografiaFile) return

      const formData = new FormData()
      formData.append('fotografia', this.fotografiaFile)

      await this.$axios.post(`autores/${autorId}/fotografia`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
    },

    async autorPost () {
      this.loading = true
      try {
        const { data } = await this.$axios.post('autores', this.autor)
        await this.subirFotografiaSiCorresponde(data.id)
        this.cerrarDialogo()
        this.$alert.success('Autor creado')
        await this.autoresGet()
      } catch (e) {
        this.$alert.error(e.response?.data?.message || 'No se pudo crear')
      } finally {
        this.loading = false
      }
    },

    async autorPut () {
      this.loading = true
      try {
        await this.$axios.put(`autores/${this.autor.id}`, this.autor)
        await this.subirFotografiaSiCorresponde(this.autor.id)
        this.cerrarDialogo()
        this.$alert.success('Autor actualizado')
        await this.autoresGet()
      } catch (e) {
        this.$alert.error(e.response?.data?.message || 'No se pudo actualizar')
      } finally {
        this.loading = false
      }
    },

    autorEliminar (id) {
      this.$alert.dialog('Desea eliminar el autor?')
        .onOk(async () => {
          this.loading = true
          try {
            await this.$axios.delete(`autores/${id}`)
            this.$alert.success('Autor eliminado')
            await this.autoresGet()
          } catch (e) {
            this.$alert.error(e.response?.data?.message || 'No se pudo eliminar')
          } finally {
            this.loading = false
          }
        })
    },

    onFileChange (event) {
      const file = event.target.files[0]
      if (!file) return

      this.fotografiaFile = file
      this.fotografiaPreview = URL.createObjectURL(file)
    },

    onRequest (props) {
      this.pagination = { ...this.pagination, ...props.pagination }
      this.autoresGet()
    },

    onSearch () {
      this.pagination.page = 1
      this.autoresGet()
    }
  }
}
</script>

<style scoped>
.ellipsis-cell {
  max-width: 260px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>
