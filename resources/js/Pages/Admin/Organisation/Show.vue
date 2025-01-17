<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3"
import {
    mdiMultimedia,
    mdiArrowLeftBoldOutline,
    mdiSquareEditOutline,
    mdiEarth,
    mdiPlus,
} from "@mdi/js"
import LayoutAuthenticated from "@/Layouts/Admin/LayoutAuthenticated.vue"
import SectionMain from "@/Components/SectionMain.vue"
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue"
import CardBox from "@/Components/CardBox.vue"
import BaseButton from "@/Components/BaseButton.vue"
import { countBy } from "lodash"

const props = defineProps({
   organization: {
        type: Object,
        default: () => ({}),
    },
    services: {
        type: Object,
        default: () => ({}),
    }
    ,

    can: {
        type: Object,
        default: () => ({}),
    },
})
</script>

<template>
    <LayoutAuthenticated>

        <Head title="View Organization " />
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiHome" title="View Organization" main>
                <BaseButton :route-name="route('admin.organization.index')" :icon="mdiArrowLeftBoldOutline" label="Back"
                    color="white" rounded-full small />
            </SectionTitleLineWithButton>
            <CardBox class="mb-6">
                <table>
                    <tbody>


                        <tr>
                            <td class="
                  p-4
                  pl-8
                  text-slate-500
                  dark:text-slate-400
                  hidden
                  lg:block
                ">
                                Name
                            </td>
                            <td data-label="Name">
                                {{ organization.name }}
                            </td>
                        </tr>



                        <tr>
                            <td class="
                  p-4
                  pl-8
                  text-slate-500
                  dark:text-slate-400
                  hidden
                  lg:block
                ">
                                Description
                            </td>
                            <td data-label="Description">
                                {{ organization.description }}
                            </td>
                        </tr>


                        <tr>
                            <td class="
                  p-4
                  pl-8
                  text-slate-500
                  dark:text-slate-400
                  hidden
                  lg:block
                ">
                                Address
                            </td>
                            <td data-label="Address">
                                {{ organization.address }}
                            </td>
                        </tr>



                        <tr>
                            <td class="
                  p-4
                  pl-8
                  text-slate-500
                  dark:text-slate-400
                  hidden
                  lg:block
                ">
                                Phone
                            </td>
                            <td data-label="Phone">
                                {{ organization.phone }}
                            </td>
                        </tr>


                        <tr>
                            <td class="
                  p-4
                  pl-8
                  text-slate-500
                  dark:text-slate-400
                  hidden
                  lg:block
                ">
                                Email
                            </td>
                            <td data-label="Email">
                                {{ organization.email }}
                            </td>
                        </tr>


                        <tr>
                            <td class="
                  p-4
                  pl-8
                  text-slate-500
                  dark:text-slate-400
                  hidden
                  lg:block
                ">
                                Website
                            </td>
                            <td data-label="Website">
                                {{ organization.url }}
                            </td>
                        </tr>
                        <tr>
                            <td class="
                  p-4
                  pl-8
                  text-slate-500
                  dark:text-slate-400
                  hidden
                  lg:block
                ">
                                Total Services
                            </td>
                            <td data-label="Total Services">
                                {{ services.length}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </CardBox>

            <CardBox>
                <SectionTitleLineWithButton main :title="organization.name + ' Services #'+services.length">
                    <BaseButton :route-name="route('admin.organization.addService',organization.id)" :icon="mdiPlus"
                        label="Add" color="info" rounded-full small />

                </SectionTitleLineWithButton>


                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>
                                <Sort label="Name" attribute="name" />Name
                            </th>

                            <th>Description</th>
                            <th>Location</th>
                            <th>Estimated Beneficiaries</th>
                            <th>Charge</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr v-for="service in services" :key="service.id">
                            <td data-label="#">
                                {{ service.id }}
                            </td>
                            <td data-label="Name">
                                <Link :href="route('admin.service.show', service.id)" class="
                    no-underline
                    hover:underline
                    text-cyan-600
                    dark:text-cyan-400
                  ">
                                {{ service.name }}
                                </Link>
                            </td>
                            <td data-label="description">
                                {{ service.description }}

                            </td>
                            <td data-label="Location">
                                {{ service.district_name }}

                            </td>

                            <td data-label="Estimated Beneficiaries">
                                {{ service.number_of_beneficiary }}


                            </td>
                            <td data-label="Service Charge">
                                {{ service.charge_name }}



                            </td>
                            <td data-label="Start Date">
                                {{ service.start_date }}

                            </td>
                            <td data-label="End Date">
                                {{ service.end_date }}



                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="py-4">
                    <Pagination :data="services" />
                </div>
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
