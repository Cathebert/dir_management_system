
<script setup>
import {
    BarController } from 'chart.js'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)
const props = defineProps({
    data: {
        type: Object,
        required: true,
        default: () => ({ data: [40, 20, 12] }),
    },
    labels:{
        type:Object,
        required:true,
        default: () => (['January', 'February', 'March'])
    }
})

const root = ref(null)
let chart
onMounted(() => {
    chart = new Chart(root.value, {
    name: 'BarChart',
    components: { BarController },
    data() {
        return {
            chartData: {
                labels: props.labels,
                datasets: props.data,
            },
            chartOptions: {
                responsive: true
            }
        }
    }
    })
})
const chartData = computed(() => props.data)

watch(chartData, data => {
    if (chart) {
        chart.data = data
        chart.update()
    }
})
</script>
<template>
  <canvas ref="root" />
</template>
