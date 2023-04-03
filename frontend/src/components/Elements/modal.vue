<template>
  <div
    v-if="typeModal == true"
    v-click-outside="fecharModal"
    id="modal"
    :class="['message', modalClassOpenClose, 'success']"
  >
    <div class="title__wrapper flexRow">
      <h3 class="modalTitle"><slot name="modalTitle"></slot></h3>
      <h3 id="fechar" @click="fecharModal">
        <i class="fa-solid fa-xmark"></i>
      </h3>
    </div>
    <div class="text__wrapper flexRow">
      <h3 class="modalMessage"><slot name="modalMessage"></slot></h3>
    </div>
  </div>
  <div
    v-if="typeModal == false"
    v-click-outside="fecharModal"
    id="modal"
    :class="['message', modalClassOpenClose, 'error']"
  >
    <div class="title__wrapper flexRow">
      <slot name="modalTitle"></slot>
      <h3 id="fechar" @click="fecharModal">
        <i class="fa-solid fa-xmark"></i>
      </h3>
    </div>
    <div class="text__wrapper flexRow">
      <h3><slot name="modalMessage"></slot></h3>
    </div>
  </div>
</template>

<script lang="ts">
import { Options, Vue } from "vue-class-component";
// eslint-disable-next-line
// @ts-ignore
import vClickOutside from "click-outside-vue3";

@Options({
  directives: {
    clickOutside: vClickOutside.directive,
  },
  emits:['fechar-modal'],
  props: {
    typeModal: {
      type: Boolean,
      require: true,
    },
  },
})
export default class ModalApp extends Vue {
  modalClassOpenClose = "";

  typeModal!: boolean;

  exibirModal(event: Event) {
    this.modalClassOpenClose = "slide-in-top-Message";
  }

  fecharModal(event: Event) {
    const localName = (event.target as HTMLElement).localName;
    if (
      document.querySelector("#modal")?.classList.contains("dNone") ||
      localName == "a" || localName == 'li'
    )
      return;

    this.modalClassOpenClose = "slide-out-top-Message";
      setTimeout(() => {
        this.$emit('fechar-modal');
      }, 500);
  }
}
</script>

<style>
div.message.error {
  background-color: #ec2132a6;
}

div.message.error div.title__wrapper {
  background-color: var(--vermelho_1);
}

div.message.error div.text__wrapper {
  background-color: var(--vermelho_2);
}

div.message.success {
  background-color: #0931669d;
}

div.message.success div.title__wrapper {
  background-color: var(--azul_2);
}

div.message.success div.text__wrapper {
  background-color: var(--azul);
}

div.message:hover {
  transform: scale(110%) translate(-45%, -50%);
}

div.message.dNone {
  display: none;
}

div.message {
  position: absolute;
  width: 60%;
  padding: 0 0 1% 0;
  z-index: 1;
  transform: translate(-50%, -50%);
  left: 50%;
  top: 30%;
  border-radius: 20px;
  transition: ease-in-out 0.4s;
  box-shadow: rgba(0, 0, 0, 0.705) 0px 3px 8px;
}

div.message div.title__wrapper {
  border-top-left-radius: 20px;
  border-top-right-radius: 20px;

  color: var(--branco);
  width: 100%;
  padding: 1% 2%;
  flex-flow: row nowrap;
  justify-content: space-between;
  align-items: center;
  user-select: none;
  border-bottom: 1px solid white;
}

div.message #fechar {
  cursor: pointer;
  background-color: var(--vermelho_2);
  padding: 1% 3%;
  display: flex;
  font-size: 1.5vw;
  align-items: center;
  border: 1px solid white;
  border-radius: 10px;
}

div.message div.text__wrapper {
  color: white;
  justify-content: center;
  align-items: center;
  padding: 4%;
  margin: 0% 0;
}

.slide-in-top-Message {
  -webkit-animation: slide-in-top-Message 0.3s
    cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
  animation: slide-in-top-Message 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
}

@keyframes slide-in-top-Message {
  0% {
    -webkit-transform: translateY(-1000px) translateX(-50%);
    transform: translateY(-1000px) translateX(-50%);
    opacity: 0;
  }

  100% {
    -webkit-transform: translateY(-50%) translateX(-50%);
    transform: translateY(-50%) translateX(-50%);
    opacity: 1;
  }
}

.slide-out-top-Message {
  -webkit-animation: slide-out-top-Message 0.3s
    cubic-bezier(0.55, 0.085, 0.68, 0.53) both;
  animation: slide-out-top-Message 0.3s cubic-bezier(0.55, 0.085, 0.68, 0.53)
    both;
}

@keyframes slide-out-top-Message {
  0% {
    -webkit-transform: translateY(-50%) translateX(-50%);
    transform: translateY(-50%) translateX(-50%);
    opacity: 1;
  }

  100% {
    -webkit-transform: translateY(-1000px) translateX(-50%);
    transform: translateY(-1000px) translateX(-50%);
    opacity: 0;
  }
}

@media (max-width: 1500px) {
  div.message #fechar {
    font-size: 30px;
  }

  div.message {
    width: 80%;
    padding: 0 0 4% 0;
    top: 20%;
  }
}

@media (max-width: 690px) {
  div.message {
    width: 96%;
    padding: 0 0 5% 0;
    top: 20%;
  }
}
</style>
