<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3"
import {
  mdiAccountKey,
  mdiArrowLeftBoldOutline
} from "@mdi/js"
import { ref, onMounted, watch, watchEffect } from 'vue';
import LayoutAuthenticated from "@/Layouts/Admin/LayoutAuthenticated.vue"
import SectionMain from "@/Components/SectionMain.vue"
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue"
import CardBox from "@/Components/CardBox.vue"
import FormField from '@/Components/FormField.vue'
import FormControl from '@/Components/FormControl.vue'
import FormCheckRadioGroup from '@/Components/FormCheckRadioGroup.vue'
import BaseDivider from '@/Components/BaseDivider.vue'
import BaseButton from '@/Components/BaseButton.vue'
import BaseButtons from '@/Components/BaseButtons.vue'
let organization = ref({})
const props = defineProps({
  roles: {
    type: Object,
    default: () => ({}),
  },
    districts: {
        type: Object,
        default: () => ({}),
    },
  organizations: {
        type: Object,
        default: () => ({}),
    },

})

const form = useForm({
  name: '',
  email: '',
  district:'',
  organization:'',
  password: '',
  password_confirmation: '',
  roles: props.roles
})



function getDistrictOrganization(id) {
    if (id) {
        let route = window.routes.getDistrictOrganization
        axios
            .get(route + '/' + id)
            .then(function (result) {

                organization = result.data.organization;
                console.log(organization);
                form.organization = result.data.organization;
            })



    }
    else {
        form.organization = null
      organization= []

    }

}
</script>

<template>
    <LayoutAuthenticated>

        <Head title="Add user" />
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiAccountKey" title="Add user" main>
                <BaseButton :route-name="route('admin.user.index')" :icon="mdiArrowLeftBoldOutline" label="Back"
                    color="white" rounded-full small />
            </SectionTitleLineWithButton>
            <CardBox form @submit.prevent="form.post(route('admin.user.store'))">
                <FormField label="Name" :class="{ 'text-red-400': form.errors.name }">
                    <FormControl v-model="form.name" type="text" placeholder="Enter Name" :error="form.errors.name">
                        <div class="text-red-400 text-sm" v-if="form.errors.name">
                            {{ form.errors.name }}
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

                <FormField label="Password" :class="{ 'text-red-400': form.errors.password }">
                    <FormControl v-model="form.password" type="password" placeholder="Enter Password"
                        :error="form.errors.password">
                        <div class="text-red-400 text-sm" v-if="form.errors.password">
                            {{ form.errors.password }}
                        </div>
                    </FormControl>
                </FormField>
                <FormField label="Password Confirmation" :class="{ 'text-red-400': form.errors.password }">
                    <FormControl v-model="form.password_confirmation" type="password"
                        placeholder="Enter Password Confirmation" :error="form.errors.password">
                        <div class="text-red-400 text-sm" v-if="form.errors.password">
                            {{ form.errors.password }}
                        </div>
                    </FormControl>
                </FormField>

                <FormField label="District" :class="{ 'text-red-400': form.errors.district }">

                    <FormControl v-model="form.district" type="select" label="name" placeholder="--Select District--"
                        :error="form.errors.district" :options="districts" @update:modelValue="getDistrictOrganization($event)">
                        <div class="text-red-400 text-sm" v-if="form.errors.district">
                            {{ form.errors.district }}
                        </div>

                    </FormControl>

                </FormField>
                <span style="color: brown;">Leave blank if user oversees all organization in the districts</span>
                <FormField label="Organization" :class="{ 'text-red-400': form.errors.organization }">

                    <FormControl v-model="form.organization" type="select" label="name"
                        placeholder="--Select Organization--" :error="form.errors.organization"
                        :options="organization">
                        <div class="text-red-400 text-sm" v-if="form.errors.organization">
                            {{ form.errors.organization }}
                        </div>

                    </FormControl>

                </FormField>


                <BaseDivider />

                <!--  <FormField label="Roles" wrap-body>
                    <FormCheckRadioGroup v-model="form.roles" name="roles" is-column :options="props.roles" />
                </FormField> -->

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
