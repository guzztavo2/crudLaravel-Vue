import AxiosInstance from "./axiosRequest";
export default class Informacao {
  public static async getInformacoesList(
    page: number | null,
    request: Array<string> | undefined
  ) {
    const resultado: any = [];
    if (page == undefined) page = 1;

    if (request !== undefined && request?.length > 0) {
      await AxiosInstance.get(
        "/informacao?"+ request[0] +"="+ request[1] + "&page=" +page
      )
        .then((result) => {
          resultado.push(true, result.data);
        })
        .catch((error) => {
          if (error.response !== undefined)
            resultado.push(false, error.response.data.errors);
          else
            resultado.push(
              false,
              "Não foi possível acessar o servidor no momento..."
            );
        });

      return resultado;
    }
    await AxiosInstance.get("/informacao?page=" + page)
      .then((result) => {
        resultado.push(true, result.data);
      })
      .catch((error) => {
        if (error.response !== undefined)
          resultado.push(false, error.response.data.errors);
        else
          resultado.push(
            false,
            "Não foi possível acessar o servidor no momento..."
          );
      });

    return resultado;
  }
  public static async deletarInformacao(id:number){
    const resultado:any = [];
    await AxiosInstance.delete('/informacao/' + id).then(result =>{
      resultado.push(true, result.data);
    }) .catch((error) => {
      if (error.response !== undefined)
        resultado.push(false, error.response.data.erro);
      else
        resultado.push(
          false,
          "Não foi possível acessar o servidor no momento..."
        );
    })
    return resultado;
  }
  public static async buscarInformacao(id:number){
    const resultado:any = [];
    await AxiosInstance.get('/informacao/' + id).then(result =>{
      resultado.push(true, result.data);
    }) .catch((error) => {
      if (error.response !== undefined)
        resultado.push(false, error.response.data.erro);
      else
        resultado.push(
          false,
          "Não foi possível acessar o servidor no momento..."
        );
    })
    return resultado;
  }
  public static async atualizarInformacao(id:number, novaInformacao:string){
    const resultado:any = [];
    await AxiosInstance.put('/editarInformacao/' + id, {'informacao':novaInformacao}).then(sucess => {
      resultado.push(true);
    }).catch(error => {
      if (error.response !== undefined)
      resultado.push(false, error.response.data.erro);
    else
      resultado.push(
        false,
        "Não foi possível acessar o servidor no momento..."
      );
    })
    return resultado;
  }
}
