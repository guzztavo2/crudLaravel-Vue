<template>
  <ModalApp
    v-if="typeModal[0] == 'deletar'"
    ref="modal"
    @fechar-modal="respostaClick(false)"
    :typeModal="false"
  >
    <template #modalTitle>
      <h3>Você tem certeza que deseja deletar essa informação?</h3>
    </template>
    <template #modalMessage>
      <h3>
        Depois de deletado, não terá como voltar atrás, então escolha com
        cuidado:
      </h3>
      <h3 class="button_list">
        <button @click="respostaClick(true)">Sim, deletar</button>
        <button @click="respostaClick(false)">Não, cancelar</button>
      </h3>
    </template>
  </ModalApp>
  <ModalApp
    v-if="typeModal[0] == 'visualizar'"
    ref="modal"
    @fechar-modal="respostaClick(false)"
    :typeModal="true"
  >
    <template #modalTitle>
      <h3>Visualizar Informação:</h3>
    </template>
    <template #modalMessage>
      <h3>
        <label for="informacao">
          Informação
          <input type="text" value="Informação" id="informacao" />
        </label>
        <label for="atualizarInfo">
          Atualizar
          <input type="text" value="Informação" id="atualizarInfo" />
        </label>

        <button>Salvar</button>
        <button>Cancelar</button>
      </h3>
    </template>
  </ModalApp>
</template>

<script lang="ts">
import { Vue, Options } from "vue-class-component";
import ModalApp from "./modal.vue";
@Options({
  props: {
    typeModal: [],
  },
  emits: ["resposta-modal"],
  mounted() {
    this.typeModal = this.$props.typeModal;
    console.log(this.typeModal[0]);
    this.$nextTick(() => {
      const modal = this.$refs.modal;
      modal.exibirModal();
    });
  },
  components: { ModalApp },
})
export default class dialogModal extends Vue {
  typeModal: any = [];
  respostaClick(resposta: any) {
    const modal = this.$refs.modal as any;
    modal.fecharModal();
    setTimeout(() => {
      this.$emit("resposta-modal", [
        resposta,
        this.typeModal[0],
        this.typeModal[1],
      ]);
    }, 200);
  }
}
</script>

<style>
h3.button_list {
  display: flex;
  flex-flow: row wrap;
  justify-content: center;
  align-items: center;
  border-top: 1px solid black;
  margin: 2%;
}
button {
  cursor: pointer;
  border: 0;
  outline: 0;
  border-radius: 10px;
}
h3 button {
  font-size: 1.1vw;
  padding: 1%;
  width: calc(100% / 2 - 2%);
  margin: 1%;
  min-width: 200px;
}
</style>
