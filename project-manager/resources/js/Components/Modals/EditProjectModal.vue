<script setup>
import Modal from '@/Components/Modal.vue'; // O Modal base do Breeze
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';

const props = defineProps({
    show: Boolean,
    project: Object,
    users: Array,
});

const emit = defineEmits(['close', 'project-updated']);

const form = useForm({
    title: '',
    client_name: '',
    phase: '',
    is_active: true,
    team_members: [],
});

const phases = ['Planejamento', 'Desenvolvimento', 'Testes', 'Concluído', 'Em Pausa'];

watch(() => props.project, (newProject) => {
    if (newProject) {
        form.title = newProject.title;
        form.client_name = newProject.client_name;
        form.phase = newProject.phase;
        form.is_active = newProject.is_active;
        form.team_members = newProject.users ? newProject.users.map(user => user.id) : [];
        form.clearErrors();
    }
}, { immediate: true });

const submit = () => {
    form.put(route('projects.update', props.project.id), {
        onSuccess: () => {
            emit('project-updated');
        },
        onFinish: () => form.processing = false,
    });
};

const closeModal = () => {
    emit('close');
    form.reset();
};
</script>

<template>
    <Modal :show="show" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Editar Projeto: {{ project?.title }}</h2>

            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="edit_title" value="Título do Projeto" />
                    <TextInput
                        id="edit_title"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.title"
                        required
                        autofocus
                    />
                    <InputError class="mt-2" :message="form.errors.title" />
                </div>

                <div class="mt-4">
                    <InputLabel for="edit_client_name" value="Nome do Cliente" />
                    <TextInput
                        id="edit_client_name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.client_name"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.client_name" />
                </div>

                <div class="mt-4">
                    <InputLabel for="edit_phase" value="Fase do Projeto" />
                    <select
                        id="edit_phase"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                        v-model="form.phase"
                        required
                    >
                        <option value="" disabled>Selecione uma fase</option>
                        <option v-for="phaseOption in phases" :key="phaseOption" :value="phaseOption">{{ phaseOption }}</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.phase" />
                </div>

                <div class="mt-4">
                    <InputLabel for="edit_team_members" value="Membros da Equipe" />
                    <select
                        id="edit_team_members"
                        multiple
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                        v-model="form.team_members"
                    >
                        <option v-for="user in users" :key="user.id" :value="user.id">
                            {{ user.name }}
                        </option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.team_members" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>
                    <PrimaryButton class="ml-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Salvar Alterações
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>