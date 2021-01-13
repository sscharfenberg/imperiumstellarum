<script>
/******************************************************************************
 * PageComponent: ShipCardRenameModal
 *****************************************************************************/
import { computed, onMounted, ref } from "vue";
import { useStore } from "vuex";
import Modal from "Components/Modal/Modal";
import GameButton from "Components/Button/GameButton";
import Icon from "Components/Icon/Icon";
export default {
    name: "ShipCardRenameModal",
    props: {
        shipId: String,
    },
    components: { Modal, GameButton, Icon },
    emits: ["close"],
    setup(props, { emit }) {
        const store = useStore();
        const ship = computed(() =>
            store.getters["fleets/shipById"](props.shipId)
        );
        const shipName = ref(ship.value.name);
        const rules = window.rules.ships.name;
        const input = ref(null);
        const onSubmit = () => {
            store.dispatch("fleets/CHANGE_SHIP_NAME", {
                id: props.shipId,
                name: shipName.value,
            });
            emit("close");
        };
        onMounted(() => {
            input.value?.focus();
        });
        return {
            ship,
            shipName,
            input,
            rules,
            onSubmit,
        };
    },
};
</script>

<template>
    <modal
        :title="$t('fleets.ship.rename.title', { name: ship.name })"
        @close="$emit('close')"
    >
        <div class="app-form">
            <div class="form-row">
                <input
                    type="text"
                    class="form-control"
                    id="shipName"
                    v-model="shipName"
                    :placeholder="$t('fleets.new.namePlaceholder')"
                    :maxlength="rules.max"
                    ref="input"
                    @keyup.enter="onSubmit"
                />
                <div class="addon">
                    <icon :name="`hull-${ship.hullType}`" />
                </div>
                <div class="descr">
                    {{
                        $t("fleets.ship.rename.explanation", {
                            min: rules.min,
                            max: rules.max,
                        })
                    }}
                </div>
            </div>
        </div>
        <template v-slot:actions>
            <game-button
                :text-string="$t('fleets.ship.rename.button')"
                icon-name="save"
                :primary="true"
                @click="onSubmit"
            />
        </template>
    </modal>
</template>

<style lang="scss" scoped>
.app-form {
    margin: 0;
}

.modal .app-form .form-row {
    padding: 0;
}
</style>
