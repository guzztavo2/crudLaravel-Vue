<template>
  <section class="crud">
    <div class="container">
      <h1>Gerenciar Informações</h1>
      <div class="buttons">
        <button class="adicionar">Adicionar Informação <i class="fa-solid fa-plus"></i></button>
        <button class="selecionar" @click="alterarSelecao" v-if="selecaoAtivada == false">
         Selecionar itens <i class="fa-regular fa-square-check"></i>
        </button>
        <button class="remover" v-if="selecaoAtivada">
          Remover Informação <i class="fa-solid fa-minus"></i>
        </button>
        <button class="selecionar" @click="alterarSelecao" v-if="selecaoAtivada == true">
         Cancelar seleção <i class="fa-regular fa-square-check"></i>
        </button>
      </div>
      <div class="table_wrapper">
        <table v-if="selecaoAtivada === true" ref="table">
          <tr class="head">
            <th><p>Selecionar</p></th>
            <th><p>ID</p></th>
            <th><p>Informação</p></th>
            <th><p>Data de Criação</p></th>
            <th><p>Última atualização</p></th>
            <th><p>Quem atualizou</p></th>
          </tr>
          <tr>
            <td>
              <p><input type="checkbox" id="1" /></p>
            </td>
            <td><p>1</p></td>
            <td><p>Oláaaaaaa</p></td>
            <td><p>1</p></td>
            <td><p>1</p></td>
            <td><p>Guzztavo2</p></td>
          </tr>
        </table>
        <table v-if="selecaoAtivada === false" ref="table">
          <tr class="head">
            <th><p>ID</p></th>
            <th><p>Informação</p></th>
            <th><p>Data de Criação</p></th>
            <th><p>Última atualização</p></th>
            <th><p>Quem atualizou</p></th>
            <th><p>*</p></th>
          </tr>
          <tr>
            <td><p>1</p></td>
            <td><p>Oláaaaaaa</p></td>
            <td><p>1</p></td>
            <td><p>1</p></td>
            <td><p>Guzztavo2</p></td>
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
          </tr>
        </table>
      </div>
    </div>
  </section>
</template>

<script lang="ts">
import { Vue, Options } from "vue-class-component";
@Options({})
export default class crud extends Vue {
  selecaoAtivada = false;
  alterarSelecao(){
    if(this.selecaoAtivada)
    this.selecaoAtivada = false;
    else
    this.selecaoAtivada = true;
  }
}
</script>

<style>
section.crud table td p.flexRow {
  
  flex-flow: row wrap;
  padding:1%;
  align-items: center;
  justify-content: space-around;
}
section.crud table td p.flexRow i {
  width:calc(100% / 2);
  min-width:50px;
  font-size: 1.50vw;
  height:100%;
  display:block;
  padding:1%;
  font-size: 1.50vw;
  align-items: center;
  cursor:pointer;
  position: relative;
}

section.crud table td p.flexRow i > span{
position: absolute;
top:-40px;
z-index: 2;
display:none;
width:100%;
background-color:#8d8b8b;

border-radius: 5px;
color:white;
font-size: 0.90vw;
padding:4%;
}
section.crud table td p.flexRow i:hover > span{
  display:block;
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
  /* border-right: 1px solid black; */
  margin: 0;
  outline: 0;
  border: 0;
}
section.crud table td {
  padding: 1% 0%;
  user-select: none;
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
section.crud div.buttons button.adicionar{
background-color:var(--azul_3);
color:white;
}
section.crud div.buttons button.remover{
background-color:var(--vermelho_2);
color:white;

}
section.crud div.buttons button.selecionar{
background-color:var(--branco);
color:black;
border:1px solid black;
}
</style>
