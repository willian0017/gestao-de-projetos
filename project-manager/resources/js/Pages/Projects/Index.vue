<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { PlusIcon, MagnifyingGlassIcon, XCircleIcon, PencilIcon, ChevronDownIcon } from '@heroicons/vue/24/solid';
import EditProjectModal from '@/Components/Modals/EditProjectModal.vue';
import DeactivateProjectModal from '@/Components/Modals/DeactivateProjectModal.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    projects: Array,
    users: Array,
    filters: Object
});

const filterForm = useForm({
    title: props.filters.title || '',
    client_name: props.filters.client_name || '',
    phase: props.filters.phase || '',
    status: props.filters.status || 'active',
});

const phases = ['Planejamento', 'Desenvolvimento', 'Testes', 'Concluído', 'Em Pausa'];
const statuses = [{ label: 'Ativo', value: 'active' }, { label: 'Inativo', value: 'inactive' }];

const showPhaseDropdown = ref(false);
const showStatusDropdown = ref(false);

const applyFilters = () => {
    filterForm.get(route('projects.index'), {
        preserveState: true,
        replace: true,
    });
};

const showEditModal = ref(false);
const showDeactivateModal = ref(false);
const selectedProject = ref(null);

const openEditModal = (project) => {
    selectedProject.value = project;
    showEditModal.value = true;
};

const openDeactivateModal = (project) => {
    selectedProject.value = project;
    showDeactivateModal.value = true;
};

const softReload = () => {
    router.visit(route('projects.index'), {
        preserveScroll: true, preserveState: true, replace: true
    });
};

const handleProjectUpdated = () => {
    showEditModal.value = false;
    selectedProject.value = null;
    softReload();
};

const handleProjectDeactivated = () => {
    showDeactivateModal.value = false;
    selectedProject.value = null;
    softReload();
};

const getPhaseColorClass = (phase) => {
    switch (phase) {
        case 'Planejamento': return 'bg-blue-600 text-white';
        case 'Desenvolvimento': return 'bg-purple-600 text-white';
        case 'Testes': return 'bg-yellow-500 text-white';
        case 'Concluído': return 'bg-green-600 text-white';
        case 'Em Pausa': return 'bg-gray-600 text-white';
        default: return 'bg-gray-600 text-white';
    }
};

</script>

<template>
    <Head title="Meus Projetos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Meus Projetos</h2>
        </template>

        <div class="py-12">
            <div class="max-w-full mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        <div class="mb-6 border border-gray-200 dark:border-gray-700 p-4 rounded-lg bg-gray-50 dark:bg-gray-700">
                            <h3 class="text-md font-medium mb-4 text-gray-900 dark:text-gray-100">Filtrar Projetos</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div>
                                    <InputLabel for="filter_title" value="Título" class="text-gray-700 dark:text-gray-300"/>
                                    <TextInput id="filter_title" type="text" class="mt-1 block w-full bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600" v-model="filterForm.title" />
                                </div>
                                <div>
                                    <InputLabel for="filter_client" value="Cliente" class="text-gray-700 dark:text-gray-300"/>
                                    <TextInput id="filter_client" type="text" class="mt-1 block w-full bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600" v-model="filterForm.client_name" />
                                </div>

                                <div class="relative">
                                    <InputLabel for="filter_phase_custom" value="Fase" class="text-gray-700 dark:text-gray-300"/>
                                    <button type="button" @click="showPhaseDropdown = !showPhaseDropdown" class="mt-1 block w-full text-left py-2 px-3 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        {{ filterForm.phase || 'Todas as Fases' }}
                                        <ChevronDownIcon class="w-4 h-4 inline-block float-right mt-1 text-gray-500 dark:text-gray-400" />
                                    </button>
                                    <div v-if="showPhaseDropdown" class="absolute z-40 mt-1 w-full rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none max-h-60 overflow-y-auto">
                                        <div class="py-1">
                                            <button @click="filterForm.phase = ''; showPhaseDropdown = false;" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Todas as Fases</button>
                                            <button v-for="phaseOption in phases" :key="phaseOption" @click="filterForm.phase = phaseOption; showPhaseDropdown = false;" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                {{ phaseOption }}
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="relative">
                                    <InputLabel for="filter_status_custom" value="Status" class="text-gray-700 dark:text-gray-300"/>
                                    <button type="button" @click="showStatusDropdown = !showStatusDropdown" class="mt-1 block w-full text-left py-2 px-3 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        {{ statuses.find(s => s.value === filterForm.status)?.label || 'Ativo' }}
                                        <ChevronDownIcon class="w-4 h-4 inline-block float-right mt-1 text-gray-500 dark:text-gray-400" />
                                    </button>
                                    <div v-if="showStatusDropdown" class="absolute z-40 mt-1 w-full rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none max-h-60 overflow-y-auto">
                                        <div class="py-1">
                                            <button v-for="statusOption in statuses" :key="statusOption.value" @click="filterForm.status = statusOption.value; showStatusDropdown = false;" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                {{ statusOption.label }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end mt-4 space-x-2">
                                <Link :href="route('projects.create')" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    <PlusIcon class="w-4 h-4 mr-2" /> Novo Registro
                                </Link>
                                <PrimaryButton @click="applyFilters">
                                    <MagnifyingGlassIcon class="w-4 h-4 mr-2" /> Buscar
                                </PrimaryButton>
                            </div>
                        </div>

                        <div v-if="projects.length > 0" class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Título</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cliente</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fase</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Criador</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Equipe</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr v-for="project in projects" :key="project.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                            <div class="relative inline-block text-left">
                                                <button type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-2 py-2 bg-yellow-500 text-sm font-medium text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 dark:focus:ring-offset-gray-800" @click="project.showActions = !project.showActions">
                                                    <ChevronDownIcon class="w-5 h-5" />
                                                </button>

                                                <div v-if="project.showActions" class="origin-top-right absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none z-50" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                                                    <div class="py-1" role="none">
                                                        <button @click="openEditModal(project); project.showActions = false;" class="text-gray-700 dark:text-gray-200 block px-4 py-2 text-sm w-full text-left hover:bg-gray-100 dark:hover:bg-gray-600" role="menuitem" tabindex="-1">
                                                            <PencilIcon class="w-4 h-4 inline-block mr-2" /> Editar
                                                        </button>
                                                        <button @click="openDeactivateModal(project); project.showActions = false;" class="text-red-600 dark:text-red-400 block px-4 py-2 text-sm w-full text-left hover:bg-gray-100 dark:hover:bg-gray-600" role="menuitem" tabindex="-1" :disabled="!project.is_active">
                                                            <XCircleIcon class="w-4 h-4 inline-block mr-2" /> Desativar
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-gray-100">{{ project.title }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-200">{{ project.client_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="['px-2 inline-flex text-xs leading-5 font-semibold rounded-full', getPhaseColorClass(project.phase)]">
                                                {{ project.phase }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="{'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true, 'bg-green-500 text-white': project.is_active, 'bg-red-500 text-white': !project.is_active}">
                                                {{ project.is_active ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-200">{{ project.creator.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-200">
                                            <span v-if="project.users && project.users.length > 0">
                                                {{ project.users.map(user => user.name).join(', ') }}
                                            </span>
                                            <span v-else>Nenhum</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else class="p-4 text-center text-gray-500 dark:text-gray-400">
                            Nenhum projeto encontrado com os filtros selecionados.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <EditProjectModal
            :show="showEditModal"
            :project="selectedProject"
            :users="users"
            @close="showEditModal = false"
            @project-updated="handleProjectUpdated"
        />

        <DeactivateProjectModal
            :show="showDeactivateModal"
            :project="selectedProject"
            @close="showDeactivateModal = false"
            @project-deactivated="handleProjectDeactivated"
        />

    </AuthenticatedLayout>
</template>