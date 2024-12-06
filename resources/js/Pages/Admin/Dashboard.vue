<script setup>
import { Head } from '@inertiajs/vue3'
import { computed, ref, onMounted } from 'vue'
import { useMainStore } from '@/Stores/main'
import {
  mdiAccountMultiple,
  mdiCartOutline,
  mdiChartTimelineVariant,
  mdiFinance,
  mdiMonitorCellphone,
  mdiReload,
  mdiGithub,
  mdiChartPie,
  mdiServer,
  mdiHome
} from '@mdi/js'
import * as chartConfig from '@/Components/Charts/chart.config.js'
//import LineChart from '@/Components/Charts/LineChart.vue'
import SectionMain from '@/Components/SectionMain.vue'
import CardBoxWidget from '@/Components/CardBoxWidget.vue'
import CardBox from '@/Components/CardBox.vue'
import TableSampleClients from '@/Components/TableSampleClients.vue'
//import NotificationBar from '@/Components/NotificationBar.vue'
//import BaseButton from '@/Components/BaseButton.vue'
import CardBoxTransaction from '@/Components/CardBoxTransaction.vue'
import CardBoxClient from '@/Components/CardBoxClient.vue'
import LayoutAuthenticated from '@/Layouts/Admin/LayoutAuthenticated.vue'
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'
//import SectionBannerStarOnGitHub from '@/Components/SectionBannerStarOnGitHub.vue'
const chartData = ref(null)
const fillChartData = () => {
  chartData.value = chartConfig.sampleChartData()
}
onMounted(() => {
  fillChartData()
})
const mainStore = useMainStore()

/* Fetch sample data */
mainStore.fetchSampleClients()
mainStore.fetchSampleHistory()


const clientBarItems = computed(() => mainStore.clients.slice(0, 4))
const transactionBarItems = computed(() => mainStore.history)
const props = defineProps({
    default_logo: null,
    organization_count:null,
    service_count:null,
    users:null,
    items: {
        type: Object,
        default: () => ({}),
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
    can: {
        type: Object,
        default: () => ({}),
    },
})

</script>

<template>
    <LayoutAuthenticated>

        <Head title="Dashboard" />
        <SectionMain>


            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mb-6">
                <CardBoxWidget :trend=organization_count trend-type="up" color="text-emerald-500" :icon="mdiHome"
                    :number=organization_count label="Organizations" />
                <CardBoxWidget :trend=service_count trend-type="up" color="text-blue-500" :icon="mdiServer"
                    :number=service_count label="Services" />
                <CardBoxWidget :trend=users trend-type="alert" color="text-red-500" :icon="mdiAccountMultiple"
                    :number=users label="Users" />



            </div>



            <!--div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="flex flex-col justify-between">
                    <CardBoxTransaction v-for="(transaction,index) in transactionBarItems" :key="index"
                        :amount="transaction.amount" :date="transaction.date" :business="transaction.business"
                        :type="transaction.type" :name="transaction.name" :account="transaction.account" />
                </div>
                <div class="flex flex-col justify-between">
                    <CardBoxClient v-for="client in clientBarItems" :key="client.id" :name="client.name"
                        :login="client.login" :date="client.created" :progress="client.progress" />
                </div>
            </div>-->

            <!--SectionBannerStarOnGitHub /-->







            <CardBox :icon="mdiMonitorCellphone" title="Organization" has-table>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>
                                <Sort label="Name" attribute="name" />
                            </th>
                            <th>Logo</th>
                            <th>Url</th>

                            <th>Description</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Email</th>


                        </tr>
                    </thead>

                    <tbody>

                        <tr v-for=" organization in items.data" :key="organization.id">
                            <td data-label="File">
                                {{ organization.id }}
                            </td>
                            <td data-label="Name">
                                <Link :href="route('admin.organization.show', organization.id)" class="
                    no-underline
                    hover:underline
                    text-cyan-600
                    dark:text-cyan-400
                  ">
                                {{ organization.name }}
                                </Link>
                            </td>
                            <td data-label="File">
                                <div class="w-12 rounded">
                                    <div v-if="organization.logo === null">
                                        <img :src="default_logo" :alt="organization.url" style="border-radius: 50%;"
                                            class="block h-auto w-full max-w-full bg-gray-100 dark:bg-slate-800" />
                                    </div>
                                    <div v-else>
                                        <img :src="organization.logo" :alt="organization.url"
                                            style="border-radius: 50%;"
                                            class="block h-auto w-full max-w-full bg-gray-100 dark:bg-slate-800" />
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a :href="organization.url" class="
                    no-underline
                    hover:underline
                    text-cyan-600
                    dark:text-cyan-400
                  ">
                                    {{ organization.name }}
                                </a>
                            </td>

                            <td>
                                {{ organization.description }}
                            </td>
                            <td>
                                {{ organization.address }}
                            </td>
                            <td>
                                {{ organization.phone }}
                            </td>
                            <td>
                                {{ organization.email }}
                            </td>


                        </tr>
                    </tbody>
                </table>
                <div class="py-4">
                    <Pagination :data="items" />
                </div>
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
