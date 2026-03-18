<template>
  <q-layout class="login-layout">
    <q-page-container>
      <q-page class="full-height">
        <div class="login-bg-overlay"></div>
        <div class="login-glow login-glow-left"></div>
        <div class="login-glow login-glow-right"></div>

        <q-form @submit.prevent="login" class="login-wrap">
          <q-card flat bordered class="login-card">
            <q-card-section class="login-top text-center">
              <q-img src="logo.png" width="78px" class="q-mb-sm login-logo" ratio="1" fit="contain" />
              <div class="text-subtitle2 text-grey-8 brand-chip">
                <b>Latinas Editores</b> · Plataforma editorial
              </div>
              <div class="brand-title q-mt-sm">Bienvenido</div>
              <div class="brand-subtitle">
                Accede al panel administrativo de Latinas Editores.
              </div>
            </q-card-section>

            <q-separator spaced />

            <q-card-section class="q-pt-none login-body">
              <div class="text-h6 text-bold q-mb-xs">Iniciar sesion</div>
              <div class="text-body2 text-grey-7 q-mb-sm">
                Ingresa con tus credenciales.
              </div>

              <div class="q-mb-xs text-caption text-grey-7">Usuario</div>
              <q-input
                v-model="username"
                outlined
                dense
                placeholder="Ingresa tu usuario"
                :rules="[v => !!v || 'Ingrese su usuario']"
                class="q-mb-sm"
              >
                <template v-slot:prepend>
                  <q-icon name="account_circle" size="18px" />
                </template>
              </q-input>

              <div class="q-mb-xs text-caption text-grey-7">Contrasena</div>
              <q-input
                v-model="password"
                outlined
                dense
                :type="showPassword ? 'text' : 'password'"
                placeholder="Ingresa tu contrasena"
                :rules="[v => !!v || 'Ingrese su contrasena']"
              >
                <template v-slot:prepend>
                  <q-icon name="lock" size="18px" />
                </template>
                <template v-slot:append>
                  <q-icon
                    :name="showPassword ? 'visibility' : 'visibility_off'"
                    size="18px"
                    class="cursor-pointer"
                    @click="showPassword = !showPassword"
                  />
                </template>
              </q-input>

              <div class="row items-center q-mt-xs q-mb-sm">
                <q-checkbox v-model="rememberMe" label="Recuerdame" color="primary" dense />
                <q-space />
                <q-btn
                  flat
                  dense
                  no-caps
                  class="text-weight-medium link-muted"
                  label="Olvidaste tu contrasena?"
                  @click="forgotPassword"
                />
              </div>

              <q-btn
                color="primary"
                label="Entrar"
                class="full-width btnLogin"
                no-caps
                unelevated
                :loading="loading"
                type="submit"
              />
            </q-card-section>

            <q-card-section class="q-pt-none text-center login-footer">
              <div class="text-body2">
                Aun no tienes cuenta?
                <q-btn flat dense no-caps class="text-weight-medium" label="Solicitar acceso" @click="goRegister" />
              </div>

              <div class="text-caption text-grey-6 footer-copy">
                © {{ year }} Latinas Editores. Todos los derechos reservados.
              </div>
            </q-card-section>
          </q-card>
        </q-form>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script>
export default {
  name: 'LoginLatinasEditores',
  data () {
    return {
      username: '',
      password: '',
      showPassword: false,
      rememberMe: false,
      loading: false
    }
  },
  computed: {
    year () {
      return new Date().getFullYear()
    }
  },
  methods: {
    login () {
      this.loading = true

      this.$axios.post('/login', {
        username: this.username,
        password: this.password
      })
        .then(res => {
          const { user, token } = res.data

          this.$axios.defaults.headers.common.Authorization = `Bearer ${token}`

          if (this.$store) {
            this.$store.isLogged = true
            this.$store.user = user
            this.$store.permissions = (user.permissions || []).map(p => p.name)
          }

          localStorage.setItem('tokenTicket', token)
          localStorage.setItem('user', JSON.stringify(user))

          if (this.$alert && this.$alert.success) {
            this.$alert.success('Bienvenido', user?.name || '')
          }

          this.$router.push('/')
        })
        .catch(err => {
          const msg = err?.response?.data?.message || 'Error de autenticacion'
          if (this.$alert && this.$alert.error) this.$alert.error(msg, 'Error')
        })
        .finally(() => {
          this.loading = false
        })
    },

    forgotPassword () {
      if (this.$alert && this.$alert.info) this.$alert.info('Funcion no disponible por ahora')
    },

    goRegister () {
      if (this.$alert && this.$alert.info) this.$alert.info('Solicitud de acceso no disponible por ahora')
    }
  }
}
</script>

<style scoped>
.login-layout {
  background-image: url('./../bg.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  min-height: 100vh;
}

.full-height {
  min-height: 100vh;
  position: relative;
  overflow: hidden;
}

.login-bg-overlay {
  position: absolute;
  inset: 0;
  backdrop-filter: blur(3px);
  background:
    linear-gradient(135deg, rgba(46, 12, 12, 0.62), rgba(108, 16, 16, 0.46)),
    radial-gradient(1200px 800px at 70% 40%, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.24));
}

.login-glow {
  position: absolute;
  z-index: 0;
  width: 220px;
  height: 220px;
  border-radius: 999px;
  filter: blur(12px);
  background: rgba(255, 255, 255, 0.1);
}

.login-glow-left {
  top: 10%;
  left: -70px;
}

.login-glow-right {
  right: -60px;
  bottom: 8%;
  background: rgba(187, 8, 8, 0.16);
}

.login-wrap {
  position: relative;
  z-index: 1;
  max-width: 396px;
  margin: 0 auto;
  padding: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
}

.login-card {
  width: 100%;
  max-width: 372px;
  border-radius: 18px;
  background: rgba(255, 255, 255, 0.86);
  backdrop-filter: blur(14px);
  -webkit-backdrop-filter: blur(14px);
  border: 1px solid rgba(255, 255, 255, 0.72);
  box-shadow:
    0 18px 50px rgba(26, 11, 11, 0.18),
    0 4px 18px rgba(26, 11, 11, 0.08);
}

.login-top {
  padding-top: 18px;
  padding-bottom: 6px;
}

.login-body {
  padding-left: 18px;
  padding-right: 18px;
}

.login-footer {
  padding-left: 18px;
  padding-right: 18px;
  padding-bottom: 16px;
}

.login-logo {
  margin: 0 auto;
}

.brand-chip {
  display: inline-block;
  padding: 5px 10px;
  border-radius: 999px;
  background: rgba(187, 8, 8, 0.08);
  border: 1px solid rgba(187, 8, 8, 0.1);
  font-size: 0.74rem;
}

.brand-title {
  font-size: 1.5rem;
  font-weight: 700;
  line-height: 1.1;
  color: #241919;
}

.brand-subtitle {
  max-width: 240px;
  margin: 6px auto 0;
  color: #6f6161;
  font-size: 0.84rem;
  line-height: 1.32;
}

.link-muted {
  color: #6b7280 !important;
}

.link-muted:hover {
  color: var(--q-primary) !important;
}

.btnLogin {
  height: 40px;
  border-radius: 10px;
  transition: all 0.25s ease;
}

.btnLogin:hover {
  background-color: #fff !important;
  color: var(--q-primary) !important;
  outline: 1px solid var(--q-primary) !important;
}

.footer-copy {
  margin-top: 10px;
}

@media (max-width: 768px) {
  .login-wrap {
    max-width: 92vw;
    padding: 8px;
  }

  .login-card {
    border-radius: 16px;
  }

  .login-body,
  .login-footer {
    padding-left: 16px;
    padding-right: 16px;
  }

  .brand-title {
    font-size: 1.35rem;
  }
}
</style>
