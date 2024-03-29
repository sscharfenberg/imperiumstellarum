<script>
/******************************************************************************
 * PageComponent: StarNameEdit
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, ref, onMounted } from "vue";
import GameButton from "Components/Button/GameButton";
export default {
    name: "StarNameEdit",
    props: {
        starId: String,
        starName: String,
    },
    components: { GameButton },
    setup(props) {
        const store = useStore();
        const name = ref(props.starName);
        const input = ref(null);
        const isExpanded = computed(
            () => !store.getters["empire/isStarCollapsed"](props.starId)
        );
        const onExpandClick = () => {
            store.commit("empire/TOGGLE_STAR_EXPANDED", {
                id: props.starId,
                expand: false,
            });
        };
        const onCancel = () => {
            store.commit("empire/SET_EDITING_STAR", "");
        };
        const onDone = () => {
            store.commit("empire/SET_EDITING_STAR", "");
            store.dispatch("empire/CHANGE_STAR_NAME", {
                id: props.starId,
                name: name.value,
            });
        };
        onMounted(() => {
            input.value?.focus();
        });
        return {
            input,
            name,
            isExpanded,
            onExpandClick,
            onCancel,
            onDone,
        };
    },
};
</script>

<template>
    <div class="star-name">
        <game-button
            v-if="!isExpanded"
            icon-name="expand_more"
            @click="onExpandClick"
            class="expand"
        />
        <game-button
            v-if="isExpanded"
            icon-name="expand_less"
            @click="onExpandClick"
            class="expand"
        />
        <input
            class="name"
            v-model="name"
            @keyup.enter="onDone"
            @keyup.escape="onCancel"
            ref="input"
        />
        <game-button icon-name="cancel" class="cancel" @click="onCancel" />
        <game-button icon-name="done" class="done" @click="onDone" />
    </div>
</template>

<style lang="scss" scoped>
.star-name {
    display: flex;
    align-items: center;

    overflow: hidden;
    flex: 1 1 auto;
}

.expand {
    margin-right: 4px;

    @include respond-to("medium") {
        margin-right: 16px;
    }
}

.name {
    width: 100%;
    height: 48px;
    padding: 0;
    border: 0;
    margin-right: 4px;

    background: transparent;
    outline: 0;

    font-size: 24px;
    line-height: 1;

    @include themed() {
        color: t("b-viking");
    }

    @include respond-to("medium") {
        margin-right: 8px;
    }
}

.cancel {
    margin-left: auto;
}

.done {
    margin-left: 4px;

    @include respond-to("medium") {
        margin-right: 8px;
    }
}
</style>
