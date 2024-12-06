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

const props = defineProps({
    district: {
        type: Object,
        default: () => ({}),
    },
    typeOptions: {
        type: Object,
        default: () => ({}),
    },
})

const form = useForm({
    _method: 'put',
    name: props.district.name,

})
</script>

<template>
    <LayoutAuthenticated>

        <Head title="Update Organization" />
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiHome" title="Update District" main>
                <BaseButton :route-name="route('admin.district.index')" :icon="mdiArrowLeftBoldOutline" label="Back"
                    color="white" rounded-full small />
            </SectionTitleLineWithButton>
            <CardBox form @submit.prevent="form.post(route('admin.district.update', props.district.id))">
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
