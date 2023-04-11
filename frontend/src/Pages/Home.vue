<template>
  <section class="home">
    <div class="container">
      <h1>Seja bem vindo ao CRUD APP</h1>
      <p>
        A aplicação é um CRUD (Create, Read, Update, Delete).<br /><br />
        Desenvolvido com Laravel API no back-end e VueJS no front-end,
        permitindo o acesso e registro de informações de forma gratuita.<br /><br />
        A escolha das tecnologias utilizadas garante a segurança dos dados dos
        usuários, pois a API é separada do front-end e é 100% segura.<br /><br />
        O objetivo do desenvolvimento da aplicação é demonstrar as habilidades
        do programador em programação. Para acessar o CRUD, é necessário criar
        uma conta e fazer login através dos campos de registro e login
        disponíveis.<br /><br />
        A aplicação também é responsiva, podendo ser acessada por diferentes
        dispositivos e telas.<br /><br />
        Além disso, a aplicação possui outras funcionalidades avançadas que a
        tornam uma ferramenta completa e eficiente para gerenciamento de
        informações.
      </p>
    </div>
  </section>
</template>

<script lang="ts">
import { Options, Vue } from "vue-class-component";
@Options({
  props: {},
  methods: {}
})
export default class Home extends Vue {}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
section.home {
  height: 90%;
  background-color: var(--branco);
}

section.home div.container {
  height: 85%;
  display: flex;
  flex-flow: column nowrap;
  justify-content: center;
  overflow: auto;
}
section.home h1 {
  font-size: 1.5vw;
  font-weight: 400;
  text-align: center;
  border-bottom: 1px solid var(--azul_3);
  color: var(--azul_3);
  text-transform: uppercase;
}
section.home p {
  max-width: 80%;
  padding: 2% 4%;
  margin: 0 auto;
  margin-top: 20px;
  border: 1px solid black;
  user-select: none;
  background-color: var(--azul);
}

@media (max-width: 1500px) {
  section.home h1 {
    font-size: 30px;
  }
}

@media (max-width: 830px) {
  section.home div.container {
    min-height: 100%;
    max-width: 100%;
  }
}
@media (max-width: 550px) {
  section.home div.container {
    height: 100%;
    min-height: 0;
    flex-flow: column nowrap;
    justify-content: start;
    padding: 2%;
  }
  section.home h1 {
    height: 10%;
  }
  section.home p {
    height: 90%;
    padding: 4%;
    overflow: auto;
    border: none;
  }
}
@media (max-width: 690px) {
  section.home {
    height: 88%;
  }
}
</style>
