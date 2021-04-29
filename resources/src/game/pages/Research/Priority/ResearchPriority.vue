<script>
/******************************************************************************
 * PageComponent: ResearchPriority
 *****************************************************************************/
import { computed, ref } from "vue";
import { useStore } from "vuex";
import debounce from "lodash/debounce";
export default {
    name: "ResearchPriority",
    setup() {
        const store = useStore();
        const rules = window.rules.tech.researchPriority;
        const priority = computed({
            get: () => store.state.empireResearchPriority,
            set: debounce((val) => {
                store.dispatch("research/SET_RESEARCH_PRIORITY", {
                    researchPriority: parseFloat(val),
                });
            }, 300),
        });
        const isRequesting = ref(false);
        const totalPopulation = computed(
            () => store.state.research.totalPopulation
        );
        const calculatedCosts = computed(() => {
            return Math.ceil(priority.value * totalPopulation.value);
        });
        const dead = computed(() => store.state.dead);
        const gameOver = computed(() => store.state.gameEnded);
        return {
            rules,
            priority,
            isRequesting,
            calculatedCosts,
            dead,
            gameOver,
        };
    },
};
</script>

<template>
    <div class="priority">
        <div class="set">
            <label for="researchSpeedNumber">{{
                $t("research.priority.label")
            }}</label>
            <input
                type="range"
                id="researchSpeedSlider"
                :min="rules.min"
                :aria-valuemin="rules.min"
                :max="rules.max"
                :aria-valuemax="rules.max"
                step="0.1"
                v-model="priority"
                :disabled="isRequesting || dead || gameOver"
            />
            <input
                type="number"
                id="researchSpeedNumber"
                :min="rules.min"
                :aria-valuemin="rules.min"
                :max="rules.max"
                :aria-valuemax="rules.max"
                step="0.5"
                v-model="priority"
                :disabled="isRequesting || dead || gameOver"
            />
        </div>
        <div class="result">
            <div class="costs">
                {{ $t("research.priority.costs") }}: {{ calculatedCosts }}
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.priority {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;

    @include themed() {
        background: t("g-sunken");
    }
}

.set {
    display: flex;
    align-items: center;
    flex-wrap: wrap;

    padding: 8px;
    flex-basis: 100%;

    @include respond-to("medium") {
        padding: 16px;
        flex-grow: 1;
        flex-basis: auto;
    }

    > input[type="range"] {
        margin: 0 8px 0 0;
        flex-grow: 1;

        @include respond-to("medium") {
            margin-right: 16px;
        }
    }

    > input[type="number"] {
        padding: 5px 10px;
        border: 0;
        flex: 0 0 45px;

        font-weight: 300;
        line-height: 1;

        @include themed() {
            background: t("g-deep");
            color: t("t-light");
        }

        &:focus {
            outline: 0;

            @include themed() {
                background: t("g-raven");
                color: t("g-white");
            }
        }
    }

    > label {
        flex-basis: 100%;

        @include respond-to("medium") {
            margin-right: 16px;
            flex-basis: auto;
        }
    }
}

.result {
    padding: 8px;
    flex: 0 0 100%;

    @include themed() {
        background: t("g-bunker");
    }

    @include respond-to("medium") {
        padding: 8px 16px;
        flex: 0 0 100%;
    }
}
</style>
