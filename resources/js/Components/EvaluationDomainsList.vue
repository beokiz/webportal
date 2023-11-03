<!--
  - GorKa Team
  - Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
  -->

<script setup>
const props = defineProps({
    domains: Array,
    ratings: Array,
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

const updateValue = (newRatings) => {
    emit('updateRatingData', newRatings);
};
</script>

<template>
    <div class="domains-list-container"
         v-for="(domain, index) in domains"
         :key="domain.id">

        <h3 :class="{ 'green': ratings[index].color === 'green', 'yellow': ratings[index].color === 'yellow', 'red': ratings[index].color === 'red' }">
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

                <h5 :class="{ error: errors[`ratings.${milestone.domain_index}.milestones.${milestone.index}.value`] }">{{milestone.abbreviation}}</h5>
                <div class="milestone-list-text"
                     :class="{ error: errors[`ratings.${milestone.domain_index}.milestones.${milestone.index}.value`] }">
                    <span>{{milestone.title}}</span>
                    <p>{{milestone.text}}</p>
                </div>

                <fieldset :class="{ error: !disabled && errors[`ratings.${milestone.domain_index}.milestones.${milestone.index}.value`] }">
                    <template v-for="rating in [1, 2, 3, 4]">
                        <div class="radio-wrap radio-content">
                            <input type="radio"
                                   v-model="ratings[milestone.domain_index].milestones[milestone.index].value"
                                   :name="disabled ? milestone.id + '-check-radio-disabled' : milestone.id + '-check-radio'"
                                   :value="rating"
                                   :disabled="disabled"
                                   @input="updateValue(ratings)"/>
                        </div>
                    </template>
                </fieldset>
            </div>
        </div>
    </div>
</template>
