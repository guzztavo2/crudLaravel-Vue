<template>
  <loadingComp v-if="openLoading"></loadingComp>
  <modalApp
    v-if="openModal"
    ref="modal"
    @fechar-modal="fecharModal"
    :typeModal="typeModal"
  >
    <template #modalTitle>
      <h3 v-html="titleModal"></h3>
    </template>
    <template #modalMessage>
      <h3 v-html="messageModal"></h3>
    </template>
  </modalApp>
  <headerApp

    :currentPage="currentPage"
    @atualizar-pagina="atualizarPagina"
    @exibir-modal="dispararModal"
  ></headerApp>
  <component
    :is="currentPage"
    @exibir-loading="exibirLoading"
    @exibir-modal="dispararModal"
    @atualizar-pagina="atualizarPagina"
  ></component>
  <footerApp></footerApp>
</template>

<script lang="ts">
import { Options, Vue } from "vue-class-component";
import Home from "./components/Home.vue";
import Registro from "./components/Registro.vue";
import Login from "./components/Login.vue";
import headerApp from "./components/Elements/header.vue";
import footerApp from "./components/Elements/footer.vue";
import ModalApp from "./components/Elements/modal.vue";
import PainelApp from "./components/Painel.vue";
import trocarSenha from "./components/trocarSenha.vue";
import User from "./components/User";
import crud from "./components/Crud.vue";
import trocarNomeUsuario from "./components/trocarNomeUsuario.vue";
import loadingComp from "./components/Elements/loading.vue";
interface AppData {
  currentPage: string;
}

interface modalProps {
  titleModal: string;
  messageModal: string;
  typeModal: boolean;
  openModal: boolean;
  dispararModal(
    typeModal: boolean,
    titleModal: string,
    messageModal: string
  ): void;
}
@Options({
  components: {
    headerApp,
    footerApp,
    Home,
    Registro,
    Login,
    ModalApp,
    trocarSenha,
    PainelApp,
    trocarNomeUsuario,
    crud,
    loadingComp,
  },
  methods: {},
  updated() {
    this.verificarUsuario();
  },
  created() {
    this.verificarUsuario();
  },
  mounted() {
    this.verificarUsuario();
  },
})
export default class App extends Vue implements AppData, modalProps {
  // *** Props para abrir e definir o Estilo do Modal ***
  titleModal = "";
  messageModal = "";
  typeModal = true;
  openModal = false;
  // *****
  // Props abrir a página de carregamento da aplicação
  openLoading = false;

  exibirLoading(valor:boolean){
    this.openLoading = valor;
  }
  // ******
  publicPages = ["home", "registro", "login"];
  dispararModal(
    typeModal: boolean,
    titleModal: string,
    messageModal: string
  ): void {
    this.openModal = true;
    this.$nextTick(() => {
      this.typeModal = typeModal;
      const modal: any = this.$refs.modal;
      this.titleModal = titleModal;
      this.messageModal = messageModal;

      modal.exibirModal();
    });
  }

  fecharModal() {
    this.openModal = false;
  }
  currentPage = "crud";
  atualizarPagina(novaPagina: string) {
    if (User.verificarLogado() == false) {
      if (this.publicPages.includes(novaPagina)) {
        this.currentPage = novaPagina;
        return;
      }
    } else {
      this.currentPage = novaPagina;
      return;
    }
    this.dispararModal(
      false,
      "Ops, falha de navegação!",
      "Verificamos que você não pode continuar logado, pois sua conta foi acessada em outra sessão/dispositivo."
    );
    this.atualizarPagina("home");
  }
  async verificarUsuario() {
    const response = await User.verificarUsuario();

    if (response !== null && response == false) {
      this.dispararModal(
        false,
        "Ops, falha de navegação!",
        "Verificamos que você não pode continuar acessando sua conta, pois pode haver uma instabilidade no servidor e/ou alguém de fora acessou sua conta. </br></br>Tente acessa-la novamente através da tela de login, caso você tenha sucesso, bem provável que outra pessoa já tem acesso a sua conta."
      );
      this.atualizarPagina("login");
    }
    return;
  }
}
</script>

<style></style>
