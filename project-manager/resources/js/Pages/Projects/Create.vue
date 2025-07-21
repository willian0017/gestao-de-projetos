<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ChevronLeftIcon, CheckIcon } from "@heroicons/vue/24/solid";
import SuccessButton from "@/Components/SuccessButton.vue";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";
import { ref } from "vue";

const showPhaseDropdown = ref(false);

const props = defineProps({
    users: Array,
});

const form = useForm({
    title: "",
    client_name: "",
    phase: "",
    team_members: [],
});

const submit = () => {
    const originalTeamMembers = form.team_members;
    form.team_members = form.team_members.map(user => user.id);

    form.post(route("projects.store"), {
        onSuccess: () => {
            form.reset(); 
        },
        onError: () => {
            form.team_members = originalTeamMembers;
        }
    });
};

const phases = [
    "Planejamento",
    "Desenvolvimento",
    "Testes",
    "Concluído",
    "Em Pausa",
];

</script>

<template>
    <Head title="Novo Projeto" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Criar Novo Projeto
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-full mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 min-h-screen-75">
                    <form @submit.prevent="submit" class="max-w-2xl mx-auto">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <InputLabel
                                    for="title"
                                    value="Título do Projeto"
                                    class="text-gray-700 dark:text-gray-300"
                                />
                                <TextInput
                                    id="title"
                                    type="text"
                                    class="mt-1 block w-full bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600"
                                    v-model="form.title"
                                    required
                                    autofocus
                                    autocomplete="off"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.title"
                                />
                            </div>

                            <div>
                                <InputLabel
                                    for="client_name"
                                    value="Nome do Cliente"
                                    class="text-gray-700 dark:text-gray-300"
                                />
                                <TextInput
                                    id="client_name"
                                    type="text"
                                    class="mt-1 block w-full bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600"
                                    v-model="form.client_name"
                                    required
                                    autocomplete="off"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.client_name"
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div class="relative">
                                <InputLabel for="filter_phase_custom" value="Fase" class="text-gray-700 dark:text-gray-300"/>
                                <button type="button" @click="showPhaseDropdown = !showPhaseDropdown" class="mt-1 block w-full text-left py-2 px-3 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    {{ form.phase || 'Todas as Fases' }}
                                    <ChevronDownIcon class="w-4 h-4 inline-block float-right mt-1 text-gray-500 dark:text-gray-400" />
                                </button>
                                <div v-if="showPhaseDropdown" class="absolute z-40 mt-1 w-full rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none max-h-60 overflow-y-auto">
                                    <div class="py-1">
                                        <button @click="form.phase = ''; showPhaseDropdown = false;" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Todas as Fases</button>
                                        <button v-for="phaseOption in phases" :key="phaseOption" @click="form.phase = phaseOption; showPhaseDropdown = false;" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                                            {{ phaseOption }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Equipe</label
                                >
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
                                <InputError class="mt-2" :message="form.errors.team_members" />
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end">
                            <Link
                                :href="route('projects.index')"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                            >
                                <ChevronLeftIcon class="w-4 h-4 mr-2" /> Voltar
                            </Link>

                            <SuccessButton
                                class="ms-4"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                <CheckIcon class="w-4 h-4 mr-2" /> Salvar
                            </SuccessButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>