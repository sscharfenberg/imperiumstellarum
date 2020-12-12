<script>
/******************************************************************************
 * PageComponent: ListTechLevels
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
export default {
    name: "ListTechLevels",
    props: {
        current: Number,
        type: String,
    },
    setup(props) {
        const store = useStore();
        const researchLevels = computed(() =>
            store.getters["research/researchJobsByType"](props.type).map(
                (job) => job.level
            )
        );
        const min = window.rules.tech.bounds.min;
        const max = window.rules.tech.bounds.max;
        return {
            min,
            max,
            researchLevels,
        };
    },
    methods: {
        label(level) {
            let label = this.$t("research.tl.levelLabel", { level }) + ": ";
            if (level <= this.current) {
                label += this.$t("research.tl.learned");
            } else if (this.researchLevels.includes(level)) {
                label += this.$t("research.tl.learning");
            } else {
                label += this.$t("research.tl.available");
            }
            return label;
        },
    },
};
</script>

<template>
    <ul class="tech-levels">
        <li class="learned">0</li>
        <li
            v-for="level in max"
            :key="level"
            :class="{
                learned: level <= current,
                learning: researchLevels.includes(level),
            }"
            :aria-label="label(level)"
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
    margin: 8px 0 0 0;
    flex: 0 0 100%;
    grid-gap: 2px;

    list-style: none;

    @include respond-to("medium") {
        margin-top: 16px;
        grid-gap: 4px;
    }

    > li {
        display: flex;
        align-items: center;
        justify-content: center;

        height: 30px;
        border: 2px solid transparent;
        //border-right-width: 0;

        @include themed() {
            background-color: t("g-bunker");
            color: t("t-bright");
            border-color: t("g-deep");
        }

        @include respond-to("medium") {
            height: 40px;
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

    .learning {
        @include themed() {
            border: 2px dashed t("s-warning");

            background: t("s-building");
        }
    }
}
</style>
