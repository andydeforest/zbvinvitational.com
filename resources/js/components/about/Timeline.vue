<template>
  <Timeline class="about-timeline" :value="formattedMilestones" align="alternate">
    <template #marker="{ item }">
      <div class="about-timeline__year">
        {{ item.year }}
      </div>
    </template>
    <template #content="{ item }">
      <div class="about-timeline__blurb">
        <div class="about-timeline__amount">{{ item.amount }}</div>
        <div class="about-timeline__title">{{ item.title }}</div>
      </div>
    </template>
  </Timeline>
</template>

<script setup lang="ts">
  import { ref } from 'vue';
  import Timeline from 'primevue/timeline';

  defineEmits();
  const milestones = ref([
    { year: '2016', title: '1st Tournament', amount: '$1,200' },
    { year: '2017', title: '2nd Tournament', amount: '$3,000' },
    { year: '2018', title: '3rd Tournament', amount: '$7,000' },
    { year: '2019', title: '4th Tournament', amount: '$13,500' },
    { year: '2020', title: 'No Tournament due to COVID :(', amount: '$0' },
    { year: '2021', title: '5th Tournament', amount: '$18,000' },
    { year: '2022', title: '6th Tournament', amount: '$24,300' },
    { year: '2023', title: '7th Tournament', amount: '$27,500' },
    { year: '2024', title: '8th Tournament', amount: '$46,000' }
  ]);

  const formattedMilestones = computed(() => [...milestones.value].reverse());
</script>

<style lang="scss">
  .about-timeline {
    &__year {
      font-family: var(--heading-font-family);
      font-size: 2rem;
      font-weight: 700;
      color: var(--zbv-primary);
    }

    &__amount {
      font-weight: 500;
      color: var(--zbv-secondary);
    }
  }
</style>
