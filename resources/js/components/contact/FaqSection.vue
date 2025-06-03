<template>
  <BaseSection class="contact-faq-section">
    <h2 class="section-title">FAQ</h2>
    <div v-for="(question, x) in faq" :key="`question-${x}`" class="">
      <h3 class="is-size-4 contact-faq-section__question">
        {{ question.question }}
      </h3>
      <p>
        {{ question.answer }}
      </p>
    </div>
  </BaseSection>
</template>

<script setup lang="ts">
  const faq = [
    {
      question: 'What time does the fun begin?',
      answer:
        'Check in as early as 9am and hit a bucket of balls. Then enjoy some coffee, donuts, bromosa or other beverages. 10am shotgun start for 18 hole players and Noon for 9 hole players.'
    },
    {
      question: "What is this year's theme?",
      answer:
        'Golfing with the stars. Let your imagination run wild! Come dressed as your favorite celebrity, movie star, famous athlete, etc.'
    },
    { question: 'Where is the tournament being held?', answer: 'Creekside Golf Course - 701 Lincoln Ave., Modesto' },
    {
      question: "What is included in the day's events?",
      answer:
        'For 18 hole golfers, we provide breakfast, lunch, dinner (for you and 1 guest), dessert, goody bag, drinks on the course, 18 holes of fun play and cart. For 9 hole players we provide lunch, dinner (for you and 1 guest), dessert, goody bag, drinks on the course, 9 holes of fun play and cart. After play, there will be opportunity to purchase raffle tickets and to bed on some amazing procured items.'
    },
    {
      question: "I'm not a golfer, can I just join you for dinner?",
      answer: 'Absolutely! Dinner is just $15 which includes wine, beer or soda.'
    },
    {
      question: 'What is served for lunch and dinner?',
      answer:
        'Lunch is a delicious sandwich (there is a vegetarian option), chips and a cookie. If you have special dietary restrictions, please let us know in the comments section when registering. Dinner will be a traditional Mexican style meal including a protein, tortillas, rice and beans.'
    },
    {
      question: 'What should I bring with me?',
      answer:
        'Clubs, sunscreen, hat, decorations (if you want to detail your cart), and your wallet :). Water stations will be at various locations on the course but please bring your own water bottle'
    },
    {
      question: 'What time is dinner?',
      answer: "Dinner will probably be served early - we don't have an exact time but it will be close to 4pm."
    },
    { question: 'What time do the raffle and silent auction close?', answer: '6pm' },
    { question: 'What forms of payment are accepted at the course?', answer: 'Credit cards, cash, checks, Venmo' },
    {
      question: 'What happens in the event of rain or significant heat?',
      answer:
        "If weather interferes with our day and we have to make the sad decision to cancel, we will hold everyone's payment for next year's event unless they request a refund."
    }
  ];
</script>

<style lang="scss">
  .contact-faq-section {
    --faq-gap: 2rem;

    display: flex;
    flex-direction: column;
    gap: var(--faq-gap);

    > div {
      display: flex;
      flex-direction: column;

      @include mixins.desktop {
        display: grid;
        grid-template-columns: 2fr 4fr;
        gap: 2rem;

        &:not(:last-of-type) {
          border-bottom: 1px solid #ccc;
          padding-bottom: var(--faq-gap);
        }
      }

      p {
        margin-bottom: 0;
      }
    }

    &__question {
      display: flex;
      align-items: center;
    }
  }
</style>
