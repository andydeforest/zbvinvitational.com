<template>
  <BaseSection class="about-section">
    <div class="about-section__copy">
      <div>
        <h2>About the ZBV Memorial Fund</h2>
        <p>
          In early 2014 Zeke Bondy-Villa was very busy. His fledgling business was really growing and gaining clients,
          he was president of his Rotary Club chapter and he was helping with preparations for the wedding of his son,
          Adam. He wanted an outdoor kitchen with a massive pizza oven, so in his spare time he was building it by hand.
          Zeke had a lot going on. Then again, he always did. He was the buddy you could rely on. He always made the
          right choice. He was light-hearted and fun loving. When you saw him he made you feel good. He just did things
          the right way. Zeke had an army of friends and he also had cancer.
        </p>
        <p>
          He'd survived cancer twice before, but this time was different. The army of friends came together to provide
          care and support and grandma's cure-all, but even chicken soup was no match for metastatic renal sarcoma. He
          was gone the following March.
        </p>
        <p>So, yeah. Cancer sucks.</p>
        <p>Still, you're probably wondering who are we and what's this got to do with golf?</p>
      </div>
      <div>
        <h2>About the Golf Tournament</h2>
        <p>
          We're Zeke's legacy, the army of friends. We're here to honor his name and memory and do something that would
          make him proud, but do it in a way that would make him laugh.
        </p>
        <p>
          Zeke's game was golf. He played well, but not too seriously. With him on a golf course there was play with a
          purpose along with a chance to laugh about something. He loved everything about it, especially its social
          side. So, a tournament in his name is a natural to bring people together.
        </p>
        <p>
          Purpose and laughter are what it's all about. This tournament is 100% volunteer-operated. All the proceeds
          support community organizations as well as cancer research and awareness programs. That's the purpose and it's
          serious.
        </p>
        <p>
          The laughter? Hey, it's an army of friends having a great day in September at the course. There is always
          laughter.
        </p>
      </div>
    </div>
    <div class="about-section__timeline">
      <AboutTimeline />
    </div>
  </BaseSection>
</template>

<style lang="scss">
  .about-section {
    display: flex;
    flex-direction: column;
    gap: 2rem;

    @include mixins.desktop {
      flex-direction: row;
      gap: 4rem;
    }

    &__copy {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      flex: 1;

      @include mixins.desktop {
        gap: 2rem;
      }

      h2 {
        margin-bottom: 1rem;
      }
    }

    &__timeline {
      display: flex;
      align-items: center;
    }
  }
</style>
