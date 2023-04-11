<template>
  <div class="verificarConta" v-if="exibir == true">
    <label for="senhaAntiga"
      ><p>Digite sua senha atual para confirmação:</p>
      <input
        id="senhaAntiga"
        @focusout="verificarSenhaAction"
        v-model="senha"
        type="password"
        placeholder="Por favor, digite sua senha antiga"
      />
      <small v-html="erroSenha"></small>
    </label>
    <div class="flexRow" @click="verificarSenhaAction">
      <button>Confirmar</button>
    </div>
  </div>
</template>

<script lang="ts">
import { Vue, Options } from "vue-class-component";
import User from "../User";
@Options({
  components: {},
  methods: {},
  emits: ["verificar" ,"exibir-loading"],
})
export default class verificarConta extends Vue {
  senha = "";
  erroSenha = "";
  exibir = true;

  verificaoAction(value: boolean) {
    this.exibir = value;
  }
  async verificarSenhaAction() {
    this.erroSenha = User.verificarSenha(this.senha);
    this.$emit('exibir-loading', true);
    await User.verificarSenhaUsuario(this.senha).then((result) => {
      if (result[0] == true) {
        this.exibir = false;
        this.$emit("verificar", this.exibir);
      } else{
        this.erroSenha = result[1];
      }
    }).finally(()=>{
      this.$emit('exibir-loading', false);

    })
  }
}
</script>

<style>
div.verificarConta {
  text-align: center;
  padding: 2%;

}
div.verificarConta button {
  width: 100%;
  font-size: 1.1vw;
  padding: 1%;
  max-width: 50%;
  border: 0;
  background-color: var(--azul_2);
  color: white;
  border-radius: 10px;
  cursor: pointer;
  margin-top: 2%;
}
div.verificarConta div.flexRow {
  align-items: center;
  justify-content: center;
}
div.verificarConta input {
  margin: 0;
  border: 1px solid black;
}

@media (max-width: 1500px) {
  div.verificarConta button {
    font-size: 20px;
  }
}
</style>
