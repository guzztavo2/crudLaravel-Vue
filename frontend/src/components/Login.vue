<template>
  <section class="login">
    <div class="container flexColumn">
      <div class="loginForms">
        <p class="title">
          Bem-vindo, aqui você pode acessar a sua conta de usuário:
        </p>
        <label for="nomeUsuario">
          <p>Nome de usuário:</p>
          <input
            type="text"
            @focusout="validarNomeUsuario"
            placeholder="Digite seu nome de usuário"
            v-model="nomeUsuario"
            id="nomeUsuario"
          />
          <div class="error"></div>
        </label>
        <label for="senhaUsuario">
          <p>Senha de usuário:</p>
          <input
            type="password"
            placeholder="Digite sua senha de usuário"
            v-model="senhaUsuario"
            id="senhaUsuario"
          />
          <div class="error"></div>
        </label>
        <button @click="submitLoginForm">Acessar</button>
      </div>
    </div>
  </section>
</template>

<script lang="ts">
import { Options, Vue } from "vue-class-component";
import headerApp from "./Elements/header.vue";
import footerApp from "./Elements/footer.vue";
import User from "./User";
// import axios from "axios";
@Options({
  data() {
    return {
      emits: ["atualizar-pagina", "exibir-modal"],
      nomeUsuario: {
        require: true,
        type: String,
      },
      senhaUsuario: {
        require: true,
        type: String,
      },
    };
  },
  components: {
    headerApp,
    footerApp,
  },
  props: {},
  methods: {},
})
export default class Login extends Vue {
  nomeUsuario = "guzztavo";
  senhaUsuario = "gututeleS2010@";
  validarNomeUsuario() {
    const validacao = this.validarLenghtInputs(5, 50, this.nomeUsuario.length);
    const errorDiv = document.querySelector(
      'label[for="nomeUsuario"] div.error'
    );

    console.log(errorDiv);
    if (validacao !== true) (errorDiv as HTMLElement).innerHTML = validacao;
    else (errorDiv as HTMLElement).innerHTML = "";
    return;
  }

  validarSenhaUsuario() {
    const validacao = this.validarLenghtInputs(5, 50, this.senhaUsuario.length);
    const errorDiv = document.querySelector(
      'label[for="senhaUsuario"] div.error'
    );

    if (validacao !== true) (errorDiv as HTMLElement).innerHTML = validacao;
    else (errorDiv as HTMLElement).innerHTML = "";

    return;
  }

  validarLenghtInputs(
    min: number,
    max: number,
    atualLenght: number
  ): string | true {
    if (atualLenght < min)
      return "O número minimo para esse campo é de: " + min + " caracteres";
    if (atualLenght > max)
      return "O número máximo para esse campo é de: " + max + " caracteres";
    if (atualLenght < max && atualLenght > min) return true;
    return "O campo está com um valor inválido, por favor, reinsira-o ou recarregue a página.";
  }

  submitLoginForm() {
    this.validarNomeUsuario();
    this.validarSenhaUsuario();

    const verificarErros = () => {
      return !Array.from(document.querySelectorAll("label div.error")).some(
        (item) => {
          return item.innerHTML.length > 0;
        }
      );
    };
    const inputOpacityDisable = (
      opacity: string,
      disabled: boolean,
      input: HTMLElement | null
    ) => {
      (input as HTMLElement).style.opacity = opacity;
      if (disabled == true) (input as HTMLElement).setAttribute("disabled", "");

      setTimeout(() => {
        (input as HTMLElement).style.opacity = "100%";
        (input as HTMLElement).removeAttribute("disabled");
      }, 2000);
    };

    if (!verificarErros()) {
      inputOpacityDisable(
        "25%",
        true,
        document.querySelector("div.loginForms button")
      );
      setTimeout(() => {
        this.$emit(
          "exibir-modal",
          false,
          "Erro!",
          "Por favor, verifique os erros que estão dentro dos inputs! Você não conseguirá acessar sua conta, se os erros continuarem ativos."
        );
      }, 500);
    }

    inputOpacityDisable(
      "50%",
      true,
      document.querySelector("div.loginForms input#nomeUsuario")
    );
    inputOpacityDisable(
      "50%",
      true,
      document.querySelector("div.loginForms input#senhaUsuario")
    );
    inputOpacityDisable(
      "50%",
      true,
      document.querySelector("div.loginForms button")
    );

    User.loginUser(this.nomeUsuario, this.senhaUsuario).then((response) => {
      if (response[0] == false) {
        this.$emit(
          "exibir-modal",
          false,
          "Não foi possível acessar o usuário!",
          response[1]
        );
      } else {
        this.$emit("atualizar-pagina", "home");
        this.$emit(
          "exibir-modal",
          true,
          "Acesso concebido!",
          "Parabéns, você está logado em sua conta"
        );
        console.log(User.getLocalStorage());
      }
    });
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
section.login {
  height: 90%;
}

section.login div.container {
  height: 80%;
  align-items: center;
  justify-content: center;
}

section.login div.loginForms {
  padding: 2%;
  outline: 10px solid #09316698;
  box-shadow: rgba(0, 0, 0, 0.35) 0.2vw 0.2vw 0.2vw;

  background-color: #47b2d6b2;
  border-radius: 5px;
  width: 80%;

  display: flex;
  flex-flow: column nowrap;
  align-items: center;
}

section.login div.loginForms label {
  width: 100%;
  display: block;
  padding: 1% 4%;
}

section.login div.loginForms label div.error {
  color: var(--vermelho_1);
  padding-top: 1%;
}
section.login div.loginForms button {
  background-color: var(--vermelho_2);
  box-shadow: rgba(0, 0, 0, 0.35) 0.2vw 0.2vw 0.2vw;
  color: var(--branco);
  width: 100%;
  max-width: 60%;
  margin-top: 5%;
  border-radius: 10px;
  border: 0;
  outline: 0;
  cursor: pointer;
  padding: 1%;
  font-size: 1.5vw;
  transition: ease-in-out 0.2s;
}

section.login div.loginForms button:hover {
  background-color: var(--vermelho_1);
}

p.title {
  font-weight: 100;
  font-size: 1.5vw;
  text-transform: uppercase;
  text-align: center;
  border-bottom: 0.1vw solid black;
  padding: 1%;
}

@media (max-width: 1500px) {



  section.login div.loginForms button {
    font-size: 25px;
  }

  p.title {
    font-size: 25px;
  }

  section.login div.loginForms {
    width: 100%;
  }
}

@media (max-width: 690px) {
  section.login {
    height: 80%;
  }

  section.login div.container {
    height: 90%;
    max-width: 100%;
  }
}
</style>
