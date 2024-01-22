<template>
    <GmapMap
        :center="center"
        :zoom="12.5"
        map-type-id="terrain"
        style="width: 100%; height: 700px"
    >
        <GmapCircle
            :radius="parseFloat(radius)*1609.34"
            :center="{ lat: germanCities.position.lat, lng: germanCities.position.lng}"
            :options="circleOptions"
        />

        <GmapMarker
            :position="centerMarkerPosition"
            :icon="import(`/admin/assets/icons/zip.svg`)"
            :clickable="true"
            :draggable="false"
        />
        <GmapMarker
            :key="index"
            v-for="(m, index) in markers"
            :position="m.position"
            :icon="import(`/admin/assets/icons/prospect.svg`)"
            :clickable="true"
            :draggable="false"
        />
    </GmapMap>
</template>

<script>

export default {
    name: 'RadiusMapComponent',
    props:["center","centerMarkerPosition","germanCities","markers","radius"],
    mapMarker : { url: require('../../../public/admin/assets/icons/zip.svg')},
    data() {
        return {
            // center: {lat: 51.093048, lng: 6.842120},
            // centerMarkerPosition:{lat: 51.233334, lng: 6.783333},
            // germanCities:
            //     {
            //         id: 'duesseldorf',
            //         population: 612178,
            //         position: {
            //             lat: 51.233334, lng: 6.783333
            //         },
            //     },
            // markers: [
            //     {
            //         position: {lat: 51.093048, lng: 6.842120},
            //     },
            //     {
            //         position: { lat: 59.923043, lng: 10.752839 }
            //     },
            //     {
            //         position: { lat: 59.339025, lng: 18.065818 }
            //     },
            //     {
            //         position: { lat: 55.675507, lng: 12.574227 }
            //     },
            //     {
            //         position: { lat: 52.521248, lng: 13.399038 }
            //     },
            //     {
            //         position: { lat: 48.856127, lng: 2.346525 }
            //     }
            // ],
            circleOptions: {
                strokeColor: "#FF0000",
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: "#FF0000",
                fillOpacity: 0.35,
            },
            markerOptions: {
                url: this.mapMarker,
                size: {width: 60, height: 90, f: 'px', b: 'px',},
                scaledSize: {width: 30, height: 45, f: 'px', b: 'px',},
            },
        }
    },
}
</script>
