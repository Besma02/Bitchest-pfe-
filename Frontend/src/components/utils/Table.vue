<template>
  <div class="overflow-x-auto shadow-lg rounded-lg">
    <table class="min-w-full bg-white rounded-lg overflow-hidden">
      <thead>
        <tr class="bg-gray-100">
          <th
            class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b"
          >
            #
          </th>
          <th
            v-for="(header, index) in headers"
            :key="index"
            class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b"
          >
            {{ header.text }}
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(row, rowIndex) in formattedData"
          :key="rowIndex"
          class="border-b transition duration-300 ease-in-out hover:bg-gray-50"
        >
          <td class="px-4 py-3 text-sm font-semibold text-gray-700">
            {{ rowIndex + 1 }}
          </td>
          <td
            v-for="(cell, cellIndex) in row"
            :key="cellIndex"
            class="px-4 py-3 text-sm text-gray-700"
          >
            {{ cell }}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  props: {
    data: {
      type: Array,
      required: true,
    },
    headers: {
      type: Array,
      required: true,
    },
    tableName: {
      type: String,
    },
  },
  computed: {
    formattedData() {
      return this.data.map((row) => {
        let newRow = {};
        Object.entries(row).forEach(([key, value]) => {
          if (
            (this.tableName == "investmentDetails" && key === "profitOrLoss") ||
            (this.tableName == "investmentDetails" && key === "percentage") ||
            key === "user_id"
          )
            return;

          newRow[key] = this.formatValue(value);
        });
        return newRow;
      });
    },
  },
  methods: {
    formatValue(value) {
      if (typeof value === "number" || (!isNaN(value) && value !== "")) {
        let num = parseFloat(value);
        if (num === 0) return "-"; // Remplace 0 par "-"
        return new Intl.NumberFormat("fr-FR", {
          minimumFractionDigits: 0,
          maximumFractionDigits: 5,
          useGrouping: true,
        })
          .format(num)
          .replace(/\s/g, " ");
      }
      return value;
    },
  },
};
</script>

<style scoped>
/* Styles de base */
table {
  border-collapse: separate;
  border-spacing: 0;
  border-radius: 8px;
  width: 100%;
}

th {
  background-color: #f8f9fa;
}

tr:hover {
  background-color: #f1f5f9;
}

/* Responsive design */
@media (max-width: 640px) {
  th,
  td {
    padding: 0.5rem; /* Réduire le padding pour les petits écrans */
    font-size: 0.75rem; /* Réduire la taille de la police */
  }

  th {
    font-size: 0.7rem; /* Taille de police plus petite pour les en-têtes */
  }

  .overflow-x-auto {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch; /* Améliorer le défilement sur mobile */
  }
}

@media (min-width: 641px) and (max-width: 1024px) {
  th,
  td {
    padding: 0.75rem; /* Ajuster le padding pour les tablettes */
    font-size: 0.875rem; /* Taille de police intermédiaire */
  }
}

@media (min-width: 1025px) {
  th,
  td {
    padding: 1rem; /* Padding par défaut pour les écrans larges */
    font-size: 0.9rem; /* Taille de police par défaut */
  }
}
</style>
