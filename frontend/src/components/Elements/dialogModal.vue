<template>
  <ModalApp v-if="typeModal[0] == 'deletar'" ref="modal" @fechar-modal="respostaClick(false)" :typeModal="false">
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
  <ModalApp v-if="typeModal[0] == 'visualizar'" ref="modal" @fechar-modal="respostaClick(false)" :typeModal="true">
    <template #modalTitle>
      <h3>Visualizar Informação:</h3>
    </template>
    <template #modalMessage>
      <h3>
        <label for="id">
          ID:
          <input disabled style="background-color: white; color: black" type="text" value="Informação" id="id" />
        </label>

        <label for="informacao">
          Informação:
          <input style="background-color: white; color: black" disabled type="text" id="informacao" />
        </label>
        <label for="quandoAtualizado">
          Atualizar:
          <input style="background-color: white; color: black" disabled type="text" value="Informação"
            id="quandoAtualizado" />
        </label>
        <label for="quandoCriado">
          Quando foi criado:
          <input style="background-color: white; color: black" disabled type="text" value="Informação"
            id="quandoCriado" />
        </label>
        <label for="quemCriado">
          Por quem foi criado:
          <input style="background-color: white; color: black" disabled type="text" value="Informação" id="quemCriado" />
        </label>
      </h3>
    </template>
  </ModalApp>
  <ModalApp v-if="typeModal[0] == 'editar'" ref="modal" @fechar-modal="respostaClick(false)" :typeModal="true">
    <template #modalTitle>
      <h3>Editar Informação:</h3>
    </template>
    <template #modalMessage>
      <h3>
        <label for="id">
          ID:
          <input type="text" id="id" disabled />
        </label>

        <label for="informacao">
          Informação:
          <input type="text" v-model="informacaoNova" id="informacao" />
        </label>
        <label for="quandoAtualizado">
          Atualizar:
          <input type="text" ref="atualizacao" id="quandoAtualizado" disabled />
        </label>
        <label for="quandoCriado">
          Quando foi criado:
          <input type="text" id="quandoCriado" disabled />
        </label>
        <label for="quemCriado">
          Por quem foi criado:
          <input type="text" id="quemCriado" disabled />
        </label>
        <div class="flexRow" style="justify-content: center">
          <button @click="respostaClick(true)">Salvar</button>
        </div>
      </h3>
    </template>
  </ModalApp>
  <ModalApp v-if="typeModal[0] == 'adicionar'" ref="modal" @fechar-modal="respostaClick(false)" :typeModal="true">
    <template #modalTitle>
      <h3>Adicionar informação</h3>
    </template>
    <template #modalMessage>
      <h3 style="text-align: center">
        Se você quiser adicionar informação, escreva-a no campo abaixo e clique
        em salvar:

        <label style="padding: 3%; text-align: left" for="informacao">
          <p>Informação</p>
          <input v-model="informacaoNova" type="text" id="informacao" placeholder="Digite aqui sua informação" />
        </label>

        <button @click="adicionarInformacao">Salvar</button>
      </h3>
    </template>
  </ModalApp>
</template>

<script lang="ts">
import { Vue, Options } from "vue-class-component";
import ModalApp from "./modal.vue";
import Informacao from "../Informacao";
import infoData from "../Model/infoData"
@Options({
  props: {
    typeModal: Array,
  },
  emits: ["resposta-modal", "exibir-modal", "exibir-loading"],
  mounted() {
    this.typeModal = this.$props.typeModal;
    this.$nextTick(() => {
      const refs = this.$refs;
      refs.modal.exibirModal();
      if (this.typeModal[0] == "visualizar" || this.typeModal[0] == "editar")
        this.buscarInformacao(this.typeModal[1]);
    });
  },
  components: { ModalApp },
})
export default class dialogModal extends Vue {
  typeModal: any = [];
  informacaoNova = "";
  respostaClick(resposta: any) {
    const modal = this.$refs.modal as any;

    if (resposta == true) this.verificarAcao();

    modal.fecharModal();
    this.enviarResposta(resposta);
  }
  verificarAcao() {
    const id = this.typeModal[1];

    switch (this.typeModal[0]) {
      case "deletar":
        this.deletarInformacao(id);
        break;
      case "editar":
        this.atualizarInformacao(id);
        break;
    }
  }
  exibirModal(typeModal: boolean, titleModal: string, messageModal: string) {
    this.$emit("exibir-modal", typeModal, titleModal, messageModal);
  }
  async atualizarInformacao(id: number) {
    const resultado: any = [];
    await Informacao.atualizarInformacao(id, this.informacaoNova);
  }
  async buscarInformacao(id: number) {
    this.$emit("exibir-loading", true);
    await Informacao.buscarInformacao(id)
      .then((resultado) => {
        if (resultado[0] == true) {
          var informacao = resultado[1].informacao;
          informacao = new infoData(
            informacao.id,
            informacao.informacao,
            informacao.created_at,
            informacao.updated_at,
            informacao.user.nomeUsuario
          );

          this.setViewInformacoes(informacao);
          this.$emit("exibir-loading", false);
        } else {
          this.$emit("exibir-loading", false);
          this.$emit(
            "exibir-modal",
            false,
            "Falha",
            "Não foi possível carregar as informações para visualização: </br>" +
            resultado[1]
          );
          //this.enviarResposta(false);
        }
      })
      .finally(() => {
        this.$emit("exibir-loading", false);
      });
  }
  setViewInformacoes(informacao: infoData) {
    function getElementById(id: string) {
      return document.getElementById(id) as HTMLInputElement;
    }
    getElementById("id").value = informacao.id.toString();
    getElementById("informacao").value = informacao.informacao;
    getElementById("quandoAtualizado").value = informacao.atualizadoQuando;
    getElementById("quandoCriado").value = informacao.criadoQuando;
    getElementById("quemCriado").value = informacao.nomeUsuario;
  }
  async deletarInformacao(id: number) {
    await Informacao.deletarInformacao(id).then((result) => {
      if (result[0]) {
        this.$emit(
          "exibir-modal",
          true,
          "Sucesso",
          "A informação foi deletada com sucesso"
        );
      } else {
        this.$emit(
          "exibir-modal",
          false,
          "Falha",
          "A informação não pôde ser deletada: </br>" + result[1]
        );
      }
      return;
    });
  }

  enviarResposta(resposta: boolean) {
    this.$emit("resposta-modal", resposta);
  }
  async adicionarInformacao() {
    this.$emit('exibir-loading', true);

    if (this.informacaoNova.length <= 5) {
      document.getElementById('informacao')?.focus();
    }
    await Informacao.inserirInformacao(this.informacaoNova).then(response => {
      console.log(response);
      if (response[0]) {

        this.$emit('resposta-modal', true);
        this.$emit(
          "exibir-modal",
          true,
          "Sucesso",
          "A informação foi registrada com sucesso"
        );
      } else {
        this.$emit('resposta-modal', true);
        this.$emit(
          "exibir-modal",
          false,
          "Falha no registro",
          response[1]
        );
        this.$emit('resposta-modal', false);
      }
    }).finally(() => {
      this.$emit('exibir-loading', false);
    })
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
  font-size: 20px;
  padding: 1%;
  width: calc(100% / 2 - 2%);
  margin: 1%;
  min-width: 200px;
}

label {
  padding: 0% 4%;
  display: flex;
  flex-flow: column wrap;
}

h3.modalMessage {
  padding: 0 2%;
  width: 100%;
}

input#informacao {
  width: 100%;
  border-radius: 10px;
  border: 0;
  outline: 0;
  padding: 2%;
}

p.error {
  color: red;
  font-size: 0.8vw;
}

@media (max-width: 900px) {
  h3 button {
    padding: 3%;
  }
}
</style>
