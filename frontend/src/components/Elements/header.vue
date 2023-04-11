<template>
  <section class="header">
    <div class="container">
      <nav class="flexRow">
        <div class="logo">
          <a @click="atualizarPagina('home')"
            ><i class="fa-regular fa-font-awesome"></i
          ></a>
        </div>

        <ol class="desktopItems flexRow" v-if="estaLogado == false">
          <li>
            <a class="home" ref="home" @click="atualizarPagina('home')">
              <i class="fa-solid fa-house"></i>
              Home
              <span></span>
            </a>
          </li>
          <li>
            <a class="login" @click="atualizarPagina('login')">
              <i class="fa-solid fa-arrow-up-right-from-square"></i>
              Login
              <span></span>
            </a>
          </li>
          <li>
            <a class="registro" @click="atualizarPagina('registro')">
              <i class="fa-regular fa-pen-to-square"></i>
              Registro
              <span></span>
            </a>
          </li>
        </ol>
        <ol class="desktopItems flexRow" v-if="estaLogado == true">
          <li>
            <a class="home" @click="atualizarPagina('home')">
              <i class="fa-solid fa-house"></i>
              Home
              <span></span>
            </a>
          </li>
          <li>
            <a class="painelApp" @click="atualizarPagina('PainelApp')">
              <i class="fa-solid fa-user"></i>
              Painel
              <span></span>
            </a>
          </li>
          <li>
            <a class="registro" @click="logout()">
              <i class="fa-solid fa-right-from-bracket"></i>
              Sair
              <span></span>
            </a>
          </li>
        </ol>
        <h1 id="mobileClick" @click="mobileClick">
          <i id="mobileClick" class="fa-solid fa-bars"></i>
        </h1>
        <ol
          class="mobileItems flexRow"
          v-click-outside="visibleFalseMobile"
          v-if="estaLogado == false"
        >
          <li>
            <a class="home" @click="atualizarPagina('home')">
              <i class="fa-solid fa-house"></i>
              Home
              <span></span>
            </a>
          </li>
          <li>
            <a class="login" @click="atualizarPagina('login')">
              <i class="fa-solid fa-arrow-up-right-from-square"></i>
              Login
              <span></span>
            </a>
          </li>
          <li>
            <a class="registro" @click="atualizarPagina('registro')">
              <i class="fa-regular fa-pen-to-square"></i>
              Registro
              <span></span>
            </a>
          </li>
        </ol>
        <ol
          class="mobileItems flexRow"
          v-click-outside="visibleFalseMobile"
          v-if="estaLogado == true"
        >
          <li>
            <a class="home" @click="atualizarPagina('home')">
              <i class="fa-solid fa-house"></i>
              Home
              <span></span>
            </a>
          </li>
          <li>
            <a class="painelApp" @click="atualizarPagina('PainelApp')">
              <i class="fa-solid fa-user"></i>
              Painel
              <span></span>
            </a>
          </li>
          <li>
            <a class="registro" @click="logout()">
              <i class="fa-solid fa-right-from-bracket"></i>
              Sair
              <span></span>
            </a>
          </li>
        </ol>
      </nav>
    </div>
  </section>
</template>
<script lang="ts">
import { Options, Vue } from "vue-class-component";
import User from "../User";

// eslint-disable-next-line
// @ts-ignore
import vClickOutside from "click-outside-vue3";

interface HeaderProps {
  currentPage: string;
}

interface HeaderMethods {
  atualizarPagina(novoValor: string): void;
}

@Options({
  directives: {
    clickOutside: vClickOutside.directive,
  },
  emits: ["atualizar-pagina", "exibir-modal"],
  components: {},
  data() {
    return {
      estaLogado: Boolean,
    };
  },
  updated() {
    this.activeEffect();
    this.estaLogado = this.verificaLogado();
  },
  mounted() {
    this.activeEffect();
    this.estaLogado = this.verificaLogado();
  },
  methods: {
    activeEffect() {
      const navItems = document.querySelectorAll("ol li a");
      navItems.forEach((item) => {
        item.classList.remove("ativo");
        const classActive = item.getAttribute("class")?.toLocaleLowerCase();
        const currentPage = this.currentPage.toLocaleLowerCase();
        if (currentPage == classActive) {
          item.classList.add("ativo");
        }
      });
    },
  },
  props: {
    currentPage: String,
  },
})
export default class HeaderApp
  extends Vue
  implements HeaderProps, HeaderMethods
{
  currentPage!: string;
  visible = true;
  estaLogado = false;
  invisibleFunc = () => {
    const mobileElement = document.querySelector(".mobileItems");
    mobileElement?.classList.add("slide-out-top");
    mobileElement?.classList.remove("slide-in-top");
  };
  visibleFunc = () => {
    const mobileElement = document.querySelector(".mobileItems");
    mobileElement?.classList.add("slide-in-top");
    mobileElement?.classList.remove("slide-out-top");
  };

  mobileClick() {
    if (this.visible === true) {
      this.invisibleFunc();
      this.visible = false;
    } else if (this.visible === false) {
      this.visibleFunc();
      this.visible = true;
    }
    return;
  }
  visibleFalseMobile(event: Event | null) {
    if (event !== null && (event.target as HTMLElement).id == "mobileClick")
      return;

    this.visible = false;
    this.invisibleFunc();
  }
  verificaLogado(): boolean {
    return User.verificarLogado();
  }
  async logout() {
    await User.logout();
    this.atualizarPagina("home");
    this.estaLogado = false;
  }
  atualizarPagina(novoValor: string) {
    // if (novoValor == "login" || novoValor == "registro") return;
    this.visibleFalseMobile(null);
    if (this.estaLogado) {
      if (novoValor == "login" || novoValor == "registro") {
        this.$emit(
          "exibir-modal",
          false,
          "Ops, caminho errado, Cowboy!",
          "Você não pode acessar essa página, pois já está logado"
        );

        return;
      }
    }
    this.$emit("atualizar-pagina", novoValor);
  }
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
section.header {
  background-color: var(--azul_3);
  color: white;
  height: 5%;
}

section.header a {
  font-size: 1.2vw;
}

ol.mobileItems {
  display: none;
}

section.header > div.container {
  height: 100%;
}

h1#mobileClick {
  display: none;
}

nav {
  justify-content: space-around;
  width: 100%;
  align-items: center;
  height: 100%;
}

div.logo {
  width: calc(100% / 2);
  cursor: pointer;
  text-align: center;
  font-weight: 900;
  text-transform: uppercase;
}

div.logo a {
  background-color: var(--vermelho_2);
  outline: 2px solid var(--azul);
  padding: 0 4%;
  width: 100%;
  text-shadow: 0.1vw 0.1vw var(--azul_2);
  border-radius: 10px;
}

div.logo a,
div.logo i {
  font-size: 1.5vw;
}

a.home {
  display: flex;
  align-items: center;
}

ol.desktopItems i {
  font-size: 1vw;
}

ol.desktopItems li {
  position: relative;
  text-align: center;
  display: flex;
  justify-content: center;
}

ol.desktopItems a:hover > span {
  width: 100%;
}

ol.desktopItems span {
  display: block;
  width: 0%;
  height: 2px;
  background-color: var(--vermelho_2);
  position: absolute;
  bottom: 0;
  right: 0;
  transition: ease-in-out 0.5s;
}

ol.desktopItems a i {
  padding: 2%;
  border-inline: 4px solid var(--azul_3);
}

ol li a {
  display: flex;
  align-items: center;
}

ol.desktopItems {
  justify-content: center;
  list-style: none;
  width: calc(100% / 2);
}

ol.desktopItems li {
  width: calc(100% / 3);
}
ol li a {
  color: var(--branco);
}
ol li a.ativo {
  color: var(--vermelho_2);
}
ol.desktopItems li a {
  padding: 4%;
  cursor: pointer;
  font-weight: 700;
  text-transform: uppercase;
}

@media (max-width: 1500px) {
  section.header a {
    font-size: 22px;
  }

  ol.desktopItems i {
    font-size: 22px;
  }
}

@media (max-width: 1190px) {
  section.header > div.container {
    padding: 0;
    max-width: 90%;
  }

  section.header nav {
    width: 100%;
    padding: 0;
  }

  section.header nav .desktopItems {
    width: calc(100% - 30%);
    padding: 0 2%;
  }

  section.header nav .logo {
    width: 30%;
    padding: 0;
    margin: 0;
    font-size: 22px;
  }

  section.header nav .logo i {
    font-size: 25px;
    height: 0;
  }

  section.header nav .logo a {
    padding: 1% 10%;
    text-align: center;
  }
}

@media (max-width: 690px) {
  section.header > div.container {
    max-width: 100%;
  }

  section.header {
    position: relative;
    height: 7%;
  }

  ol.desktopItems {
    display: none;
  }

  ol.mobileItems {
    display: flex;

    list-style: none;
    text-align: center;
    position: absolute;
    left: 50%;
    top: 110%;
    transform: translateX(-50%);
    width: 80%;
    flex-flow: column nowrap;
  }

  section.header nav .logo a {
    padding: 4% 20%;
    text-align: center;
  }

  h1#mobileClick {
    display: block;
    font-size: 25px;
    background-color: var(--azul);
    border-radius: 10px;
    outline: 1px solid var(--branco);
    padding: 1% 8%;
    cursor: pointer;
  }

  ol.mobileItems li a {
    display: inline-block;
    padding: 2%;
    background-color: var(--azul_2);
    width: 100%;
    height: 100%;
    border-top: 1px solid var(--branco);
    border-left: 1px solid var(--branco);
    border-right: 1px solid var(--branco);
    cursor: pointer;
  }

  /* Animações */
  /* ----------------------------------------------
 * Generated by Animista on 2023-3-20 8:56:47
 * Licensed under FreeBSD License.
 * See http://animista.net/license for more info. 
 * w: http://animista.net, t: @cssanimista
 * ---------------------------------------------- */

  .slide-in-top {
    -webkit-animation: slide-in-top 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94)
      both;
    animation: slide-in-top 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
  }

  @keyframes slide-in-top {
    0% {
      -webkit-transform: translateY(-1000px) translateX(-50%);
      transform: translateY(-1000px) translateX(-50%);
      opacity: 0;
    }

    100% {
      -webkit-transform: translateY(0%) translateX(-50%);
      transform: translateY(0%) translateX(-50%);
      opacity: 1;
    }
  }

  .slide-out-top {
    -webkit-animation: slide-out-top 0.3s cubic-bezier(0.55, 0.085, 0.68, 0.53)
      both;
    animation: slide-out-top 0.3s cubic-bezier(0.55, 0.085, 0.68, 0.53) both;
  }

  @keyframes slide-out-top {
    0% {
      -webkit-transform: translateY(0%) translateX(-50%);
      transform: translateY(0%) translateX(-50%);
      opacity: 1;
    }

    100% {
      -webkit-transform: translateY(-1000px) translateX(-50%);
      transform: translateY(-1000px) translateX(-50%);
      opacity: 0;
    }
  }
}
</style>
