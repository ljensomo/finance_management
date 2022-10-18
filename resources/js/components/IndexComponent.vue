<template>
  <div class="row">
    <div class="col-md-6">
      <h3 class="text-center">Weekly Expense Summary</h3>
      <Bar v-if="loaded" :chart-data="chartData" :height="height"/>
    </div>
    <div class="col-md-6"></div>
  </div>
</template>

<script>
import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

export default {
  name: 'BarChart',
  components: { Bar },
  data: () => ({
    loaded: false,
    chartData: null,
    height: {
      type: Number,
      default: 100
    },
  }),
    beforeMount(){
    const vm = this;
    axios.get('/dashboard/expense-summary')
        .then(function(response){
            const data = response.data;
            vm.chartData = data;
            vm.loaded = true;
        })
        .catch(function(error){
            showError(error);
        });
    },
}
</script>
