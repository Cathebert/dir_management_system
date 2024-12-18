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

const props = defineProps({
    district: {
        type: Object,
        default: () => ({}),
    },
    traditional:{
        type:Object,
        default:()=>({}),
    }
    ,
    items: {
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

        <Head title="View District " />
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiEarth" title="View District" main>
                <BaseButton :route-name="route('admin.district.index')" :icon="mdiArrowLeftBoldOutline" label="Back"
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
                                {{ district.name}}
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
                                Created
                            </td>
                            <td data-label="Created">
                                {{ new Date(district.created_at).toLocaleString() }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </CardBox>

            <CardBox>
                <SectionTitleLineWithButton main title="Traditional Authority(T/A)">
                    <BaseButton :route-name="route('admin.district.addArea', props.district.id)" :icon="mdiPlus"
                        label="Add" color="white" rounded-full small />
                </SectionTitleLineWithButton>


                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>
                                <Sort label="Name" attribute="name" />Name
                            </th>

                            <th>Date Created</th>
                            <th v-if="can.edit">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr v-for="traditional in items.data" :key="traditional.id">
                            <td data-label="#">
                                {{ traditional.id }}
                            </td>
                            <td data-label="Name">
                                <Link :href="route('admin.district.show', traditional.id)" class="
                    no-underline
                    hover:underline
                    text-cyan-600
                    dark:text-cyan-400
                  ">
                                {{ traditional.name }}
                                </Link>
                            </td>
                            <td data-label="created_at">
                                {{ new Date(traditional.created_at).toLocaleString().slice(0, 10) }}
                            </td>
                            <td v-if="can.edit" class="before:hidden lg:w-1 whitespace-nowrap">
                                <BaseButtons type="justify-start lg:justify-end" no-wrap>
                                    <BaseButton v-if="can.edit" :route-name="route('admin.district.edit', traditional.id)"
                                        color="info" :icon="mdiSquareEditOutline" small />

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
