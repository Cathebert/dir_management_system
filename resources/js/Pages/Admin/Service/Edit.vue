<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3"
import {
    mdiMultimedia,
    mdiArrowLeftBoldOutline,
    mdiServer
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
import VueMultiselect from 'vue-multiselect'
import "leaflet/dist/leaflet.css"
import L from 'leaflet';


let map = ref(null)
const center = [-13.9499962, 33.6999972];
let marker = null
let circle = null
let greenIcon = null


onMounted(() => {
    map = L.map('map').setView(center, 6);
    L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {

        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map);

    if (props.coordinates.length>0){
        for (var i = 0; i < props.coordinates.length; i++){
            console.log(props.coordinates[i]['latitude'])
            var location = { lat: props.coordinates[i]['latitude'], lng:props.coordinates[i]['longitude'] }
            form.coordinates.push(location)
        }

        //props.coordinates.forEach()
        form.coordinates.forEach(function (coord) {
            circle = L.circle(coord, {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: 5000
            }).addTo(map);
        });
}
    map.on('click', function (e) {
        if (marker) {
            marker.setLatLng(e.latlng);

        } else {
            greenIcon = L.icon({
                iconUrl: 'https://leafletjs.com/examples/custom-icons/leaf-red.png',
                shadowUrl: 'https://leafletjs.com/examples/custom-icons/leaf-shadow.png',

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
        form.longitude = e.latlng.lng
        // document.getElementById('latitude').value = e.latlng.lat;
        // document.getElementById('longitude').value = e.latlng.lng;
    })
    map.addEventListener("keypress", function (event) {
        console.log(event.originalEvent.key)
        // If the user presses the "Enter" key on the keyboard
        if (event.originalEvent.key === "s" || event.originalEvent.key === "S") {
            var location = { lat: form.latitude, lng: form.longitude }
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

    let TAs = ref({})
const props = defineProps({
    service: {
        type: Object,
        default: () => ({}),

    },
        organizations: {
            type: Object,
            default: () => ({}),

        },
    organization: {
        type: Object,
        default: () => ({}),

    },
        types: {
            type: Object,
            default: () => ({}),
        },
    selected_type:{
    type: Object,
    default: () => ({}),
},
        scopes: {
            type: Array,
            default: () => ({}),
        },
    scope_selected: {
        type: Array,
        default: () => ({}),
    },
    beneficiaries: {
            type: Array,
            default: () => ({}),
        },
    beneficiary_selected: {
        type: Array,
        default: () => ({}),
    },
        numbers: {
            type: Array,
            default: () => ({}),
        },

    charges: {
            type: Array,
            default: () => ({}),
        },
    charge_selected: {
        type: Array,
        default: () => ({}),
    },
        locations: {
            type: Array,
            default: () => ({}),
        },
districts: {
            type: Object,
            default: () => ({}),
        },
    district: {
        type: Object,
        default: () => ({}),
    },
        tas: {
            type: Object,
            default: () => ({}),
        }
,
        coordinates: {
        type: Object,
        default: () => ({}),
    }


})

const form = useForm({
    _method: 'put',
    name: props.service.name,
    description: props.service.description,
    district: props.districts.id ?? props.district.id,
    organization:props.organizations.id ?? props.organization.id,
    ta: props.tas.id??props.service.ta,
    specific_area: props.service.areas,
    beneficiary: props.beneficiary_selected ?? props.beneficiaries,
    start: props.service.start_date,
    end: props.service.end_date,
    charge: props.charges.id??props.charge_selected.id,
    number: props.service.number_of_beneficiary,
    unique: null,
    location: null,
    challenges: null,
    latitude: null,
    longitude: null,
    coordinates: [],
    other_b: null
})



function getTAs(id) {
    if (id) {
        let route = window.routes.getTas
        axios
            .get(route + '/' + id)
            .then(function (result) {
                TAs = result.data.states;
                console.log(TAs);
                form.ta = TAs
            })



    }
    else {
        form.ta = null
        TAs = []

    }
}


</script>

<template>
    <LayoutAuthenticated>

        <Head title="Update Service" />
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiServer" title="Update Service" main>
                <BaseButton :route-name="route('admin.service.index')" :icon="mdiArrowLeftBoldOutline" label="Back"
                    color="white" rounded-full small />
            </SectionTitleLineWithButton>
            <CardBox form @submit.prevent="form.post(route('admin.service.update', props.service.id))">
                <!--- <FormField label="Name" :class="{ 'text-red-400': form.errors.type }">
                    <FormControl v-model="form.type" type="select" placeholder="--Select Type--"
                        :error="form.errors.type" :options="typeOptions">
                        <div class="text-red-400 text-sm" v-if="form.errors.type">
                            {{ form.errors.type }}
                        </div>
                    </FormControl>
                </FormField>-->
                <FormField label="Service Name" :class="{ 'text-red-400': form.errors.name }">
                    <FormControl v-model="form.name" type="text" placeholder="Enter Service Name"
                        :error="form.errors.name">
                        <div class="text-red-400 text-sm" v-if="form.errors.name">
                            {{ form.errors.name }}
                        </div>
                    </FormControl>
                </FormField>




                <FormField label="Service Description" :class="{ 'text-red-400': form.errors.description }">
                    <FormControl v-model="form.description" type="text" :error="form.errors.description">
                        <div class="text-red-400 text-sm" v-if="form.errors.description">
                            {{ form.errors.description }}
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
                <FormField label="District" :class="{ 'text-red-400': form.errors.district }">

                    <FormControl v-model="form.district" type="select" label="name" placeholder="--Select District--"
                        :error="form.errors.district" :options="districts" @update:modelValue="getTAs($event)">
                        <div class="text-red-400 text-sm" v-if="form.errors.district">
                            {{ form.errors.district }}
                        </div>
                    </FormControl>
                </FormField>

                <FormField label="T/A" :class="{ 'text-red-400': form.errors.district }">
                    <FormControl v-model="form.ta" type="select" :options="TAs" label="name"
                        placeholder="--Select T/A--" :error="form.errors.ta">
                        <div class="text-red-400 text-sm" v-if="form.errors.district">
                            {{ form.errors.district }}
                        </div>
                    </FormControl>
                </FormField>


                <FormField label="Specific Area within district" :class="{ 'text-red-400': form.errors.district }">
                    <FormControl v-model="form.specific_area" type="textarea" label="Specific Area"
                        :error="form.errors.specific_area" placeholder="Enter Specific Areas within the district">
                        <div class="text-red-400 text-sm" v-if="form.errors.specific_area">
                            {{ form.errors.district }}
                        </div>
                    </FormControl>
                </FormField>
                <!--multiple selection hidden-->
                <!--div>
                    <FormField label="Service Type" :class="{ 'text-red-400': form.errors.start }">
                        <VueMultiselect v-model="form.type" :options="types" :multiple="true" :close-on-select="true"
                            placeholder="--Select Service Type--" label="name" track-by="name"
                            :style="{ 'background-color' : activeColor }" />
                    </FormField>
                    <div class="text-red-400 text-sm" v-if="form.errors.type">
                        {{ form.errors.type }}

                    </div>

                </div-->




                <FormField label="End Date" :class="{ 'text-red-400': form.errors.end }">
                    <FormControl v-model="form.end" type="date" :error="form.errors.end">
                        <div class="text-red-400 text-sm" v-if="form.errors.end">
                            {{ form.errors.end }}
                        </div>
                    </FormControl>
                </FormField>



                <div>
                    <FormField label="Organization Sector" :class="{ 'text-red-400': form.errors.beneficiary }">
                        <VueMultiselect v-model="form.beneficiary" :options="beneficiaries" :multiple="true"
                            :close-on-select="true" placeholder="--Select Organization sectors--" label="name"
                            track-by="name" :style="{ 'background-color': activeColor }" />
                    </FormField>
                    <div class="text-red-400 text-sm" v-if="form.errors.beneficiary">
                        {{ form.errors.beneficiary }}

                    </div>

                </div>
                &nbsp;&nbsp;
                <div v-show="form.beneficiary === 6">
                    <FormField label="Other Type Beneficiary" :class="{ 'text-red-400': form.errors.name }">
                        <FormControl v-model="form.other_b" type="text" placeholder="Other" :error="form.errors.name">
                            <div class="text-red-400 text-sm" v-if="form.errors.name">
                                {{ form.errors.name }}
                            </div>
                        </FormControl>
                    </FormField>
                </div>
                <FormField label="Beneficiary Estimates" :class="{ 'text-red-400': form.errors.number }">
                    <FormControl v-model="form.number" type="number" :error="form.errors.number">
                        <div class="text-red-400 text-sm" v-if="form.errors.number">
                            {{ form.errors.number }}
                        </div>
                    </FormControl>
                </FormField>


                <FormField label="Service Charge" :class="{ 'text-red-400': form.errors.charge }">
                    <FormControl v-model="form.charge" type="select" label="Service Charge"
                        placeholder="--Select Service Charge" :error="form.errors.charge" :options="charges">
                        <div class="text-red-400 text-sm" v-if="form.errors.charge">
                            {{ form.errors.number }}
                        </div>
                    </FormControl>
                </FormField>

                <FormField label="press S when desired location is pinned" style="color: brown;"
                    :class="{ 'text-red-400': form.errors.unique }" hidden>
                    <FormControl v-model="form.latitude" type="hidden" :error="form.errors.latitude" id="latitude">
                        <div class="text-red-400 text-sm" v-if="form.errors.latitude">
                            {{ form.errors.latitude }}
                        </div>
                    </FormControl>
                </FormField>
                <CardBox>


                    <div id="map" style="height: 500px; margin-top:5px;" hidden>
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
