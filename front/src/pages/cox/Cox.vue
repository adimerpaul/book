<template>
  <q-page class="q-pa-md">
    <q-card flat bordered style="max-width: 620px">
      <q-card-section>
        <div class="text-h6">Cox</div>
        <div class="text-caption text-grey-7">
          Configuracion global para contacto comercial de la web.
        </div>
      </q-card-section>

      <q-separator />

      <q-card-section>
        <q-form @submit.prevent="guardar">
          <q-input
            v-model="form.whatsapp_number"
            label="Numero de WhatsApp"
            dense
            outlined
            hint="Ejemplo: 59170000000"
            :rules="[req]"
            class="q-mb-md"
          />

          <div class="row justify-end q-gutter-sm">
            <q-btn no-caps flat color="primary" label="Recargar" :loading="loading" @click="cargar" />
            <q-btn no-caps color="primary" label="Guardar" type="submit" :loading="loading" />
          </div>
        </q-form>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
export default {
  name: 'CoxPage',
  data () {
    return {
      loading: false,
      form: {
        whatsapp_number: ''
      }
    }
  },
  mounted () {
    this.cargar()
  },
  methods: {
    req (v) {
      return !!v || 'Campo requerido'
    },
    async cargar () {
      this.loading = true
      try {
        const { data } = await this.$axios.get('cox')
        this.form.whatsapp_number = data.whatsapp_number || ''
      } catch (e) {
        this.$alert.error(e.response?.data?.message || 'No se pudo cargar la configuracion')
      } finally {
        this.loading = false
      }
    },
    async guardar () {
      this.loading = true
      try {
        await this.$axios.put('cox', this.form)
        this.$alert.success('Configuracion actualizada')
      } catch (e) {
        this.$alert.error(e.response?.data?.message || 'No se pudo guardar')
      } finally {
        this.loading = false
      }
    }
  }
}
</script>
