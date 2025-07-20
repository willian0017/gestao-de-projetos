<script setup>
import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    show: Boolean,
    project: Object,
});

const emit = defineEmits(["close", "project-deactivated"]);

const passwordInput = ref(null);

const form = useForm({
    password: "",
});

const deactivateProject = () => {
    form.delete(route("projects.destroy", props.project.id), {
        preserveScroll: true,
        onSuccess: () => {
            emit("project-deactivated");
        },
        onError: () => {
            passwordInput.value.focus();
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
    <Modal :show="show" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Desativar Projeto: {{ project?.title }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Para desativar este projeto, por favor, insira sua senha para
                confirmar que você está autorizado a realizar esta ação.
            </p>

            <div class="mt-6">
                <InputLabel for="password" value="Senha" class="sr-only" />

                <TextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="Senha"
                    @keyup.enter="deactivateProject"
                />

                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>

                <DangerButton
                    class="ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="deactivateProject"
                >
                    Desativar Projeto
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>
