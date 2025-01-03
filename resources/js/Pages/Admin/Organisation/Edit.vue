<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3"
import {
    mdiMultimedia,
    mdiArrowLeftBoldOutline
} from "@mdi/js"
import LayoutAuthenticated from "@/Layouts/Admin/LayoutAuthenticated.vue"
import SectionMain from "@/Components/SectionMain.vue"
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue"
import CardBox from "@/Components/CardBox.vue"
import FormField from '@/Components/FormField.vue'
import FormControl from '@/Components/FormControl.vue'
import BaseButton from '@/Components/BaseButton.vue'
import BaseButtons from '@/Components/BaseButtons.vue'
import VueMultiselect from 'vue-multiselect'
const props = defineProps({
    organization: {
        type: Object,
        default: () => ({}),
    },
    typeOptions: {
        type: Object,
        default: () => ({}),
    },
    selected: {
        type: Object,
        default: () => ({}),
    },
    sectors: {
        type: Object,
        default: () => ({}),
    },
    sector_selected:{
        type: Object,
        default: () => ({}),
    }
})

const form = useForm({
    _method: 'put',
    name: props.organization.name,
    type: props.typeOptions.id??props.selected.id,
    description: props.organization.description,
    sector: props.sector_selected??props.sectors,
    url: props.organization.url,
    phone:props.organization.phone,
    address:props.organization.address,
    email:props.organization.email,
    file:null,
})

</script>

<template>
    <LayoutAuthenticated>

        <Head title="Update Organization" />
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiHome" title="Update Organization" main>
                <BaseButton :route-name="route('admin.organization.index')" :icon="mdiArrowLeftBoldOutline" label="Back"
                    color="white" rounded-full small />
            </SectionTitleLineWithButton>
            <CardBox form @submit.prevent="form.post(route('admin.organization.update', props.organization.id))">
                <!--- <FormField label="Name" :class="{ 'text-red-400': form.errors.type }">
                    <FormControl v-model="form.type" type="select" placeholder="--Select Type--"
                        :error="form.errors.type" :options="typeOptions">
                        <div class="text-red-400 text-sm" v-if="form.errors.type">
                            {{ form.errors.type }}
                        </div>
                    </FormControl>
                </FormField>-->
                <FormField label="Name" :class="{ 'text-red-400': form.errors.name }">
                    <FormControl v-model="form.name" type="text" placeholder="Enter Name" :error="form.errors.name">
                        <div class="text-red-400 text-sm" v-if="form.errors.name">
                            {{ form.errors.name }}
                        </div>
                    </FormControl>
                </FormField>
                <FormField label="Type" :class="{ 'text-red-400': form.errors.type }">
                    <FormControl v-model="form.type" type="select" placeholder="--Select Type--"
                        :error="form.errors.type" :options="typeOptions">
                        <div class="text-red-400 text-sm" v-if="form.errors.type">
                            {{ form.errors.type }}
                        </div>
                    </FormControl>
                </FormField>
                <FormField label="Mission/Vision" :class="{ 'text-red-400': form.errors.description }">
                    <FormControl v-model="form.description" type="textarea" placeholder="Enter Mission/Vision"
                        :error="form.errors.description">
                        <div class="text-red-400 text-sm" v-if="form.errors.description">
                            {{ form.errors.description }}
                        </div>
                    </FormControl>
                </FormField>


                <FormField label="Address" :class="{ 'text-red-400': form.errors.address}">
                    <FormControl v-model="form.address" type="text" placeholder="Enter Address"
                        :error="form.errors.address">
                        <div class="text-red-400 text-sm" v-if="form.errors.address">
                            {{ form.errors.address }}
                        </div>
                    </FormControl>
                </FormField>
                <FormField label="Phone" :class="{ 'text-red-400': form.errors.phone }">
                    <FormControl v-model="form.phone" type="text" placeholder="Enter Phone" :error="form.errors.phone">
                        <div class="text-red-400 text-sm" v-if="form.errors.phone">
                            {{ form.errors.phone }}
                        </div>
                    </FormControl>
                </FormField>

                <div>
                    <FormField label="Organization Sector" :class="{ 'text-red-400': form.errors.sector}">
                        <VueMultiselect v-model="form.sector" :options="sectors" :multiple="true"
                            :close-on-select="true" placeholder="--Select Organization sectors--" label="name"
                            track-by="name" :style="{ 'background-color': activeColor }" />
                    </FormField>
                    <div class="text-red-400 text-sm" v-if="form.errors.sector">
                        {{ form.errors.sector }}

                    </div>

                </div>
                &nbsp;&nbsp;


                <FormField label="URL" :class="{ 'text-red-400': form.errors.url }">
                    <FormControl v-model="form.url" type="text" placeholder="Enter Url" :error="form.errors.url">
                        <div class="text-red-400 text-sm" v-if="form.errors.url">
                            {{ form.errors.url }}
                        </div>
                    </FormControl>
                </FormField>
                <FormField label="Email" :class="{ 'text-red-400': form.errors.email }">
                    <FormControl v-model="form.email" type="text" placeholder="Enter Email" :error="form.errors.email">
                        <div class="text-red-400 text-sm" v-if="form.errors.email">
                            {{ form.errors.email }}
                        </div>
                    </FormControl>
                </FormField>
                <div class="w-32 rounded">
                    <div v-if="organization.logo === null">
                        <img :src="organization.logo" :alt="organization.url"
                            class="block h-auto w-full max-w-full bg-gray-100 dark:bg-slate-800" />
                    </div>
                    <div v-else>
                        <img :src="organization.logo" :alt="organization.url"
                            class="block h-auto w-full max-w-full bg-gray-100 dark:bg-slate-800" />
                    </div>
                </div>
                <FormField label="Logo" :class="{ 'text-red-400': form.errors.file }">
                    <FormControl v-model="form.file" type="file" placeholder="Select File" :error="form.errors.file"
                        @input="form.file = $event.target.files[0]">
                        <div class="text-red-400 text-sm" v-if="form.errors.file">
                            {{ form.errors.file }}
                        </div>
                    </FormControl>
                </FormField>
                <template #footer>
                    <BaseButtons>
                        <BaseButton type="submit" color="info" label="Submit" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing" />
                    </BaseButtons>
                </template>
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
