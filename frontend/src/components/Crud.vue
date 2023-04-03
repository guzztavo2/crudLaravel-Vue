<template>
  <section class="crud">
    <div class="container">
      <h1>Gerenciar Informações</h1>
      <div class="buttons">
        <button @click="adicionarInformacao" class="adicionar">
          Adicionar Informação <i class="fa-solid fa-plus"></i>
        </button>
        <button
          class="selecionar"
          @click="alterarSelecao"
          v-if="selecaoAtivada == false"
        >
          Selecionar itens <i class="fa-regular fa-square-check"></i>
        </button>
        <button
          class="remover"
          v-if="selecaoAtivada && listInformacoes.length > 0"
        >
          Remover Informação <i class="fa-solid fa-minus"></i>
        </button>
        <button
          class="selecionar"
          @click="alterarSelecao"
          v-if="selecaoAtivada == true"
        >
          Cancelar seleção <i class="fa-regular fa-square-check"></i>
        </button>
      </div>
      <div class="table_wrapper">
        <table v-if="selecaoAtivada === true" ref="table">
          <tr v-if="listInformacoes.length > 0" class="head">
            <th><p>Selecionar</p></th>
            <th><p>ID</p></th>
            <th><p>Informação</p></th>
            <th><p>Data de Criação</p></th>
            <th><p>Última atualização</p></th>
            <th><p>Quem atualizou</p></th>
          </tr>
          <tr class="noItens" v-if="listInformacoes.length == 0">
            <h2>Não há informações cadastradas</h2>
          </tr>
        </table>
        <table v-if="selecaoAtivada === false" ref="table">
          <tr v-if="listInformacoes.length > 0" class="head">
            <th><p>ID</p></th>
            <th><p>Informação</p></th>
            <th><p>Data de Criação</p></th>
            <th>
              <p>Última atualização</p>
            </th>
            <th><p>Quem atualizou</p></th>
            <th><p>*</p></th>
          </tr>
          <tr class="noItens" v-if="listInformacoes.length == 0">
            <h2>Não há informações cadastradas</h2>
          </tr>
        </table>
      </div>
      <div
        v-if="listInformacoes.length > 0"
        ref="paginacao"
        class="flexRow paginacao"
      ></div>
    </div>
  </section>
</template>

<script lang="ts">
class infoData {
  public id!: number;
  public informacao!: string;
  public criadoQuando!: string;
  public atualizadoQuando!: string;
  public nomeUsuario!: string;

  constructor(
    id: number,
    informacao: string,
    criadoQuando: string,
    atualizadoQuando: string,
    nomeUsuario: string
  ) {
    this.id = id;
    this.informacao = informacao;

    this.setCriadoQuando(criadoQuando);
    if (criadoQuando !== atualizadoQuando)
      this.setAtualizadoQuando(atualizadoQuando);
    else this.atualizadoQuando = "Ainda não foi atualizado";
    this.nomeUsuario = nomeUsuario;
  }
  private dataToString(data: string) {
    const date = new Date(data);
    return date.toLocaleString("pt-BR", { timeZone: "America/Sao_Paulo" });
  }
  private setCriadoQuando(criadoQuando: string) {
    this.criadoQuando = this.dataToString(criadoQuando);
  }
  private setAtualizadoQuando(atualizadoQuando: string) {
    this.atualizadoQuando = this.dataToString(atualizadoQuando);
  }
  public static arrayToObject(listArray: any[]) {
    const resultArray: Array<infoData> = [];
    listArray.forEach((item) => {
      const object = new infoData(
        item.id,
        item.informacao,
        item.created_at,
        item.updated_at,
        item.user.nomeUsuario
      );
      resultArray.push(object);
    });

    return resultArray;
  }
}
import { Vue, Options } from "vue-class-component";
import Informacao from "./Informacao";
import { tsImportEqualsDeclaration } from "@babel/types";
@Options({
  async mounted() {
    await this.getInformacoes();
   
  },

  emits: ["exibirModal", "exibir-loading", "atualizarPagina"],
})
export default class crud extends Vue {
  selecaoAtivada = false;
  listInformacoes: Array<infoData> = [];
  currentPage!: number;
  quantityPages!: number;
  resquestOrder: Array<string> = [];

    
  orderByEvent() {
    const elements = document.querySelectorAll("tr.head th[order]");    
    elements.forEach((element) => {
      element.addEventListener('click', ()=>{
         if (this.resquestOrder.length > 0) {
          if (this.resquestOrder[1] == "desc") this.resquestOrder[1] = "asc";
          else this.resquestOrder[1] = "desc";
          this.resquestOrder[0] = element.getAttribute('order')||'';
        } else {
          this.resquestOrder[0] = element.getAttribute('order')||'';
          this.resquestOrder[1] = "desc";
        }
        this.getInformacoes();
      })
    });

    // this.getInformacoes();
  }
  alterarSelecao() {
    if (this.selecaoAtivada) this.selecaoAtivada = false;
    else this.selecaoAtivada = true;

    this.getInformacoes();
  }
  async getInformacoes() {
    this.$emit("exibir-loading", true);
    await Informacao.getInformacoesList(this.currentPage, this.resquestOrder)
      .then((result) => {
        if (result[0] == true) {
          const resultData = result[1];
          this.currentPage = resultData.current_page;
          this.quantityPages = resultData.last_page;

          this.listInformacoes = infoData.arrayToObject(resultData.data);
        }
      })
      .finally(() => {
        this.insertInformacoes();
        this.adicionarPaginacao();
        this.$emit("exibir-loading", false);
      });
  }
  insertInformacoes() {
    const table = this.$refs.table as HTMLElement;
    const selecao = this.selecaoAtivada;
    if (selecao) {
      table.innerHTML = `<tr v-if="listInformacoes.length > 0" class="head">
            <th><p>Selecionar</p></th>
            <th><p>ID</p></th>
            <th><p>Informação</p></th>
            <th><p>Data de Criação</p></th>
            <th><p>Última atualização</p></th>
            <th><p>Quem atualizou</p></th>
          </tr>`;
      this.listInformacoes.forEach((item: any) => {
        table.innerHTML += `<tr identificador="${item.id}" id="informacaoRow">
          <td> <p><input type="checkbox" id="${item.id}" /></p></td>
            <td><p>${item.id}</p></td>
            <td><p>${item.informacao.substring(0, 25)}...</p></td>
            <td><p>${item.criadoQuando}</p></td>
            <td><p>${item.atualizadoQuando}</p></td>
            <td><p>${item.nomeUsuario}</p></td>
          </tr>`;
      });
    } else {
      table.innerHTML = `  <tr v-if="listInformacoes.length > 0" class="head">
            <th order='id'><p>ID</p></th>
            <th order='informacao'><p>Informação</p></th>
            <th order='created_at'><p>Data de Criação</p></th>
            <th order='updated_at'><p>Última atualização</p></th>
            <th><p>Quem atualizou</p></th>
            <th><p>*</p></th>
          </tr>`;
      this.listInformacoes.forEach((item: any) => {
        table.innerHTML += `<tr identificador="${item.id}" id="informacaoRow">
          <td><p>${item.id}</p></td>
            <td><p>${item.informacao.substring(0, 25)}...</p></td>
            <td><p>${item.criadoQuando}</p></td>
            <td><p>${item.atualizadoQuando}</p></td>
            <td><p>${item.nomeUsuario}</p></td>
            <td>
              <p class="flexRow">
                <i class="fa-solid fa-minus">
                  <span>Remover</span>
                </i>
                <i class="fa-solid fa-pen-to-square">
                  <span>Editar</span>
                </i>
              </p>
            </td>
          </tr>`;
      });
    }

    const atualizarInformacaoEvent = (listItems: any) => {
      listItems.forEach(function (item: HTMLElement) {
        item.addEventListener("click", function (event: Event) {
          console.log("evento de lista");
        });
      });
    };
    this.orderByEvent();
    atualizarInformacaoEvent(document.querySelectorAll("#informacaoRow"));
  }

  adicionarPaginacao() {
    const paginacao = this.$refs.paginacao as HTMLElement,
      currentEvent = this.setCurrentPage,
      currentPage = this.currentPage,
      quantidadePaginas = this.quantityPages,
      paginaAnterior = () => {
        const pagina = document.querySelector("#paginaAnterior");
        pagina?.addEventListener("click", function () {
          currentEvent(currentPage - 1);
        });
      },
      proximaPagina = () => {
        const pagina = document.querySelector("#proximaPagina");
        pagina?.addEventListener("click", function () {
          currentEvent(currentPage + 1);
        });
      },
      paginaEvent = () => {
        const paginasEvents = document.querySelectorAll("#paginaEvent");
        paginasEvents.forEach((item) => {
          item.addEventListener("click", function (event: Event) {
            currentEvent(parseInt((event.target as HTMLElement).innerHTML));
          });
        });
      };
    paginacao.innerHTML = "";
    paginacao.innerHTML +=
      '<h1 id="paginaAnterior"><i class="fa-solid fa-caret-left"></i></h1>';

    for (let n = 1; n <= quantidadePaginas; n++) {
      if (n === currentPage)
        paginacao.innerHTML +=
          "<h1 class='marcado' id='paginaEvent'>" + n + "</h1>";
      else paginacao.innerHTML += "<h1 id='paginaEvent'>" + n + "</h1>";
    }
    paginacao.innerHTML = paginacao.innerHTML +=
      '<h1 id="proximaPagina"><i class="fa-solid fa-caret-right"></i></h1>';
    if (this.selecaoAtivada == true) {
      paginacao.innerHTML = "";
    }
    paginaAnterior();
    proximaPagina();
    paginaEvent();
  }
  setCurrentPage(page: number) {
    if (page < 1) return;
    if (page > this.quantityPages) return;

    this.currentPage = page;
    this.getInformacoes();
  }

  adicionarInformacao() {
    alert("a");
  }
}
</script>

<style>
.dNone {
  display: none !important;
}

section.crud div.paginacao {
  padding: 1%;
  justify-content: center;
  align-items: center;
  border-bottom: 1px solid black;
}
section.crud div.paginacao h1 {
  padding: 1% 2%;
  margin-right: 1%;
  border-radius: 5px;
  font-weight: 400;
  text-transform: uppercase;
  cursor: pointer;
  background-color: var(--azul);
  transition: ease-in-out 0.2s;
}
section.crud div.paginacao h1.marcado {
  background-color: var(--azul_2);
  color: white;
}
section.crud div.paginacao h1:hover {
  background-color: var(--azul_3);
  color: white;
}

section.crud table td p.flexRow {
  flex-flow: row wrap;
  padding: 1%;
  align-items: center;
  justify-content: space-around;
}
section.crud table td p.flexRow i {
  width: calc(100% / 2);
  min-width: 50px;
  height: 100%;
  display: block;
  padding: 1%;
  font-size: 1.2vw;
  align-items: center;
  cursor: pointer;
  position: relative;
}

section.crud table td p.flexRow i > span {
  position: absolute;
  top: -40px;
  z-index: 2;
  display: none;

  background-color: #8d8b8b;

  border-radius: 5px;
  color: white;
  font-size: 0.9vw;
  padding: 4%;
}
section.crud table td p.flexRow i:hover > span {
  display: block;
}
section.crud div.table_wrapper {
  width: 100%;
  overflow: auto;
}
section.crud table {
  width: 100%;
  min-width: 800px;
  text-align: center;
  margin-top: 1%;
}
section.crud table tr.head {
  margin: 0;
  padding: 0;
  border: 0;
  outline: 1px solid black;
  background-color: var(--vermelho_2);
  color: white;
}
section.crud table tr {
  margin: 0;
  padding: 0;
  border: 0;
  background-color: var(--azul);
  color: black;
}
section.crud table tr.noItens {
  height: 100px;
  display: flex;
  align-items: center;
  width: 100%;
}
section.crud table tr.noItens h2 {
  width: 100%;
}
section.crud table tr:not(tr.head):hover {
  background-color: var(--azul_2);
  color: white;
}
section.crud table tr.head th:hover {
  background-color: var(--vermelho_1);
}
section.crud table th {
  cursor: pointer;
  padding: 1% 0;
  margin: 0;
  outline: 0;
  border: 0;
}
section.crud table td {
  padding: 1% 0%;
  user-select: none;
  width: 20px;
  overflow: auto;
}
section.crud table td input[type="checkbox"] {
  transform: scale(200%);
  user-select: none;
  display: grid;
  place-content: center;
  border: 0;
  outline: 0;
}

section.crud {
  height: 90%;
}
section.crud > div.container {
  width: 100%;
  height: 100%;
  padding: 0 1%;
  overflow: auto;
}
section.crud > div.container > h1 {
  font-weight: 900;
  text-transform: uppercase;
  padding: 1%;

  border-left: 1px solid black;
  border-bottom: 1px solid black;
}
section.crud div.buttons {
  border: 1px solid rgba(0, 0, 0, 0.199);
  border-radius: 10px;
  margin-top: 1%;
  padding: 1% 2%;
  display: flex;
  flex-flow: row wrap;
  align-items: center;
  justify-content: center;
}

section.crud div.buttons button {
  width: calc(100% / 3 - 2%);
  min-width: 300px;
  padding: 1%;
  font-size: 1vw;
  margin: 1%;
  border: 0;
  outline: 0;
  border-radius: 5px;
  text-transform: uppercase;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  flex-flow: row wrap;
  align-items: center;
  justify-content: space-around;
}
section.crud div.buttons button.adicionar {
  background-color: var(--azul_3);
  color: white;
}
section.crud div.buttons button.remover {
  background-color: var(--vermelho_2);
  color: white;
}
section.crud div.buttons button.selecionar {
  background-color: var(--branco);
  color: black;
  border: 1px solid black;
}
</style>
