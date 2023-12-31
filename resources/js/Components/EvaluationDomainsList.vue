<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
import { computed } from 'vue';

const props = defineProps({
    domains: Array,
    ratings: Array,
    age: Number,
    errors: {
        type: Object,
        default: {},
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits();

// Computed
const ageData = computed(() => {
    return props.age;
});

const preparedDomains = computed(() => {
    if (ageData.value) {
        let localPreparedDomains = props.domains.map(domain => ({
            ...domain,
            subdomains: domain.subdomains.map(subdomain => ({
                ...subdomain,
                milestones: subdomain.milestones.filter(milestone => {
                    return parseFloat(milestone.age) === parseFloat(ageData.value);
                }),
            })).filter(subdomain => subdomain.milestones.length > 0),
        })).filter(domain => domain.subdomains.length > 0);

        emit('updateDomainsData', localPreparedDomains);

        return localPreparedDomains;
    } else {
        return props.domains;
    }
});

const domainColors = computed(() => {
    let data = {};

    props.ratings.map(function(item) {
        data[`domain_${item.domain}`] = item.color ?? null;

        return item;
    });

    return data;
});

const hasMilestones = computed(() => {
    return preparedDomains.value.length > 0;
});

const updateValue = (newRatings) => {
    emit('updateDomainsData', preparedDomains);
    emit('updateRatingData', newRatings);
};
</script>

<template>
    <div v-if="hasMilestones">
        <div class="domains-list-container"
             v-for="(domain, index) in preparedDomains"
             :key="domain.id">

            <template v-if="domain.subdomains && domain.subdomains.length > 0">
                <h3 :class="{ 'green': domainColors[`domain_${domain.id}`] === 'green', 'yellow': domainColors[`domain_${domain.id}`] === 'yellow', 'red': domainColors[`domain_${domain.id}`] === 'red' }">
                    {{domain.name}}
                </h3>

                <div class="subdomains-list-container"
                     v-for="subdomain in domain.subdomains"
                     :key="subdomain.id">
                    <div class="subdomains-list-head">
                        <h4>{{subdomain.name}}</h4>
                        <div v-for="heading in ['Noch Nicht', 'Ansatzweise', 'Weitgehend', 'Zuverlassig']" class="radio-wrap radio-head">
                            <span>{{heading}}</span>
                        </div>
                    </div>

                    <div class="milestone-list-container"
                         v-for="milestone in subdomain.milestones"
                         :key="milestone.id">

                        <template v-if="ratings[milestone.domain_index] && ratings[milestone.domain_index].milestones[milestone.index]">
                            <h5 :class="{ error: errors[`ratings.${milestone.domain_index}.milestones.${milestone.index}.value`] }">{{milestone.abbreviation}}</h5>
                            <div class="milestone-list-text"
                                 :class="{ error: errors[`ratings.${milestone.domain_index}.milestones.${milestone.index}.value`] }">
                                <span>{{milestone.title}}</span>
                                <p>{{milestone.text}}</p>
                            </div>

                            <fieldset :class="{ error: !disabled && errors[`ratings.${milestone.domain_index}.milestones.${milestone.index}.value`] }">
                                <template v-for="(rating, index) in [1, 2, 3, 4]">
                                    <div class="radio-wrap radio-content">
                                        <label :for="disabled ? milestone.id + '-check-radio-disabled' : milestone.id + '-check-radio' + index">
                                            <input type="radio"
                                                   :id="disabled ? milestone.id + '-check-radio-disabled' : milestone.id + '-check-radio' + index"
                                                   v-model="ratings[milestone.domain_index].milestones[milestone.index].value"
                                                   :name="disabled ? milestone.id + '-check-radio-disabled' : milestone.id + '-check-radio'"
                                                   :value="rating"
                                                   :disabled="disabled"
                                                   @input="updateValue(ratings)"/>
                                        </label>
                                    </div>
                                </template>
                            </fieldset>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>
    <div v-else class="tw-text-center w-100">
        <h3>
            Keine Meilensteine in diesem Altersbereich
        </h3>
    </div>
</template>
