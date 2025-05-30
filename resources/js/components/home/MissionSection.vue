<template>
  <BaseSection class="home-mission-section copy-and-image-2">
    <div class="home-mission-section__copy">
      <h2>Our Mission Statement</h2>
      <span class="home-mission-section__copy--secondary-title">
        Zeal, Benevolence, and Vision
      </span>
      <p>
        ZBV Memorial Charities Foundation, Inc. follows the example of Zeke
        Bondy-Villa who lived joyously in the moment, reveled in companionship
        and selflessly pushed to bring a better future for his family, friends
        and community.Though he left us too soon his hard work and generosity
        continue to inspire. The ZBV Foundation is an all-volunteer,
        not-for-profit organization that honors his life and memory by:
      </p>
      <ul>
        <li>Bringing constructive change to the community,</li>
        <li>
          Supporting organizations engaged in cancer research and prevention,
        </li>
        <li>
          Conducting positive sporting and social events that foster
          relationships and grow the capacity to meet philanthropic needs.
        </li>
      </ul>
      <p>
        ZBV Memorial Charities Foundation, Inc. welcomes the participation and
        contributions of all who want to promote something greater than
        themselves regardless of where they’re from, who they love, what they
        look like or any of the thousand other things that make people distinct.
      </p>
    </div>
    <div>
      <BaseImageOverlay :src="'/images/home/mission.jpg'" alt="" />
    </div>
  </BaseSection>
</template>

<style lang="scss">
  .home-mission-section {
    &__copy {
      ul {
        list-style: disc;

        li {
          margin-left: 2rem;
          margin-bottom: 0.5rem;
        }
      }

      &--secondary-title {
        font-family: var(--heading-font-family);
        font-size: var(--h3-font-size);
        font-weight: 500;
      }
    }
  }
</style>
