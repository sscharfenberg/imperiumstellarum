<script>
/******************************************************************************
 * PageComponent: NewFleetName
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import Icon from "Components/Icon/Icon";
export default {
    name: "NewFleetName",
    components: { Icon },
    emits: ["submit"],
    setup(props, { emit }) {
        const store = useStore();
        const fleetName = computed({
            get: () => store.state.fleets.createName,
            set: (value) => {
                store.commit("fleets/SET_CREATE_FLEET_NAME", value);
            },
        });
        const rules = window.rules.fleets.name;
        const onSubmit = () => {
            emit("submit");
        };
        return { fleetName, rules, onSubmit };
    },
};
</script>

<template>
    <div class="form-row">
        <div class="label">
            <label for="fleetName">{{ $t("fleets.new.nameLabel") }}</label>
        </div>
        <div class="input">
            <input
                type="text"
                class="form-control"
                id="fleetName"
                :placeholder="$t('fleets.new.namePlaceholder')"
                :maxlength="rules.max"
                v-model="fleetName"
                @keyup.enter="onSubmit"
            />
            <div class="addon"><icon name="fleets" /></div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.form-row {
    padding: 0 0 16px 0;
}
.form-row .label {
    margin-bottom: 8px;
    flex-basis: 100%;
}
.form-row .input {
    flex-basis: 100%;
}
</style>
