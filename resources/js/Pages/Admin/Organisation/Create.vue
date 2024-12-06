<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3"
import {
    mdiMultimedia,
    mdiArrowLeftBoldOutline,
    mdiHome
} from "@mdi/js"
import LayoutAuthenticated from "@/Layouts/Admin/LayoutAuthenticated.vue"
import SectionMain from "@/Components/SectionMain.vue"
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue"
import CardBox from "@/Components/CardBox.vue"
import FormField from '@/Components/FormField.vue'
import FormControl from '@/Components/FormControl.vue'

import BaseButton from '@/Components/BaseButton.vue'
import BaseButtons from '@/Components/BaseButtons.vue'

const props = defineProps({
    typeOptions: {
        type: Object,
        default: () => ({}),
    },
})

const form = useForm({

    name:null,
    description:null,
    email:null,
    address:null,
    phone:null,
    url:null,
    file: null
})
</script>

<template>
    <LayoutAuthenticated>

        <Head title="Create media" />
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiHome" title="Add Organization" main>
                <BaseButton :route-name="route('admin.organization.index')" :icon="mdiArrowLeftBoldOutline" label="Back"
                    color="white" rounded-full small />
            </SectionTitleLineWithButton>
            <CardBox form @submit.prevent="form.post(route('admin.organization.store'))">
                <!--FormField label="Type" :class="{ 'text-red-400': form.errors.type }">
                    <FormControl v-model="form.type" type="select" placeholder="--Select Type--"
                        :error="form.errors.type" :options="typeOptions">
                        <div class="text-red-400 text-sm" v-if="form.errors.type">
                            {{ form.errors.type }}
                        </div>
                    </FormControl>
                </FormField>-->
                <FormField label="Name" :class="{ 'text-red-400': form.errors.name }">
                    <FormControl v-model="form.name" type="text" placeholder="Enter Organization Name"
                        :error="form.errors.name">
                        <div class="text-red-400 text-sm" v-if="form.errors.name">
                            {{ form.errors.name }}
                        </div>
                    </FormControl>
                </FormField>

                <FormField label="Mission/Vision" :class="{ 'text-red-400': form.errors.description }">
                    <FormControl v-model="form.description" type="textarea" placeholder="Enter Mission /Vision"
                        :error="form.errors.description">
                        <div class="text-red-400 text-sm" v-if="form.errors.description">
                            {{ form.errors.description }}
                        </div>
                    </FormControl>
                </FormField>


                <FormField label="URL" :class="{ 'text-red-400': form.errors.url }">
                    <FormControl v-model="form.url" type="text" placeholder="e.g: https://www.indemw.tech"
                        :error="form.errors.url">
                        <div class="text-red-400 text-sm" v-if="form.errors.url">
                            {{ form.errors.url }}
                        </div>
                    </FormControl>
                </FormField>

                <FormField label="Address" :class="{ 'text-red-400': form.errors.address }">
                    <FormControl v-model="form.address" type="text" placeholder="Enter Email" :error="form.errors.address">
                        <div class="text-red-400 text-sm" v-if="form.errors.address">
                            {{ form.errors.address }}
                        </div>
                    </FormControl>
                </FormField>

                <FormField label="Phone" :class="{ 'text-red-400': form.errors.phone }">
                    <FormControl v-model="form.phone" type="text" placeholder="Enter Email" :error="form.errors.phone">
                        <div class="text-red-400 text-sm" v-if="form.errors.phone">
                            {{ form.errors.phone }}
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
