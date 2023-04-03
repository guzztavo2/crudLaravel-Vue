<template>
  <section class="registro">
    <div class="container">
      <h1>Registro de usuário</h1>

      <div class="form flexColumn">
        <label for="nomeUsuario">
          <p>Nome de usuário:</p>

          <input
            @focusout="verificarNomeUsuario"
            type="text"
            v-model="nomeUsuario"
            placeholder="Digite seu nome de usuário"
            name="nomeUsuario"
            @input="validarNomeUsuario"
            id="nomeUsuario"
          />
          <div class="error"></div>
        </label>
        <label for="senhaUsuario">
          <p>Senha de usuário:</p>

          <input
            type="password"
            v-model="senhaUsuario"
            placeholder="Digite sua senha de usuário"
            @input="validarSenhaUsuario"
            name="senhaUsuario"
            id="senhaUsuario"
          />
          <div class="error"></div>
        </label>
        <label for="repetirSenha">
          <p>Repetir senha de usuário:</p>
          <input
            type="password"
            v-model="repeteSenhaUsuario"
            placeholder="Repita sua senha de usuário"
            @input="validarSenhaRepetida"
            name="repetirSenha"
            id="repetirSenha"
          />
          <div class="error"></div>
        </label>
        <button @click="submitRegistroFromAxios" id="registro__btn">
          Registrar
        </button>
      </div>
    </div>
  </section>
</template>

<script lang="ts">
import { Options, Vue } from "vue-class-component";
import headerApp from "./Elements/header.vue";
import footerApp from "./Elements/footer.vue";
import axios from "axios";
import ModalApp from "./Elements/modal.vue";
import User from "./User";

@Options({
  emits: ["atualizar-pagina", "exibir-modal", "exibir-loading"],
  data() {
    return {
      nomeUsuario: "guzztavo2",
      senhaUsuario: "gututeleS2010@",
      repeteSenhaUsuario: "gututeleS2010@",
    };
  },
  components: {
    headerApp,
    footerApp,
    ModalApp,
  },
  props: {},

  methods: {},
})
export default class Registro extends Vue {
  nomeUsuario!: string;
  senhaUsuario!: string;
  repeteSenhaUsuario!: string;

  validarNomeUsuario() {
    let errorElement = document.querySelector(
      'label[for="nomeUsuario"] div.error'
    );
    this.minLenght(5, 20, this.nomeUsuario.length, errorElement);
  }
  validarSenhaUsuario() {
    let errorElement = document.querySelector(
      'label[for="senhaUsuario"] div.error'
    );
    this.minLenght(10, 20, this.senhaUsuario.length, errorElement);
    if (!/[A-Z]/.test(this.senhaUsuario))
      errorElement!.innerHTML +=
        "<br> A senha precisa ter no minimo uma letra maiuscula.";
    if (!/[0-9]/.test(this.senhaUsuario))
      errorElement!.innerHTML +=
        "<br> A senha precisa ter no minimo um número.";
  }
  validarSenhaRepetida() {
    let errorElement = document.querySelector(
      'label[for="repetirSenha"] div.error'
    );
    this.minLenght(10, 20, this.repeteSenhaUsuario.length, errorElement);
    if (this.senhaUsuario != this.repeteSenhaUsuario) {
      errorElement!.innerHTML += "<br> As duas senhas precisam ser iguaís.";
    }
  }
  minLenght(
    Minlenght: number,
    MaxLenght: number,
    atualLenght: number,
    errorElement: Element | null
  ) {
    if (atualLenght <= MaxLenght) errorElement!.innerHTML = "";
    else if (atualLenght > MaxLenght) {
      errorElement!.innerHTML =
        "Esse campo passou de " +
        MaxLenght +
        " cacteres, por favor, tente novamente";
    }
    if (atualLenght < Minlenght)
      errorElement!.innerHTML =
        "Esse campo precisa no minimo de: " + Minlenght + " caracteres.";
  }

  async verificarNomeUsuario() {
    let errorElement = document.querySelector(
      'label[for="nomeUsuario"] div.error'
    );

    const styleNomeUsuario = (opacityStr: string) => {
      // eslint-disable-next-line
      // @ts-ignore
      document.querySelector("#nomeUsuario").style.opacity = opacityStr;
    };
    styleNomeUsuario("40%");
    this.$emit("exibir-loading", true);
    const response = await User.verificarNomeUsuario(this.nomeUsuario);
    if (response[0] == "false") {
      errorElement!.innerHTML = response[1];
      styleNomeUsuario("100%");
    } else {
      errorElement!.innerHTML = "";
      styleNomeUsuario("100%");
    }
    this.$emit("exibir-loading", false);
    return;
  }
  async submitRegistroFromAxios() {
    this.validarNomeUsuario();
    this.validarSenhaUsuario();
    this.validarSenhaRepetida();

    const verificarErros = () => {
      return !Array.from(document.querySelectorAll("label div.error")).some(
        (item) => {
          return item.innerHTML.length > 0;
        }
      );
    };
    const opacityRegistroBtn = (opacity: string, disabled: boolean) => {
      const btn = document.querySelector("#registro__btn");
      (btn as HTMLElement).style.opacity = opacity;
      if (disabled) btn?.setAttribute("disabled", "true");
      else btn?.removeAttribute("disabled");
    };

    if (!verificarErros()) {
      opacityRegistroBtn("50%", true);
      setTimeout(() => {
        opacityRegistroBtn("100%", false);
      }, 2000);
      return;
    }
    const inputOpacity = (opacityStr: string) => {
      (document.querySelector("#nomeUsuario") as HTMLElement).style.opacity =
        opacityStr;
      (document.querySelector("#senhaUsuario") as HTMLElement).style.opacity =
        opacityStr;
      (document.querySelector("#repetirSenha") as HTMLElement).style.opacity =
        opacityStr;
    };
    inputOpacity("55%");
    this.$emit("exibir-loading", true);

    User.registrarUsuario(this.nomeUsuario, this.senhaUsuario)
      .then((response) => {
        inputOpacity("100%");
        if (response[0] == false) {
          this.$emit(
            "exibir-modal",
            false,
            "Não foi possível registrar o usuário!",
            response[1]
          );
        } else {
          this.$emit("atualizar-pagina", "login");

          this.$emit(
            "exibir-modal",
            true,
            "Sucesso no registro!",
            "Parabéns, " +
              this.nomeUsuario +
              "!<br />Você foi registrado com sucesso!<br />Automaticamente será redirecionado para a pagina de login."
          );
        }
      })
      .finally(() => {
        this.$emit("exibir-loading", false);
      });
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
section.registro {
  height: 90%;
}

section.registro > div.container h1 {
  width: 100%;
  text-align: center;
  font-weight: 900;
  text-transform: uppercase;
  padding: 1%;
  border-bottom: 1px solid black;
  border-left: 1px solid black;
  border-right: 1px solid black;
}

section.registro div.container {
  height: 100%;
}
label p {
  padding: 0 1%;
}
div.form {
  width: 100%;
  justify-content: center;
  align-items: center;

  height: 70%;
}

div.form label {
  padding: 1% 15%;
  width: 100%;
}

div.form input {
  width: 100%;
  padding: 1%;
  outline: 0;
  border-radius: 0.2vw;
  border: 0;
  color: white;
  background-color: #47b2d6;
  font-size: 1.3vw;
  box-shadow: rgba(0, 0, 0, 0.35) 0.2vw 0.2vw 0.2vw;
  transition: ease-in-out 0.2s;
}

div.form input:focus {
  box-shadow: rgb(0, 0, 0) 0.2vw 0.2vw 0.2vw;
}

div.form button {
  border: 0;
  border-radius: 0.4vw;
  text-transform: lowercase;
  color: White;
  max-width: 60%;
  width: 100%;
  padding: 1% 2%;
  cursor: pointer;
  font-size: 1.5vw;
  background-color: var(--azul_2);
  transition: ease-in-out 0.2s;
  box-shadow: rgba(0, 0, 0, 0.35) 0.2vw 0.2vw 0.2vw;
}

div.form button:hover {
  background-color: var(--azul_3);
  box-shadow: rgba(0, 0, 0, 0.856) 0.2vw 0.2vw 0.2vw;
}

label div.error {
  color: var(--vermelho_2);
  width: 100%;
  font-size: 1vw;
  padding: 1% 2%;
}

@media (max-width: 1500px) {
  div.form button {
    font-size: 22px;
    max-width: 80%;
  }

  div.form input {
    font-size: 20px;
  }

  label div.error {
    font-size: 18px;
  }

  div.form label {
    padding: 1% 2%;
  }

  .container {
    max-width: 100%;
    padding: 0 2%;
  }
}

@media (max-width: 690px) {
  section.registro {
    height: 88%;
  }
}
</style>
