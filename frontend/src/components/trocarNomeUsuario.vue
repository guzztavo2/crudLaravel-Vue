<template>
  <section class="trocarNomeUsuario">
    <div class="container">
      <h1>Trocar nome usuário</h1>
      <verificarConta @verificar="verificarUser"> </verificarConta>
      <div v-if="verificacao == false" class="confirmarSenha"></div>
      <div v-if="verificacao == false" class="confirmarSenha">
        <label for="nomeUsuario"
          ><p>Trocar nome de usuário:</p>
          <input
            v-model="nomeUsuario"
            id="nomeUsuario"
            type="text"
            placeholder="Digite seu novo nome de usuário aqui"
            @focusout="
              erroNomeUsuario = validarString(5, 50, nomeUsuario.length)
            "
          />
          <small v-html="erroNomeUsuario"></small>
        </label>
        <label for="repitaNomeUsuario"
          ><p>Confirme o nome de usuário digitado:</p>
          <input
            v-model="nomeUsuario2"
            id="repitaNomeUsuario"
            @focusout="
              erroNomeUsuario2 = validarString(5, 50, nomeUsuario2.length)
            "
            type="text"
            placeholder="Confirme o novo nome de usuário redigitando ele aqui"
          />
          <small v-html="erroNomeUsuario2"></small>
        </label>

        <div class="flexRow">
          <button ref="submit_btn" @click="actionTrocaNomeUsuario">
            Confirmar e trocar senha
          </button>
        </div>
      </div>
    </div>
  </section>
</template>

<script lang="ts">
import { Vue, Options } from "vue-class-component";
import verificarConta from "./Elements/verificarUsuario.vue";
import User from "./User";
@Options({
  components: { verificarConta },
})
export default class trocarNomeUsuario extends Vue {
  verificacao = true;
  nomeUsuario = "";
  nomeUsuario2 = "";
  erroNomeUsuario = "";
  erroNomeUsuario2 = "";
  verificarUser(item: any) {
    this.verificacao = item;
  }
  async validarNomeUsuario() {
    this.erroNomeUsuario = this.validarString(5, 50, this.nomeUsuario.length);
    this.erroNomeUsuario2 = this.validarString(5, 50, this.nomeUsuario2.length);

    if (this.erroNomeUsuario.length == 0) {
      if (this.nomeUsuario !== this.nomeUsuario2) {
        this.erroNomeUsuario = "Os nomes precisam ser iguais.";
        this.erroNomeUsuario2 =
          "A confirmação precisa ser igual ao do campo acima.";
        return;
      }
      const response = await User.verificarNomeUsuario(this.nomeUsuario);
      if (response[0] == "false") {
        this.erroNomeUsuario = response[1];
        return;
      }
      this.erroNomeUsuario = "";
    }
  }
  async actionTrocaNomeUsuario() {
    this.validarNomeUsuario();
    if(this.erroNomeUsuario.length == 0 && this.erroNomeUsuario2.length == 0){
      const response = await User.trocarNomeUsuario(this.nomeUsuario, this.nomeUsuario2);
      if(response[0] == 'false'){
        this.$emit('exibir-modal', false, 'Erro!', response[1]);
        return;
      }
      this.$emit('exibir-modal', true, 'Sucesso!', `Nome de usuário trocado com sucesso! Quando
      for acessar a sua conta, utilize esse novo nome.`);
      this.$emit('atualizar-pagina', 'PainelApp');
    }
  }

  validarString(minLenght: number, maxLenght: number, atualLenght: number) {
    if (atualLenght < minLenght) {
      return (
        "O campo precisa de no minimo: " +
        minLenght +
        " caracteres. Por favor, revise-o"
      );
    } else if (atualLenght > maxLenght) {
      return (
        "O campo precisa ded no máximo: " +
        minLenght +
        " caracteres. Por favor, revise-o"
      );
    }

    return "";
  }
}
</script>

<style>
section.trocarNomeUsuario {
  padding: 2%;
  height: 90%;
}
section.trocarNomeUsuario label {
  width: 100%;
  display: block;
}
section.trocarNomeUsuario label p {
  padding: 0 2%;
}
section.trocarNomeUsuario label p,
input {
  font-size: 1.1vw;
}
section.trocarNomeUsuario label input {
  width: 100%;

  padding: 1%;
  border: 0;
  outline: 0;
  background-color: var(--azul);
  color: white;
  box-shadow: 12px 12px 20px -12px rgba(0, 0, 0, 0.35);
  border-radius: 10px;
}
section.trocarNomeUsuario div.container {
  height: 100%;
}
section.trocarNomeUsuario div.container h1 {
  text-transform: uppercase;
  font-weight: 900;
  font-size: 1.3vw;
  padding: 1%;
  border-bottom: 1px solid black;
  margin-bottom: 5%;
}
section.trocarNomeUsuario label small {
  font-size: 0.8vw;
  display: block;
  margin-top: 0%;
  padding: 0 0 1% 1%;
  color: red;
}
section.trocarNomeUsuario button {
  width: 100%;
  font-size: 1.1vw;
  padding: 1%;
  max-width: 50%;
  border: 0;
  background-color: var(--azul_2);
  color: white;
  border-radius: 10px;
  cursor: pointer;
}
section.trocarNomeUsuario button:hover {
  background-color: var(--azul_3);
}
section.trocarNomeUsuario div.flexRow {
  justify-content: center;
  padding: 1%;
}
@media (max-width: 1500px) {
  section.trocarNomeUsuario label p,
  input {
    font-size: 20px;
  }
  section.trocarNomeUsuario input {
    padding: 2% !important;
  }
  section.trocarNomeUsuario div.container h1 {
    font-size: 30px;
  }
  section.trocarNomeUsuario label small {
    font-size: 16px;
  }
  section.trocarNomeUsuario button {
    font-size: 22px;
    padding: 2%;
    max-width: 80%;
  }

  @media (max-width: 600px) {
    section.trocarNomeUsuario div.container {
      max-width: 100%;
      padding: 2%;
    }
    section.trocarNomeUsuario button {
      margin-top: 20px;
    }
  }
}
@media (max-width: 690px) {
  section.trocarNomeUsuario {
    height: 88%;
  }
}
</style>
