<script>
/******************************************************************************
 * PageComponent: ResearchPriority
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { computed, onBeforeMount, ref } from "vue";
import { useStore } from "vuex";
import debounce from "lodash/debounce";
export default {
    name: "ResearchPriority",
    setup() {
        const store = useStore();
        const rules = window.rules.tech.researchPriority;
        //const statePriority = computed(
        //    () => store.state.empireResearchPriority
        //);
        const priority = computed({
            get: () => store.state.empireResearchPriority,
            set: debounce((val) => {
                //priority.value = val;
                store.dispatch("research/SET_RESEARCH_PRIORITY", {
                    researchPriority: parseFloat(val),
                });
            }, 300),
        });
        const isRequesting = ref(false);
        const totalPopulation = computed(
            () => store.state.research.totalPopulation
        );
        const getEffectiveResearch = () => {
            isRequesting.value = true;
            window.axios
                .post("/api/game/effectiveResearch", {
                    priority: parseFloat(priority.value),
                    totalPopulation: totalPopulation.value,
                })
                .then((response) => {
                    calculatedResults.value = response.data.effectiveResearch;
                })
                .catch((e) => {
                    console.error(e);
                })
                .finally(() => {
                    isRequesting.value = false;
                });
        };
        onBeforeMount(() => {
            getEffectiveResearch();
        });
        const onChange = debounce(() => {
            getEffectiveResearch();
        }, 200);
        const calculatedCosts = computed(() => {
            return Math.ceil(priority.value * totalPopulation.value);
        });
        const calculatedResults = ref(0);
        return {
            rules,
            priority,
            isRequesting,
            calculatedCosts,
            calculatedResults,
            onChange,
            ...useI18n(),
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
                :disabled="isRequesting"
                @change="onChange"
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
                :disabled="isRequesting"
                @change="onChange"
            />
        </div>
        <div class="result">
            <div class="costs">
                {{ $t("research.priority.costs") }}: {{ calculatedCosts }}
            </div>
            <div class="work">
                {{ $t("research.priority.work") }}: {{ calculatedResults }}
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

    padding: 0.8rem;
    flex-basis: 100%;

    @include respond-to("medium") {
        padding: 1.6rem;
        flex-grow: 1;
        flex-basis: auto;
    }

    > input[type="range"] {
        margin: 0 0.8rem 0 0;
        flex-grow: 1;

        @include respond-to("medium") {
            margin-right: 1.6rem;
        }
    }

    > input[type="number"] {
        padding: 0.5rem 1rem;
        border: 0;
        flex: 0 0 4.5rem;

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
            margin-right: 1.6rem;
            flex-basis: auto;
        }
    }
}

.result {
    padding: 0.8rem;
    flex: 0 0 100%;

    @include themed() {
        background: t("g-bunker");
    }

    @include respond-to("medium") {
        padding: 0.8rem 1.6rem;
        flex: 0 0 100%;
    }
}
</style>
