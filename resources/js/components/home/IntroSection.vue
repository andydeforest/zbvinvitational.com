<template>
  <BaseSection class="home-intro-section copy-and-image-1">
    <div class="home-intro-section__copy">
      <h2>Hi Friends and Family!</h2>
      <p>
        Please save the date for the annual ZBV Invitational Golf Tournament on {{ formattedEventDate }}! If you're new
        to our foundation, welcome! As our name suggests, every year we host a charity golf tournament in memory of Zeke
        Bondy-Villa, who's companionship and spirit continue to inspire.
      </p>
      <p>
        Last year we raised another $46,000, bringing our total donations to north of $156,000 since our inception in
        2016! Thank you for your continued support, we couldn’t do this without your generosity! This year we will have
        golf, games, food, prizes, and live music. Like last year, we will be hosting the event at Creekside Golf Course
        (701 Lincoln Ave, Modesto, CA 95354). Registration opens at 9 AM, and the 18-hole tournament begins with a
        shotgun start at 10 AM. The 9-hole golfers will begin at 12 PM.
      </p>
      <p>
        This year's theme is "Golfing With the Stars." This theme is open to interpretation so do you best to wow us!
      </p>
      <p>Registration will open June 1st on a first-come, first-served basis. We hope to see you there!</p>
    </div>
    <div class="home-intro-section__image">
      <BaseImageOverlay :src="'/images/home/intro.jpg'" alt="" />
    </div>
  </BaseSection>
</template>

<script setup lang="ts">
  import { useEventInfo } from '@/composables/useEventInfo';

  const { formattedEventDate } = useEventInfo('cccc, LLLL dd, yyyy');

  defineProps<{
    randomImages: string[];
  }>();
</script>
