<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3"
import {
    mdiMultimedia,
    mdiArrowLeftBoldOutline,
    mdiEarth,
    mdiServer,
} from "@mdi/js"
import { ref, onMounted } from 'vue';
import LayoutAuthenticated from "@/Layouts/Admin/LayoutAuthenticated.vue"
import SectionMain from "@/Components/SectionMain.vue"
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue"
import CardBox from "@/Components/CardBox.vue"
import BaseButton from "@/Components/BaseButton.vue"
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

    if (props.coordinates.length > 0) {
        for (var i = 0; i < props.coordinates.length; i++) {
            console.log(props.coordinates[i]['latitude'])
            var location = { lat: props.coordinates[i]['latitude'], lng: props.coordinates[i]['longitude'] }
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



    //L.marker([e.latlng.lat, e.latlng.lng],).addTo(map);
});
const props = defineProps({
    service: {
        type: Object,
        default: () => ({}),
    },

    coordinates: {
        type: Object,
        default: () => ({}),
    },
    beneficiaries: {
        type: Object,
        default: () => ({}),
    },
})

const form = useForm({
service_name: props.service.service_name,

    coordinates: [],

})
console.log(props.service)
</script>

<template>
    <LayoutAuthenticated>

        <Head title="View Service" />
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiServer" title="View Service" main>
                <BaseButton :route-name="route('admin.service.index')" :icon="mdiArrowLeftBoldOutline" label="Back"
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
                                Service Name
                            </td>
                            <td data-label="Service Name">
                                {{ service.service_name ?? service.name }}
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
                                Organization
                            </td>
                            <td data-label="Organization">
                                {{ service.organization }}
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
                                District
                            </td>
                            <td data-label="District">
                                {{ service.district }}[{{ service.areas }}]
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
                                Beneficiary Type
                            </td>
                            <td data-label="Beneficiary Type">
                                <ul>
                                    <li v-for="value in beneficiaries" :key="value.id">
                                        {{ value.name }}
                                    </li>
                                </ul>
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
                                Beneficiary Number
                            </td>
                            <td data-label="Beneficiary Number">
                                {{ service.number_of_beneficiary }}
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
                                Start Date
                            </td>
                            <td data-label="Start Date">
                                {{ service.start_date ??"N/A"}}
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
                                End Date
                            </td>
                            <td data-label="End Date">
                                {{ service.end_date ?? "N/A" }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </CardBox>
            <CardBox>
                <div id="map" style="height: 500px; margin-top:5px;">
                </div>
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
