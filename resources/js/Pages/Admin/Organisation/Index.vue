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

const props = defineProps({
    default_logo:null,
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
    path:String,
})

const form = useForm({
    search: props.filters.search,
})

const formDelete = useForm({})

function destroy(id) {
    if (confirm("Are you sure you want to delete?")) {
        formDelete.delete(route("admin.organization.destroy", id))
    }
}
</script>

<template>
    <LayoutAuthenticated>

        <Head title="Organization" />
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiHome" title="Organization" main>
                <BaseButton v-if="can.delete" :route-name="route('admin.organization.create')" :icon="mdiPlus"
                    label="Add" color="info" rounded-full small />
            </SectionTitleLineWithButton>
            <NotificationBar :key="Date.now()" v-if="$page.props.flash.message" color="success"
                :icon="mdiAlertBoxOutline">
                {{ $page.props.flash.message }}
            </NotificationBar>
            <CardBox class="mb-6" has-table>
                <form @submit.prevent="form.get(route('admin.organization.index'))">
                    <div class="py-2 flex">
                        <div class="flex pl-4">
                            <input type="search" v-model="form.search" class="
                  rounded-md
                  shadow-sm
                  border-gray-300
                  focus:border-indigo-300
                  focus:ring
                  focus:ring-indigo-200
                  focus:ring-opacity-50
                " placeholder="Search" />
                            <BaseButton label="Search" type="submit" color="info"
                                class="ml-4 inline-flex items-center px-4 py-2" />
                        </div>
                    </div>
                </form>
            </CardBox>
            <CardBox class="mb-6" has-table>
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
                            <th>Date Created</th>
                            <th v-if="can.edit || can.delete">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr v-for=" organization in items.data" :key="organization.id">
                            <td data-label="#">
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
                            <td data-label="Logo">
                                <div class="w-12 rounded">
                                    <div v-if="organization.logo ===null">
                                        <img :src="default_logo" :alt="organization.url" style="border-radius: 50%;"
                                            class="block h-auto w-full max-w-full bg-gray-100 dark:bg-slate-800" />
                                    </div>
                                    <div v-else>
                                        <img :src="path+'/'+organization.logo" :alt="organization.url"
                                            style="border-radius: 50%;"
                                            class="block h-auto w-full max-w-full bg-gray-100 dark:bg-slate-800" />
                                    </div>
                                </div>
                            </td>
                            <td v-if="organization.url" data-label="Website">
                                <a :href="organization.url" target="_blank" class="
                    no-underline
                    hover:underline
                    text-cyan-600
                    dark:text-cyan-400
                  ">
                                    {{ organization.name }}
                                </a>
                            </td>
                            <td v-else>

                            </td>
                            <td data-label="Description">
                                {{ organization.description }}
                            </td>
                            <td data-label="Address">
                                {{ organization.address }}
                            </td>
                            <td data-label="Phone">
                                {{ organization.phone }}
                            </td>
                            <td data-label="Email">
                                {{ organization.email }}
                            </td>
                            <td data-label="created_at">
                                {{ new Date(organization.created_at).toLocaleString().slice(0, 10) }}
                            </td>
                            <td v-if="can.edit || can.delete" class="before:hidden lg:w-1 whitespace-nowrap">
                                <BaseButtons type="justify-start lg:justify-end" no-wrap>
                                    <BaseButton v-if="can.edit"
                                        :route-name="route('admin.organization.edit', organization.id)" color="info"
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
