<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3"
import {
    mdiMultimedia,
    mdiPlus,
    mdiSquareEditOutline,
    mdiTrashCan,
    mdiAlertBoxOutline,
    mdiEarth,
    mdiHome,
    mdiServer,
} from "@mdi/js"
import LayoutAuthenticated from "@/Layouts/Admin/LayoutAuthenticated.vue"
import SectionMain from "@/Components/SectionMain.vue"
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue"
import BaseButton from "@/Components/BaseButton.vue"
import CardBox from "@/Components/CardBox.vue"
import BaseButtons from "@/Components/BaseButtons.vue"
import NotificationBar from "@/Components/NotificationBar.vue"
import Pagination from "@/Components/Admin/Pagination.vue"
import Sort from "@/Components/Admin/Sort.vue"
import FormField from "@/Components/FormField.vue"
import FormControl from "@/Components/FormControl.vue"
import { get } from "lodash"
import axios from "axios"


const props = defineProps({
    default_logo: null,
    items: {
        type: Object,
        default: () => ({}),
    },
    districts: {
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

const form = useForm({
    search: props.filters.search,
    district_id: null,
})

const formDelete = useForm({})

function destroy(id) {
    if (confirm("Are you sure you want to delete?")) {
        formDelete.delete(route("admin.service.destroy", id))
    }
}
const getBeneficiaryType = (id) => {
    let route = window.routes.getBeneficiaryType;
    axios.get(route + '/' + id).then((response) => {

if(response.data.length>0){
    console.log(response.data[0].name)
    var html ="<ul>"

    for(var i=0; i<response.data.length;i++){
        html += "<li>"+response.data[i].name+",</li>"
    }
    html+="</ul>"
    document.getElementById('ty' + id).innerHTML = html
}
else{
    document.getElementById('ty'+ id).innerHTML="N/A"
}
        //return response.data
    }).catch((error) => {
        console.log(error)
    })
};

</script>

<template>
    <LayoutAuthenticated>

        <Head title="Complementary Social Services" />
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiServer"
                :title="' Complementary Social Services #' + items.data.length" main>
                <BaseButton v-if="can.delete" :route-name="route('admin.service.create')" :icon="mdiPlus" label="Add"
                    color="info" rounded-full small />
            </SectionTitleLineWithButton>
            <NotificationBar :key="Date.now()" v-if="$page.props.flash.message" color="success"
                :icon="mdiAlertBoxOutline">
                {{ $page.props.flash.message }}
            </NotificationBar>
            <CardBox class="mb-12" has-table>
                <form @submit.prevent="form.get(route('admin.service.index'))">
                    <div class="py-2 flex">
                        <div class="flex pl-4">
                            <input type="search" v-model="form.search" class="
                  rounded-md
                  shadow-sm
                  border-gray-300
                  focus:border-indigo-300
                  focus:ring

                  focus:ring-indigo-200
                  focus:ring-opacity-100
                " placeholder="Search" style="color:black"  v-if="can.search" />
                            <BaseButton label="Search" type="submit" color="info"
                                class="ml-4 inline-flex items-center px-4 py-2" v-if="can.search" />
                            <FormField label="Filter By District" v-if="can.filter">

                                <FormControl v-model="form.district_id" type="select" label="name" class="
                  rounded-md
                  shadow-sm
                  border-gray-300
                  focus:border-indigo-300
                  focus:ring
                  focus:ring-indigo-200
                  focus:ring-opacity-50" placeholder=" All" :error="form.errors.district_id" :options="districts">
                                    <div class="text-red-400 text-sm" v-if="form.errors.district_id">
                                        {{ form.errors.district_id }}
                                    </div>
                                </FormControl>

                                <BaseButton label="Go" type="submit" color="info"
                                    class="ml-4 inline-flex items-center px-4 py-2" />
                            </FormField>
                        </div>
                    </div>
                </form>
            </CardBox>
            <CardBox class="mb-12" has-table style="overflow-x: auto;">
                <table style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>
                                <Sort label="Name" attribute="s.name" />
                            </th>

                            <th>District</th>
                            <th style="max-width:120px">Service Areas</th>
                            <th>Organization</th>
                            <th>Beneficiary Type</th>
                            <th>Service Charge</th>
                            <th>Beneficiary #</th>
                            <th>Started Date</th>
                            <th>End Date</th>
                            <th>Map</th>
                            <th v-if="can.edit || can.delete">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr v-for=" (organization,index) in items.data" :key="organization.id">
                            <td>

                                {{ index + 1 }}

                            </td>
                            <td data-label="Name">
                                <Link :href="route('admin.service.show', organization.id)" class="
                    no-underline
                    hover:underline
                    text-cyan-600
                    dark:text-cyan-400
                  ">
                                {{ organization.service_name }}
                                </Link>
                            </td>

                            <td data-label="District">

                                {{ organization.district }}

                            </td>
                            <td data-label="Areas" style="max-width:200px; word-wrap: break-word;">
                                {{ organization.areas }}


                            </td>
                            <td data-label="Organization">

                                <Link :href="route('admin.organization.show', organization.organization_id)" class="
                    no-underline
                    hover:underline
                    text-cyan-600
                    dark:text-cyan-400
                  ">
                                {{ organization.organization_name }}
                                </Link>

                            </td>



                            <td data-label="Beneficiary">
                                {{ getBeneficiaryType (organization.id) }}
                                <div :id="`ty${organization.id}`"></div>
                            </td>
                            <td data-label="Service Charge">
                                {{ organization.charge }}
                            </td>
                            <td data-label="# Beneficiary">
                                {{ organization.number_of_beneficiary }}
                            </td>
                            <td data-label="Start Date">
                                {{ organization.start_date ?? 'N/A' }}
                            </td>
                            <td data-label="End Date">
                                {{ organization.end_date??'N/A' }}
                            </td>
                            <td data-label="View">
                                <div class="w-12 rounded">
                                    <div>

                                        <Link :href="route('admin.service.show', organization.id)" class="
                    no-underline
                    hover:underline
                    text-cyan-600
                    dark:text-cyan-400
                  ">
                                        <img :src="default_logo" :alt="organization.url" style="border-radius: 50%;"
                                            class="block h-auto w-full max-w-full bg-gray-100 dark:bg-slate-800" />

                                        </Link>

                                    </div>

                                </div>
                            </td>

                            <td v-if="can.edit || can.delete" class="before:hidden lg:w-1 whitespace-nowrap">
                                <BaseButtons type="justify-start lg:justify-end" no-wrap>
                                    <BaseButton v-if="can.edit"
                                        :route-name="route('admin.service.edit', organization.id)" color="info"
                                        :icon="mdiSquareEditOutline" small />
                                    <BaseButton v-if="can.delete" color="danger" :icon="mdiTrashCan" small
                                        @click="destroy(organization.id)" />
                                </BaseButtons>
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
