<script setup>
import Modal from "@/Components/Modal.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";

const props = defineProps({
    show: Boolean,
    project: Object,
    users: Array,
});

const showPhaseDropdownInModal = ref(false);

const emit = defineEmits(["close", "project-updated"]);

const form = useForm({
    title: "",
    client_name: "",
    phase: "",
    is_active: true,
    team_members: [],
});

const phases = [
    "Planejamento",
    "Desenvolvimento",
    "Testes",
    "Concluído",
    "Em Pausa",
];

watch(
    () => props.project,
    (newProject) => {
        if (newProject) {
            form.title = newProject.title;
            form.client_name = newProject.client_name;
            form.phase = newProject.phase;
            form.is_active = newProject.is_active;
            form.team_members = newProject.users
                ? newProject.users.map((user) => user)
                : [];
            form.clearErrors();
        }
    },
    { immediate: true }
);

const submit = () => {
    const originalTeamMembers = form.team_members;
    form.team_members = form.team_members.map((user) => user.id);

    form.put(route("projects.update", props.project.id), {
        onSuccess: () => {
            emit("project-updated");
        },
        onError: () => {
            form.team_members = originalTeamMembers;
        },
        onFinish: () => (form.processing = false),
    });
};

const closeModal = () => {
    emit("close");
};
</script>

<template>
    <Modal :show="show" :max-width="'2xl'" @close="closeModal">
        <div class="max-h-[90vh] overflow-y-auto p-10">
            <h2 class="text-lg font-medium text-gray-200 mb-4">
                Editar Projeto:
                <span class="font-semibold text-gray-100">{{
                    project?.title
                }}</span>
            </h2>

            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <InputLabel
                            for="edit_title"
                            value="Título do Projeto"
                            class="text-gray-300"
                        />
                        <TextInput
                            id="edit_title"
                            type="text"
                            class="mt-1 block w-full bg-gray-900 text-gray-100 border-gray-600"
                            v-model="form.title"
                            required
                            autofocus
                        />
                        <InputError class="mt-2" :message="form.errors.title" />
                    </div>

                    <div>
                        <InputLabel
                            for="edit_client_name"
                            value="Nome do Cliente"
                            class="text-gray-300"
                        />
                        <TextInput
                            id="edit_client_name"
                            type="text"
                            class="mt-1 block w-full bg-gray-900 text-gray-100 border-gray-600"
                            v-model="form.client_name"
                            required
                        />
                        <InputError
                            class="mt-2"
                            :message="form.errors.client_name"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <InputLabel
                            for="edit_phase_custom"
                            value="Fase do Projeto"
                            class="text-gray-300"
                        />
                        <div class="relative">
                            <button
                                type="button"
                                @click="
                                    showPhaseDropdownInModal =
                                        !showPhaseDropdownInModal
                                "
                                class="mt-1 block w-full text-left py-2 px-3 border border-gray-600 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm bg-gray-900 text-gray-100 focus:outline-none"
                            >
                                {{ form.phase || "Selecione uma fase" }}
                                <svg
                                    class="w-4 h-4 inline-block float-right mt-1 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 9l-7 7-7-7"
                                    ></path>
                                </svg>
                            </button>
                            <div
                                v-if="showPhaseDropdownInModal"
                                class="absolute z-40 mt-1 w-full rounded-md shadow-lg bg-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none max-h-60 overflow-y-auto"
                            >
                                <div class="py-1">
                                    <button
                                        @click="
                                            form.phase = '';
                                            showPhaseDropdownInModal = false;
                                        "
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-200 hover:bg-gray-600"
                                    >
                                        Selecione uma fase
                                    </button>
                                    <button
                                        v-for="phaseOption in phases"
                                        :key="phaseOption"
                                        @click="
                                            form.phase = phaseOption;
                                            showPhaseDropdownInModal = false;
                                        "
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-200 hover:bg-gray-600"
                                    >
                                        {{ phaseOption }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <InputError class="mt-2" :message="form.errors.phase" />
                    </div>

                    <div>
                        <InputLabel
                            for="edit_team_members"
                            value="Membros da Equipe"
                            class="text-gray-300"
                        />
                        <Multiselect
                            v-model="form.team_members"
                            :options="users"
                            :multiple="true"
                            :close-on-select="false"
                            label="name"
                            track-by="id"
                            placeholder="Selecione os membros da equipe"
                            :no-options-text="''"
                            :no-results-text="''"
                            :show-labels="false"
                            class="mt-1 multiselect-dark"
                        />
                        <InputError
                            class="mt-2"
                            :message="form.errors.team_members"
                        />
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"
                        >Cancelar</SecondaryButton
                    >
                    <PrimaryButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Salvar Alterações
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>