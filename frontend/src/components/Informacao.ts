import AxiosInstance from "./axiosRequest";
export default class Informacao {
  public static async getInformacoesList(
    page: number | null,
    request: Array<string> | undefined,
    buscar: string | undefined
  ) {
    const resultado: any = [];
    if (page == undefined) page = 1;
    let query = "";

    if (request !== undefined && request.length > 0)
      query = "?" + request[0] + "=" + request[1];

    if (buscar == undefined || buscar.length > 0) {
      if (query.length > 0) query += "&buscar=" + buscar;
      else query += "?buscar=" + buscar;
    }

    let serverUrl = "";
    if (query.length > 0) serverUrl = "/informacao" + query + "&page=" + page;
    else serverUrl = "/informacao?page=" + page;

    await AxiosInstance.get(serverUrl)
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
  public static async deletarInformacao(id: number) {
    const resultado: any = [];
    await AxiosInstance.delete("/informacao/" + id)
      .then((result) => {
        resultado.push(true, result.data);
      })
      .catch((error) => {
        if (error.response !== undefined)
          resultado.push(false, error.response.data.erro);
        else
          resultado.push(
            false,
            "Não foi possível acessar o servidor no momento..."
          );
      });
    return resultado;
  }
  public static async buscarInformacao(id: number) {
    const resultado: any = [];
    await AxiosInstance.get("/informacao/" + id)
      .then((result) => {
        resultado.push(true, result.data);
      })
      .catch((error) => {
        if (error.response !== undefined)
          resultado.push(false, error.response.data.erro);
        else
          resultado.push(
            false,
            "Não foi possível acessar o servidor no momento..."
          );
      });
    return resultado;
  }
  public static async atualizarInformacao(id: number, novaInformacao: string) {
    const resultado: any = [];
    await AxiosInstance.put("/editarInformacao/" + id, {
      informacao: novaInformacao,
    })
      .then(() => {
        resultado.push(true);
      })
      .catch((error) => {
        if (error.response !== undefined)
          resultado.push(false, error.response.data.erro);
        else
          resultado.push(
            false,
            "Não foi possível acessar o servidor no momento..."
          );
      });
    return resultado;
  }
  public static async removerInformacoes(informacoes: Array<number>) {
    const resultado: any = [];

    await AxiosInstance.delete("/informacao", {
      data: {
        informacao: informacoes,
      },
    })
      .then(() => {
        resultado.push(true);
      })
      .catch((error) => {
        if (error.response !== undefined)
          resultado.push(false, error.response.data.erro);
        else
          resultado.push(
            false,
            "Não foi possível acessar o servidor no momento..."
          );
      });
    return resultado;
  }
  public static async inserirInformacao(informacao: string) {
    const resultado: any = []
    await AxiosInstance.post('/informacao', {
     'informacao': informacao
    }).then(response => {
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
