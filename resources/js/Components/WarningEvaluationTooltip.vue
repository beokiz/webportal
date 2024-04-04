<template>
    <v-col cols="12" sm="2">
        <v-tooltip location="bottom">
            <template v-slot:activator="{ props }">
                <v-icon v-bind="props" icon="mdi-alert" color="orange"></v-icon>
            </template>

            Anzahl eingereichter Einschätzungen und gemeldete Kinder in dem Zeitraum weichen voneinander ab
        </v-tooltip>
    </v-col>
</template>
