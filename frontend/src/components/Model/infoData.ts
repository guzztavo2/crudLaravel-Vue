export default class infoData {
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
    public static dataToString(data: string|number) {
      const date = new Date(data);
      return date.toLocaleString("pt-BR", { timeZone: "America/Sao_Paulo" });
    }
    private setCriadoQuando(criadoQuando: string) {
      this.criadoQuando = infoData.dataToString(criadoQuando);
    }
    private setAtualizadoQuando(atualizadoQuando: string) {
      this.atualizadoQuando = infoData.dataToString(atualizadoQuando);
    }
    public static jsonToObject(item:any){
        console.log(item);
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