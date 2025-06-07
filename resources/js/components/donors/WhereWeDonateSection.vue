<template>
  <BaseSection class="donors-list-section">
    <div>
      <h2>Where We Donate To</h2>
      <p>
        We are committed to making a positive impact in the world by supporting various causes and organizations.
        Through our efforts, we have been able to extend our reach and contribute to the success of other charities that
        share our vision of creating a better future. Here are some of the incredible charities we have proudly donated
        money to:
      </p>
      <DonorsLogoCarousel :logos="logos" />
    </div>
  </BaseSection>
</template>

<script setup lang="ts">
  const logos: string[] = [
    '/images/donors/our-donations/alzheimers-assoc.png',
    '/images/donors/our-donations/american-cancer-society.png',
    '/images/donors/our-donations/boys-and-girls-club-stanislaus.png',
    '/images/donors/our-donations/chicana-latina-foundation.png',
    '/images/donors/our-donations/friends-of-the-escalon-library.png',
    '/images/donors/our-donations/evelyn-andrews-golf-tournament.jpg',
    '/images/donors/our-donations/fox-chase.png',
    '/images/donors/our-donations/ice-cream-co.png',
    '/images/donors/our-donations/maui-strong.jpg',
    '/images/donors/our-donations/modesto-band-of-satnislaus.jpg',
    '/images/donors/our-donations/modesto-childrens-museum.png',
    '/images/donors/our-donations/order-sons-and-daughters-of-italy-in-america.png',
    '/images/donors/our-donations/pet-smart-charities.png',
    '/images/donors/our-donations/rotary.png',
    '/images/donors/our-donations/salvation-army.png',
    '/images/donors/our-donations/sassna.png',
    '/images/donors/our-donations/second-harvest.png',
    '/images/donors/our-donations/st-judes.png',
    '/images/donors/our-donations/trevor-project.png',
    '/images/donors/our-donations/union-gospel-mission.png',
    '/images/donors/our-donations/vacaville-christmas-wish.jpg'
  ];
</script>
