<template>
  <section class="painel">
    <div class="container">
      <h1 class="placeholder" ref="userName">...</h1>
      <nav>
        <ol>
          <li>
            <p @click="$emit('atualizar-pagina', 'crud')">
              Acessar a aplicação CRUD <i class="fa-solid fa-chalkboard"></i>
            </p>
          </li>

          <li>
            <p @click="$emit('atualizar-pagina', 'trocarSenha')">
              Trocar senha <i class="fa-solid fa-lock"></i>
            </p>
          </li>
          <li>
            <p @click="$emit('atualizar-pagina', 'trocarNomeUsuario')">
              Trocar nome de usuário <i class="fa-solid fa-key"></i>
            </p>
          </li>
          <li>
            <p @click="$emit('atualizar-pagina', 'home')">
              Pagina inicial
              <i class="fa-solid fa-house-user"></i>
            </p>
          </li>
        </ol>
      </nav>
    </div>
  </section>
</template>

<script lang="ts">
import { Options, Vue } from "vue-class-component";
import User from "./User";
@Options({
  emits: ["atualizar-pagina", "exibir-modal", "verificar-usuario"],
  components: {},
  props: {},
  mounted() {
    this.carregarNomeUsuario();
  },
})
export default class PainelApp extends Vue {
  carregarNomeUsuario() {
    const ref = this.$refs.userName as HTMLElement;
    ref.classList.add("placeholder");
    User.getUserName().then((response) => {
      ref.innerHTML = "Seja bem vindo, " + response + ". O que fará hoje?";
      ref.classList.remove("placeholder");
    });
  }
}
</script>

<style scoped>
section.painel {
  height: 90%;
}
section.painel div.container {
  display: flex;
  flex-flow: column nowrap;
  height: 100%;

  align-content: center;
}
section.painel h1 {
  display: block;

  text-align: center;
  text-transform: uppercase;
  padding: 1%;
  border-bottom: 1px solid black;
  font-size: 1.5vw;
}

section.painel ol {
  list-style-type: none;
  display: flex;
  flex-flow: column wrap;
  align-items: center;
  padding: 4%;
}

section.painel ol li {
  width: 100%;
  padding: 0.5%;
  margin: 2%;
  max-width: 70%;
}
section.painel ol li p {
  font-size: 1.1vw;
  padding: 2% 4%;
  cursor: pointer;
  border: 1px solid var(--azul);
  color: var(--azul_3);
  transition: ease-in-out 0.2s;
  display: flex;
  flex-flow: row nowrap;
  align-items: center;
  justify-content: space-between;
}
section.painel ol li p:hover {
  background-color: var(--azul_2);
  color: var(--branco);
}
section.painel ol li i {
  font-size: 1.5vw;
}
.placeholder {
  animation: placeholderBackground 2s linear infinite alternate both;
}
@keyframes placeholderBackground {
  0% {
    opacity: 100%;
    background-color: var(--azul_3);
  }
  100% {
    opacity: 50%;
    background-color: var(--azul_3);
  }
}
@media (max-width: 1500px) {
  section.painel h1 {
    font-size: 22px;
  }
  section.painel ol li p {
    font-size: 20px;
    padding: 2% 1%;
  }
}
@media (max-width: 690px) {
  section.painel {
    height: 88%;
  }
  section.painel div.container {
    max-width: 100%;
  }
  section.painel ol li {
    font-size: 20px;
    padding: 3%;
  }
  section.painel ol li p {
    font-size: 20px;
    padding: 5% 2%;
  }
  section.painel ol li i {
    font-size: 25px;
  }
}
@media (max-width: 650px) {
  section.painel ol li {
    max-width: 100%;
  }
}
</style>
