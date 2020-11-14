<script>
/******************************************************************************
 * PageComponent: ListTechLevels
 *****************************************************************************/
export default {
    name: "ListTechLevels",
    props: {
        current: Number,
    },
    setup() {
        const min = window.rules.tech.bounds.min;
        const max = window.rules.tech.bounds.max;
        return {
            min,
            max,
        };
    },
};
</script>

<template>
    <ul class="tech-levels">
        <li class="learned">0</li>
        <li
            v-for="level in max"
            :key="level"
            :class="{ learned: level <= current }"
        >
            {{ level }}
        </li>
    </ul>
</template>

<style lang="scss" scoped>
.tech-levels {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr;

    padding: 0;
    margin: 0.8rem 0 0 0;
    flex: 0 0 100%;
    grid-gap: 2px;

    list-style: none;

    @include respond-to("medium") {
        margin-top: 1.6rem;
        grid-gap: 4px;
    }

    > li {
        display: flex;
        align-items: center;
        justify-content: center;

        height: 3rem;
        border: 2px solid transparent;
        //border-right-width: 0;

        @include themed() {
            background-color: t("g-bunker");
            color: t("t-bright");
        }

        @include respond-to("medium") {
            height: 4rem;
        }

        //&:last-child {
        //    border-right-width: 2px;
        //}
    }

    .learned {
        @include themed() {
            background: t("s-active");
            border-color: t("s-success");
        }
    }
}
</style>
