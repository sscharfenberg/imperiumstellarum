<script>
/******************************************************************************
 * PageComponent: FleetEditModal
 *****************************************************************************/
import { computed, onMounted, ref } from "vue";
import { useStore } from "vuex";
import GameButton from "Components/Button/GameButton";
import Icon from "Components/Icon/Icon";
import Modal from "Components/Modal/Modal";
export default {
    name: "FleetEditModal",
    props: {
        fleetId: String,
    },
    components: { GameButton, Icon, Modal },
    emits: ["close"],
    setup(props, { emit }) {
        const store = useStore();
        const fleet = computed(() =>
            store.getters["fleets/fleetById"](props.fleetId)
        );
        const fleetName = ref(fleet.value.name);
        const rules = window.rules.fleets.name;
        const input = ref(null);
        const onSubmit = () => {
            store.dispatch("fleets/CHANGE_FLEET_NAME", {
                id: props.fleetId,
                name: fleetName.value,
            });
            emit("close");
        };
        onMounted(() => {
            input.value?.focus();
        });
        return {
            fleet,
            fleetName,
            input,
            rules,
            onSubmit,
        };
    },
};
</script>

<template>
    <modal
        :title="$t('fleets.active.editName.headline', { name: fleet.name })"
        @close="$emit('close')"
    >
        <div class="app-form">
            <div class="form-row">
                <input
                    type="text"
                    class="form-control"
                    id="fleetName"
                    v-model="fleetName"
                    :maxlength="rules.max"
                    :placeholder="$t('fleets.new.namePlaceholder')"
                    ref="input"
                    @keyup.enter="onSubmit"
                />
                <div class="addon"><icon name="fleets" /></div>
                <div class="descr">
                    {{
                        $t("fleets.active.editName.explanation", {
                            min: rules.min,
                            max: rules.max,
                        })
                    }}
                </div>
            </div>
        </div>
        <template v-slot:actions>
            <game-button
                :text-string="$t('fleets.active.editName.submit')"
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
