<template>
  <section class="trocarSenha">
    <div class="container">
      <h1>Trocar senha</h1>
      <verificarConta @verificar="verificarUser" >
      </verificarConta>
      <div v-if="verificacao == false" class="confirmarSenha">
        <label for="senhaAntiga"
          ><p>Confirme sua senha atual:</p>
          <input
            id="senhaAntiga"
            v-model="senhaAtual"
            @focusout="erroSenhaAtual = verificarSenha(senhaAtual)"
            type="password"
            placeholder="Por favor, repita sua senha atual"
          />
          <small v-html="erroSenhaAtual"></small>
        </label>

        <label style="margin-top: 1%" for="novaSenha"
          ><p>Digite sua nova senha</p>
          <input
            id="novaSenha"
            @focusout="erro = verificarSenha(novaSenha)"
            v-model="novaSenha"
            type="password"
            placeholder="Por favor, digite sua nova senha"
          />
          <small v-html="erro"></small>
        </label>
        <label style="margin-top: 1%" for="repetirNovaSenha"
          ><p>Confirme a sua nova senha</p>
          <input
            id="repetirNovaSenha"
            @focusout="erro = verificarSenha(repetirNovaSenha)"
            v-model="repetirNovaSenha"
            type="password"
            placeholder="Por favor, redigite sua nova senha"
          />
          <small v-html="erro"></small>
        </label>
        <div class="flexRow">
          <button ref="submit_btn" @click="trocarSenhaAction">
            Confirmar e trocar senha
          </button>
        </div>
      </div>
    </div>
  </section>
</template>

<script lang="ts">
import { Vue, Options } from "vue-class-component";
import User from "./User";
import verificarConta from "./Elements/verificarUsuario.vue";
@Options({
  emits: ["atualizar-pagina", "exibir-modal"],
  // data() {

  // },

  components: {
    verificarConta
  },
  props: {},
  methods: {
    verificarLogado(): boolean {
      return User.verificarLogado();
    },
  },
})
export default class trocarSenha extends Vue {
  verificacao = true;
  senhaAtual = "";
  erroSenhaAtual = "";
  erro = "";
  novaSenha = "";
  repetirNovaSenha = "";
  verificarUser(item:any){
   this.verificacao = item;
  }
  verificarNovaSenha() {
    this.erro = User.verificarSenha(this.novaSenha);
  }

  verificarSenha(senha:string){
    return User.verificarSenha(senha);
  }
  async trocarSenhaAction() {
    this.erro = User.verificarSenha(this.novaSenha);
    this.erroSenhaAtual = User.verificarSenha(this.senhaAtual);
    
    if (this.erro.length > 0 || this.erroSenhaAtual.length > 0) {
      const button = this.$refs.submit_btn as HTMLElement;
      button.style.opacity = "50%";
      button.setAttribute("disabled", "true");

      setTimeout(() => {
        button.style.opacity = "100%";
        button.removeAttribute("disabled");
      }, 3000);
      return;
    }

    const resultado = await User.trocarSenha(this.senhaAtual, this.novaSenha, this.repetirNovaSenha)
    if(resultado[0]){
      this.$emit('exibir-modal', true, 'Sucesso!', 'Senha alterada com sucesso, na próxima vez que for acessar sua conta, utilize a nova senha.');
      this.$emit('atualizar-pagina', 'PainelApp');
    }else{
      this.$emit('exibir-modal', false, 'Falha!', resultado[1]);
    }
  }
}
</script>

<style>
section.trocarSenha {
  padding: 2%;
  height: 90%;
}
section.trocarSenha label {
  width: 100%;
  display: block;
}
section.trocarSenha label p{
  padding:0 2%;
}
section.trocarSenha label p,
input {
  font-size: 1.1vw;
}
section.trocarSenha label input {
  width: 100%;

  padding: 1%;
  border: 0;
  outline: 0;
  background-color: var(--azul);
  color: white;
  box-shadow: 12px 12px 20px -12px rgba(0, 0, 0, 0.35);
  border-radius: 10px;
}
section.trocarSenha div.container {
  height: 100%;
}
section.trocarSenha div.container h1 {
  text-transform: uppercase;
  font-weight: 900;
  font-size: 1.3vw;
  padding: 1%;
  border-bottom: 1px solid black;
  margin-bottom: 5%;
}
section.trocarSenha label small {
  font-size: 0.8vw;
  display: block;
  margin-top: 0%;
  padding: 0 0 1% 1%;
  color: red;
}
section.trocarSenha button {
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
section.trocarSenha button:hover {
  background-color: var(--azul_3);
}
section.trocarSenha div.flexRow {
  justify-content: center;
  padding: 1%;
}

@media (max-width: 1500px) {
  section.trocarSenha label p,
  input {
    font-size: 20px;
  }
  section.trocarSenha input {
    padding: 2% !important;
  }
  section.trocarSenha div.container h1 {
    font-size: 30px;
  }
  section.trocarSenha label small {
    font-size: 16px;
  }
  section.trocarSenha button {
    font-size: 22px;
    padding: 2%;
    max-width: 80%;
  }

  @media (max-width: 600px) {
    section.trocarSenha div.container {
      max-width: 100%;
      padding: 2%;
    }
    section.trocarSenha button {
      margin-top: 20px;
    }
  }
}
@media (max-width: 690px) {
  section.trocarSenha {
    height: 88%;
  }
}
/* ----------------------------------------------
 * Generated by Animista on 2023-3-29 9:57:28
 * Licensed under FreeBSD License.
 * ---------------------------------------------- */
 .ping{animation:ping .8s ease-in-out infinite both}
 @keyframes ping{0%{transform:scale(.2);opacity:.8}80%{transform:scale(1.2);opacity:0}100%{transform:scale(2.2);opacity:0}}
</style>
