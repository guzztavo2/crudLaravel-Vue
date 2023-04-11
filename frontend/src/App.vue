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
  <HeaderApp
    :currentPage="currentPage"
    @atualizar-pagina="atualizarPagina"
    @exibir-modal="dispararModal"
  ></HeaderApp>
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
import Home from "./Pages/Home.vue";
import Registro from "./Pages/Registro.vue";
import Login from "./Pages/Login.vue";
import Crud from "./Pages/Crud.vue";
import PainelApp from "./Pages/Painel.vue";
import trocarSenha from "./Pages/trocarSenha.vue";
import User from "./components/User";
import HeaderApp from "./components/Elements/header.vue";
import footerApp from "./components/Elements/footer.vue";
import trocarNomeUsuario from "./Pages/trocarNomeUsuario.vue";
import loadingComp from "./components/Elements/loading.vue";
import ModalApp from "./components/Elements/modal.vue";

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
    HeaderApp,
    footerApp,
    Home,
    Registro,
    Login,
    ModalApp,
    trocarSenha,
    PainelApp,
    trocarNomeUsuario,
    Crud,
    loadingComp,
  },
  methods: {},
  created() {
    this.verificarUsuario();

    setInterval(()=>{
      this.verificarUsuario();
    },60000);
  },
})
export default class App extends Vue implements AppData, modalProps {
  titleModal = "";
  messageModal = "";
  typeModal = true;
  openModal = false;
  openLoading = false;
  currentPage = "home";

  exibirLoading(valor:boolean){
    this.openLoading = valor;
  }
  publicPages = ["home", "registro", "login"];
  dispararModal(
    typeModal: boolean,
    titleModal: string,
    messageModal: string
  ): void {
    this.openModal = true;
    this.$nextTick(() => {
      this.typeModal = typeModal;
      const modal:any = this.$refs.modal;
      this.titleModal = titleModal;
      this.messageModal = messageModal;

      modal.exibirModal();
    });
  }

  fecharModal() {
    this.openModal = false;
  }
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
      this.exibirLoading(false);
    }
  }
}
</script>

<style></style>
