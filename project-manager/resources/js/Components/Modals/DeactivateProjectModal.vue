<script setup>
import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    show: Boolean,
    project: Object,
});

const emit = defineEmits(["close", "project-deactivated"]);

const form = useForm({});

const deactivateProject = () => {
    form.delete(route("projects.destroy", props.project.id), {
        preserveScroll: true,
        onSuccess: () => {
            emit("project-deactivated");
            emit("close");
        },
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    emit("close");
    form.reset();
};
</script>

<template>
    <Modal :show="show" :max-width="'sm'" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-200">
                Confirmar desativação
            </h2>

            <p class="mt-4 text-sm text-gray-400">
                Você realmente deseja desativar o projeto "<span class="font-semibold text-gray-300">{{ project?.title }}</span>"?
                Esta ação não poderá ser desfeita.
            </p>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal">
                    Cancelar
                </SecondaryButton>

                <DangerButton
                    class="ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="deactivateProject"
                >
                    Desativar
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>