
  <template>
    <h1 id="loading" class="carregando">
    <i class="fa-solid fa-gear loading"></i>
    Carregando
  </h1>
  </template>
  
  <script lang="ts">
  import { Vue, Options } from 'vue-class-component';
  @Options({

  })
  export default class loadingComp extends Vue {
  }
  </script>
  
  <style>
  h1.carregando {
  background-color: rgba(0, 0, 0, 0.897);
  display: flex;
  position: absolute;
  z-index: 4;
  left: 0;
  top: 0;
  flex-flow: column nowrap;
  justify-content: center;
  align-items: center;
  font-weight: 100;
  text-transform: uppercase;
  padding: 2%;
  width: 100%;
  height: 100%;
  color: white;
  text-shadow: 2px 2px 2px black;
}
h1.carregando i {
  color: var(--vermelho_1);
  text-shadow: 4px 2px 2px black;
  font-size: 10vw;
}

.loading {
  animation: rotate 2s ease-in-out infinite;
}

@keyframes rotate {
  0% {
    transform: rotate(-360deg);
    opacity: 1;
  }
  50% {
    transform: rotate(0);
    opacity: 1;
  }
  100% {
    transform: rotate(360deg);
    opacity: 1;
  }
}

@media (max-width:1500px) {
  h1.carregando i {
  font-size: 70px;
}
}

  </style>