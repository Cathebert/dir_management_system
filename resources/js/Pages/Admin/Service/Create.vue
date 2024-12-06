<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3"
import {
    mdiMultimedia,
    mdiArrowLeftBoldOutline,
    mdiHome
} from "@mdi/js"
import { ref, onMounted } from 'vue';
import LayoutAuthenticated from "@/Layouts/Admin/LayoutAuthenticated.vue"
import SectionMain from "@/Components/SectionMain.vue"
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue"
import CardBox from "@/Components/CardBox.vue"
import FormField from '@/Components/FormField.vue'
import FormControl from '@/Components/FormControl.vue'

import BaseButton from '@/Components/BaseButton.vue'
import BaseButtons from '@/Components/BaseButtons.vue'

import "leaflet/dist/leaflet.css"
import L from 'leaflet';

let map =ref(null)
const center = [-13.9499962, 33.6999972];
let marker=null
let circle=null
let greenIcon=null


onMounted(() => {
   map= L.map('map').setView(center, 6);
    L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {

        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map);

    map.on('click',function(e){
        if (marker) {
            marker.setLatLng(e.latlng);

        } else {
             greenIcon = L.icon({
                 iconUrl: 'https://leafletjs.com/examples/custom-icons/leaf-red.png',
                 shadowUrl:'https://leafletjs.com/examples/custom-icons/leaf-shadow.png',

                iconSize: [38, 95], // size of the icon
                shadowSize: [50, 64], // size of the shadow
                iconAnchor: [22, 94], // point of the icon which will correspond to marker's location
                shadowAnchor: [4, 62],  // the same for the shadow
                popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
            });
            /*circle = L.circle([e.latlng.lat, e.latlng.lng], {
                color: 'green',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: 5000
            }).addTo(map);*/
            marker = L.marker(e.latlng, { icon: greenIcon }).addTo(map);
        }
        console.log(e.latlng.lat)

        form.latitude = e.latlng.lat
        form.longitude=e.latlng.lng
       // document.getElementById('latitude').value = e.latlng.lat;
       // document.getElementById('longitude').value = e.latlng.lng;
    })
    map.addEventListener("keypress", function (event) {
        console.log(event.originalEvent.key)
        // If the user presses the "Enter" key on the keyboard
        if (event.originalEvent.key === "s" || event.originalEvent.key === "S") {
            var location = { lat: form.latitude, lng:form.longitude}
            form.coordinates.push(location)



            // Here's where you iterate over the array of coordinate objects.
            form.coordinates.forEach(function (coord) {
                 circle = L.circle(coord, {
                    color: 'red',
                    fillColor: '#f03',
                    fillOpacity: 0.5,
                    radius: 5000
                }).addTo(map);
            });

            // Set the view to where some of the circles are drawn.

            alert('location saved')


        }
    });

//L.marker([e.latlng.lat, e.latlng.lng],).addTo(map);
});


const props = defineProps({
    organizations: {
        type: Array,
        default: () => ({}),

    },
    types:{
        type: Object,
        default: () => ({}),
    },

    scopes: {
        type: Array,
        default: () => ({}),
    },

   beneficies: {
        type: Array,
        default: () => ({}),
    },
    numbers: {
        type: Array,
        default: () => ({}),
    },
    locations: {
        type: Array,
        default: () => ({}),
    },
    districts: {
        type: Array,
        default: () => ({}),
    },




})

const form = useForm({

    name: null,
    district:null,
    organization:null,
    type:null,
    scope: null,
    beneficiary:null,
    number:null,
    unique:null,
    location:null,
    challenges: null,
    latitude: null,
    longitude: null,
    coordinates:[]


})
</script>

<template>
    <LayoutAuthenticated>

        <Head title="Add Service" />
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiServer" title="Add Service" main>
                <BaseButton :route-name="route('admin.service.index')" :icon="mdiArrowLeftBoldOutline" label="Back"
                    color="white" rounded-full small />
            </SectionTitleLineWithButton>
            <CardBox form @submit.prevent="form.post(route('admin.service.store'))">
                <!--FormField label="Type" :class="{ 'text-red-400': form.errors.type }">
                    <FormControl v-model="form.type" type="select" placeholder="--Select Type--"
                        :error="form.errors.type" :options="typeOptions">
                        <div class="text-red-400 text-sm" v-if="form.errors.type">
                            {{ form.errors.type }}
                        </div>
                    </FormControl>
                </FormField>-->
                <FormField label="Name" :class="{ 'text-red-400': form.errors.name }">
                    <FormControl v-model="form.name" type="text" placeholder="Enter Service Name"
                        :error="form.errors.name">
                        <div class="text-red-400 text-sm" v-if="form.errors.name">
                            {{ form.errors.name }}
                        </div>
                    </FormControl>
                </FormField>


                <FormField label="District" :class="{ 'text-red-400': form.errors.district }">
                    <FormControl v-model="form.district" type="select" label="name"
                        placeholder="--Select Organization--" :error="form.errors.district"
                        :options="districts">
                        <div class="text-red-400 text-sm" v-if="form.errors.district">
                            {{ form.errors.district }}
                        </div>
                    </FormControl>
                </FormField>
                <FormField label="Organization" :class="{ 'text-red-400': form.errors.organization }">
                    <FormControl v-model="form.organization" type="select" label="name"
                        placeholder="--Select Organization--" :error="form.errors.organization"
                        :options="organizations">
                        <div class="text-red-400 text-sm" v-if="form.errors.organization">
                            {{ form.errors.organization }}
                        </div>
                    </FormControl>
                </FormField>
                <FormField label="Type Of Service" :class="{ 'text-red-400': form.errors.type }">
                    <FormControl v-model="form.type" type="select" label="name" placeholder="--Select Service Type--"
                        :error="form.errors.type" :options="types">
                        <div class="text-red-400 text-sm" v-if="form.errors.type">
                            {{ form.errors.type }}
                        </div>
                    </FormControl>
                </FormField>

                <FormField label="Scope Of Service" :class="{ 'text-red-400': form.errors.scope }">
                    <FormControl v-model="form.scope" type="select" name="label" placeholder="--Select Service Scope--"
                        :error="form.errors.scope" :options="scopes">
                        <div class="text-red-400 text-sm" v-if="form.errors.scope">
                            {{ form.errors.scope }}
                        </div>
                    </FormControl>
                </FormField>


                <FormField label="Type Of Beneficiary" :class="{ 'text-red-400': form.errors.beneficiary }">
                    <FormControl v-model="form.beneficiary" type="select" label="name"
                        placeholder="--Select Beneficiary--" :error="form.errors.beneficiary" :options="beneficies">
                        <div class="text-red-400 text-sm" v-if="form.errors.beneficiary">
                            {{ form.errors.beneficiary }}
                        </div>
                    </FormControl>
                </FormField>



                <FormField label="Number Of Beneficiaries/month" :class="{ 'text-red-400': form.errors.number }">
                    <FormControl v-model="form.number" type="select" label="name"
                        placeholder="--Select Beneficiary Target per Month--" :error="form.errors.number"
                        :options="numbers">
                        <div class="text-red-400 text-sm" v-if="form.errors.number">
                            {{ form.errors.number }}
                        </div>
                    </FormControl>
                </FormField>
                <FormField label=" Locations  you operate in within the district"
                    :class="{ 'text-red-400': form.errors.location }">
                    <FormControl v-model="form.location" type="select" label="name"
                        placeholder="--Select Number of location in operation--" :error="form.errors.location"
                        :options="locations">
                        <div class="text-red-400 text-sm" v-if="form.errors.location">
                            {{ form.errors.location }}
                        </div>
                    </FormControl>
                </FormField>

                <FormField label="Challenges Faced" :class="{ 'text-red-400': form.errors.challenges }">
                    <FormControl v-model="form.challenges" type="textarea" placeholder="Write Challenges faced"
                        :error="form.errors.challenges">
                        <div class="text-red-400 text-sm" v-if="form.errors.challenges">
                            {{ form.errors.challenges }}
                        </div>
                    </FormControl>
                </FormField>


                <FormField label="Are there any unique services you offer that others do not"
                    :class="{ 'text-red-400': form.errors.unique }">
                    <FormControl v-model="form.unique" type="textarea" placeholder="Write unique services offered"
                        :error="form.errors.unique">
                        <div class="text-red-400 text-sm" v-if="form.errors.unique">
                            {{ form.errors.unique }}
                        </div>
                    </FormControl>
                </FormField>

                <FormField label="press S when desired location is pinned" style="color: brown;"
                    :class="{ 'text-red-400': form.errors.unique }">
                    <FormControl v-model="form.latitude" type="hidden" :error="form.errors.latitude" id="latitude">
                        <div class="text-red-400 text-sm" v-if="form.errors.latitude">
                            {{ form.errors.latitude }}
                        </div>
                    </FormControl>
                </FormField>
                <CardBox>


                    <div id="map" style="height: 500px; margin-top:5px;">
                    </div>
                </CardBox>

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
