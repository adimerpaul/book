<template>
  <q-layout view="lHh Lpr lFf">
    <q-header class="bg-white text-black" bordered>
      <q-toolbar>
        <q-btn
          flat
          color="primary"
          :icon="leftDrawerOpen ? 'keyboard_double_arrow_left' : 'keyboard_double_arrow_right'"
          aria-label="Menu"
          @click="toggleLeftDrawer"
          dense
        />

        <div class="row items-center q-gutter-sm">
          <div class="text-subtitle1 text-weight-medium" style="line-height: 0.95">
            Latinas Editores <br>
            <q-badge color="warning" text-color="black" v-if="roleText" class="text-bold">
              {{ roleText }}
            </q-badge>
          </div>
        </div>

        <q-space />

        <div class="row items-center q-gutter-sm">
          <q-btn-dropdown flat unelevated no-caps dropdown-icon="expand_more">
            <template v-slot:label>
              <div class="row items-center no-wrap q-gutter-sm">
                <q-avatar rounded>
                  <q-img
                    v-if="$store.user && $store.user.avatar"
                    :src="`${$url}../../images/${$store.user.avatar}`"
                    width="40px"
                    height="40px"
                  />
                  <q-icon name="person" v-else />
                </q-avatar>

                <div class="text-left" style="line-height: 1">
                  <div class="ellipsis" style="max-width: 130px;">
                    {{ $store.user ? $store.user.username : '' }}
                  </div>
                  <q-chip
                    dense
                    size="10px"
                    :color="$filters.color($store.user ? $store.user.role : '')"
                    text-color="white"
                  >
                    {{ $store.user ? $store.user.role : '' }}
                  </q-chip>
                </div>
              </div>
            </template>

            <q-item clickable v-close-popup>
              <q-item-section>
                <q-item-label class="text-grey-7">
                  Permisos asignados
                </q-item-label>
                <q-item-label caption class="q-mt-xs">
                  <div class="row q-col-gutter-xs" style="min-width: 150px; max-width: 150px;">
                    <q-chip
                      v-for="(p, i) in $store.permissions"
                      :key="i"
                      dense
                      color="grey-3"
                      text-color="black"
                      size="12px"
                      class="q-mr-xs q-mb-xs"
                    >
                      {{ p }}
                    </q-chip>
                    <q-badge v-if="!$store.permissions || !$store.permissions.length" color="grey-5" outline>
                      Sin permisos
                    </q-badge>
                  </div>
                </q-item-label>
              </q-item-section>
            </q-item>

            <q-separator />

            <q-item clickable v-close-popup @click="$router.push('/cambiar-contrasena')">
              <q-item-section avatar>
                <q-icon name="vpn_key" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Cambiar Contrasena</q-item-label>
              </q-item-section>
            </q-item>

            <q-item clickable v-ripple @click="logout" v-close-popup>
              <q-item-section avatar>
                <q-icon name="logout" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Salir</q-item-label>
              </q-item-section>
            </q-item>
          </q-btn-dropdown>
        </div>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      bordered
      show-if-above
      :width="220"
      :breakpoint="500"
      class="bg-primary text-white"
    >
      <q-list class="q-pb-none">
        <q-item-label header class="text-center q-pa-none q-pt-md">
          <q-avatar size="64px" class="q-mb-sm bg-white" rounded>
            <q-img src="/logo.png" width="90px" />
          </q-avatar>
          <div class="text-weight-bold text-white">LATINAS EDITORES</div>
          <div class="text-caption text-white">Panel de gestion editorial</div>
        </q-item-label>

        <q-item-label header class="q-px-md text-grey-3 q-mt-sm">
          Modulos del sistema
        </q-item-label>

        <q-item
          dense
          to="/"
          exact
          clickable
          class="menu-item"
          active-class="menu-active"
          v-close-popup
          v-if="hasPermission('Dashboard')"
        >
          <q-item-section avatar>
            <q-icon name="dashboard" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="menu-label">Dashboard</q-item-label>
          </q-item-section>
        </q-item>

        <q-item
          dense
          to="/usuarios"
          exact
          clickable
          class="menu-item"
          active-class="menu-active"
          v-close-popup
          v-if="hasPermission('Usuarios')"
        >
          <q-item-section avatar>
            <q-icon name="people" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="menu-label">Usuarios</q-item-label>
          </q-item-section>
        </q-item>

        <q-item
          dense
          to="/autores"
          exact
          clickable
          class="menu-item"
          active-class="menu-active"
          v-close-popup
        >
          <q-item-section avatar>
            <q-icon name="library_books" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="menu-label">Autores</q-item-label>
          </q-item-section>
        </q-item>

        <q-item
          dense
          to="/libros"
          exact
          clickable
          class="menu-item"
          active-class="menu-active"
          v-close-popup
        >
          <q-item-section avatar>
            <q-icon name="auto_stories" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="menu-label">Libros</q-item-label>
          </q-item-section>
        </q-item>

        <q-item
          dense
          to="/hero-sliders"
          exact
          clickable
          class="menu-item"
          active-class="menu-active"
          v-close-popup
        >
          <q-item-section avatar>
            <q-icon name="view_carousel" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="menu-label">Carrusel</q-item-label>
          </q-item-section>
        </q-item>

        <q-item
          dense
          to="/mis-graderias"
          exact
          clickable
          class="menu-item"
          active-class="menu-active"
          v-close-popup
          v-if="hasPermission('Graderias')"
        >
          <q-item-section avatar>
            <q-icon name="stadium" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="menu-label">Mis graderias</q-item-label>
          </q-item-section>
        </q-item>

        <q-item
          dense
          to="/mis-graderias/nueva"
          exact
          clickable
          class="menu-item"
          active-class="menu-active"
          v-close-popup
          v-if="hasPermission('Graderias')"
        >
          <q-item-section avatar>
            <q-icon name="add_box" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="menu-label">Nueva graderia</q-item-label>
          </q-item-section>
        </q-item>

        <q-item
          dense
          to="/cambiar-contrasena"
          exact
          clickable
          class="menu-item"
          active-class="menu-active"
          v-close-popup
        >
          <q-item-section avatar>
            <q-icon name="vpn_key" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="menu-label">Cambiar Contrasena</q-item-label>
          </q-item-section>
        </q-item>

        <div class="q-pa-md">
          <div class="text-white-7 text-caption">
            Latinas Editores v{{ $version }}
          </div>
          <div class="text-white-7 text-caption">
            © {{ new Date().getFullYear() }} Sistema editorial
          </div>
        </div>

        <q-item clickable class="text-white" @click="logout" v-close-popup>
          <q-item-section avatar>
            <q-icon name="logout" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Salir</q-item-label>
          </q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <q-page-container class="bg-grey-2">
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
export default {
  name: 'MainLayout',
  data () {
    return {
      leftDrawerOpen: false
    }
  },
  computed: {
    roleText () {
      const role = this.$store && this.$store.user ? this.$store.user.role : ''
      if (!role) return ''
      return role
    }
  },
  methods: {
    toggleLeftDrawer () {
      this.leftDrawerOpen = !this.leftDrawerOpen
    },
    hasPermission (perm) {
      return this.$store && this.$store.permissions
        ? this.$store.permissions.includes(perm)
        : false
    },
    logout () {
      this.$alert.dialog('Desea salir del sistema?')
        .onOk(() => {
          this.$axios.post('/logout')
            .then(() => {
              this.$store.isLogged = false
              this.$store.user = {}
              this.$store.permissions = []
              localStorage.removeItem('tokenSIL')
              this.$router.push('/login')
            })
            .catch(() => {
              this.$store.isLogged = false
              this.$store.user = {}
              this.$store.permissions = []
              localStorage.removeItem('tokenSIL')
              this.$router.push('/login')
            })
        })
    }
  }
}
</script>

<style scoped>
.menu-item {
  border-radius: 10px;
  margin: 4px 8px;
  padding: 6px 8px;
  transition: background-color 0.2s ease, transform 0.2s ease;
}

.menu-item:hover {
  background: rgba(255, 255, 255, 0.1);
}

.menu-label {
  color: rgba(255, 255, 255, 0.94);
}

.menu-active {
  background: rgba(255, 255, 255, 0.24);
  color: #fff !important;
  border-radius: 10px;
  box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.16);
}

.menu-active .menu-label,
.menu-active .q-icon {
  color: #ffffff !important;
}
</style>
