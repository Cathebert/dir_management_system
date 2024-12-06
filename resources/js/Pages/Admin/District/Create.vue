<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3"
import {
    mdiMultimedia,
    mdiArrowLeftBoldOutline,
    mdiEarth
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

    name: null,
    alt: null,

})
</script>

<template>
    <LayoutAuthenticated>

        <Head title="Add District" />
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiEarth" title="Add District" main>
                <BaseButton :route-name="route('admin.district.index')" :icon="mdiArrowLeftBoldOutline" label="Back"
                    color="white" rounded-full small />
            </SectionTitleLineWithButton>
            <CardBox form @submit.prevent="form.post(route('admin.district.store'))">

                <FormField label="District Name" :class="{ 'text-red-400': form.errors.name }">
                    <FormControl v-model="form.name" type="text" placeholder="Enter District Name" :error="form.errors.name">
                        <div class="text-red-400 text-sm" v-if="form.errors.name">
                            {{ form.errors.name }}
                        </div>
                    </FormControl>
                </FormField>

                <FormField label="Alt" :class="{ 'text-red-400': form.errors.alt }">
                    <FormControl v-model="form.alt" type="text" placeholder="Enter Alt" :error="form.errors.alt">
                        <div class="text-red-400 text-sm" v-if="form.errors.alt">
                            {{ form.errors.alt }}
                        </div>
                    </FormControl>
                </FormField>

                <!--FormField label="file" :class="{ 'text-red-400': form.errors.file }">
                    <FormControl v-model="form.file" type="file" placeholder="Select File" :error="form.errors.file"
                        @input="form.file = $event.target.files[0]">
                        <div class="text-red-400 text-sm" v-if="form.errors.file">
                            {{ form.errors.file }}
                        </div>
                    </FormControl>
                </FormField-->

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
