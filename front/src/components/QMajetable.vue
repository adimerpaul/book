<template>
  <div class="majetable">

    <q-markup-table flat bordered dense wrap-cells class="majetable-table">
      <thead>
        <tr>
          <th
            v-for="column in columns"
            :key="column.name"
            :class="headerClass(column)"
          >
            {{ column.label }}
          </th>
        </tr>
      </thead>

      <tbody v-if="rows.length">
        <tr v-for="row in rows" :key="row[rowKey]">
          <td
            v-for="column in columns"
            :key="column.name"
            :class="bodyClass(column)"
          >
            <slot
              :name="`body-cell-${column.name}`"
              :row="row"
              :col="column"
              :value="fieldValue(row, column)"
            >
              {{ fieldValue(row, column) }}
            </slot>
          </td>
        </tr>
      </tbody>

      <tbody v-else>
        <tr>
          <td :colspan="columns.length" class="text-center text-grey-6 q-py-lg">
            {{ noDataLabel }}
          </td>
        </tr>
      </tbody>
    </q-markup-table>

    <div class="row items-center justify-between q-gutter-sm q-mt-md">
      <div class="row items-center q-gutter-sm">
        <span class="text-caption text-grey-7">Filas por pagina</span>
        <q-select
          :model-value="pagination.rowsPerPage"
          :options="rowsPerPageOptions"
          dense
          outlined
          emit-value
          map-options
          style="min-width: 92px"
          @update:model-value="changeRowsPerPage"
        />
        <span class="text-caption text-grey-7">
          {{ rangeText }}
        </span>
      </div>

      <q-pagination
        :model-value="pagination.page"
        :max="pagesNumber"
        :max-pages="6"
        boundary-numbers
        direction-links
        color="primary"
        @update:model-value="changePage"
      />
    </div>
  </div>
</template>

<script>
export default {
  name: 'QMajetable',
  props: {
    rows: {
      type: Array,
      default: () => []
    },
    columns: {
      type: Array,
      default: () => []
    },
    rowKey: {
      type: String,
      default: 'id'
    },
    pagination: {
      type: Object,
      required: true
    },
    rowsPerPageOptions: {
      type: Array,
      default: () => ([
        { label: '5', value: 5 },
        { label: '10', value: 10 },
        { label: '20', value: 20 },
        { label: '50', value: 50 }
      ])
    },
    noDataLabel: {
      type: String,
      default: 'Sin registros'
    }
  },
  emits: ['request'],
  computed: {
    pagesNumber () {
      const rowsNumber = Number(this.pagination.rowsNumber || 0)
      const rowsPerPage = Number(this.pagination.rowsPerPage || 10)
      return Math.max(1, Math.ceil(rowsNumber / rowsPerPage))
    },
    rangeText () {
      const total = Number(this.pagination.rowsNumber || 0)
      if (!total) return '0 registros'

      const page = Number(this.pagination.page || 1)
      const rowsPerPage = Number(this.pagination.rowsPerPage || 10)
      const from = ((page - 1) * rowsPerPage) + 1
      const to = Math.min(page * rowsPerPage, total)
      return `${from}-${to} de ${total}`
    }
  },
  methods: {
    fieldValue (row, column) {
      if (typeof column.field === 'function') return column.field(row)
      if (typeof column.field === 'string') return row[column.field]
      return row[column.name]
    },
    headerClass (column) {
      return [
        'text-weight-bold',
        column.align === 'right' ? 'text-right' : '',
        column.align === 'center' ? 'text-center' : ''
      ]
    },
    bodyClass (column) {
      return [
        column.align === 'right' ? 'text-right' : '',
        column.align === 'center' ? 'text-center' : ''
      ]
    },
    changePage (page) {
      this.$emit('request', {
        pagination: {
          ...this.pagination,
          page
        }
      })
    },
    changeRowsPerPage (rowsPerPage) {
      this.$emit('request', {
        pagination: {
          ...this.pagination,
          page: 1,
          rowsPerPage
        }
      })
    }
  }
}
</script>

<style scoped>
.majetable-table {
  background: white;
}
</style>
