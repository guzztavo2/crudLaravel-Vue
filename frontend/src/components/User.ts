import AxiosInstance from "./axiosRequest";

export default class User {
  static getLocalStorage() {
    const auth = localStorage.getItem("access_token");
    return auth;
  }
  static async getUserName() {
    let responseData;

    await AxiosInstance.get("/user").then((response) => {
      responseData = response.data.nomeUsuario;
    });
    return responseData;
  }
  static async verificarSenhaUsuario(senhaAntiga: string) {
    let responseData;
    //await this.csfrCookie();
    await AxiosInstance.post("/verificarSenha", {
      senhaUsuario: senhaAntiga,
    })
      .then(() => {
        responseData = true;
      })
      .catch((error) => {
        if (error.response !== undefined)
          responseData.push(false, error.response.data.errors);
        else
          responseData.push(
            false,
            "Não foi possível acessar o servidor no momento..."
          );
      });
    return responseData;
  }
  static async loginUser(nomeUsuario: string, senhaUsuario: string) {
    const responseData: any = [];
    await AxiosInstance.post("/login", {
      nomeUsuario: nomeUsuario,
      senhaUsuario: senhaUsuario,
    })
      .then((response) => {
        localStorage.setItem("access_token", response.data.access_token);
        responseData.push(true);
      })
      .catch((error) => {
        if (error.response !== undefined)
          responseData.push(false, error.response.data.errors);
        else
          responseData.push(
            false,
            "Não foi possível acessar o servidor no momento..."
          );
      });

    return responseData;
  }
  static async trocarSenha(
    senhaAntiga: string,
    novaSenha: string,
    repetirNovaSenha: string
  ) {
    const responseData: any = [];
    //'repetirNovaSenha':repetirNovaSenha
    await AxiosInstance.post("/trocarSenha", {
      senhaAntiga: senhaAntiga,
      novaSenha: novaSenha,
      repetirNovaSenha: repetirNovaSenha,
    })
      .then((result) => {
        responseData.push(true);
      })
      .catch((error) => {
        if (error.response !== undefined)
          responseData.push(false, error.response.data.errors);
        else
          responseData.push(
            false,
            "Não foi possível acessar o servidor no momento..."
          );
      });
    return responseData;
  }
  static async registrarUsuario(nomeUsuario: string, senhaUsuario: string) {
    const responseData: any = [];
    await AxiosInstance.post("/registro", {
      nomeUsuario: nomeUsuario,
      senhaUsuario: senhaUsuario,
    })
      .then(() => {
        responseData.push(true);
      })
      .catch((error) => {
        if (error.response !== undefined)
          responseData.push(false, error.response.data.errors);
        else
          responseData.push(
            false,
            "Não foi possível acessar o servidor no momento..."
          );
      });
    return responseData;
  }
  static async verificarUser() {
    let responseData;
    await AxiosInstance.get("/user")
      .then(() => {
        responseData = true;
      })
      .catch(() => {
        responseData = false;
      });

    return responseData;
  }

  static verificarLogado(): boolean {
    if (this.getLocalStorage()) {
      return true;
    }
    return false;
  }
  static removerLocalStorage() {
    localStorage.removeItem("access_token");
  }
  static verificarSenha(senha: string) {
    if (senha.length < 5) {
      return "Por favor, a senha exige ao menos 5 caracteres";
    } else if (senha.length > 50) {
      return "Por favor, a senha exige que tenha menos que 50 caracteres";
    }
    return "";
  }
  // static async logout(){

  // }
  static async verificarUsuario() {
    if (this.verificarLogado() == false) return null;

    let resultado: any;
    await User.verificarUser().then((result) => {
      resultado = result;
    });
    if (resultado == false) this.removerLocalStorage();

    return resultado;
  }
  static async logout() {
    await AxiosInstance.post("/logout");
    this.removerLocalStorage();
  }
  static async verificarNomeUsuario(nomeUsuario: string) {
    const resultado: any = [];
    await AxiosInstance.post("/verificarRegistroNomeUsuario", {
      nomeUsuario: nomeUsuario,
    })
      .then((response) => {
        resultado.push("true");
      })
      .catch((error) => {
        if (error.response !== undefined)
          resultado.push("false", error.response.data.errors[0]);
        else
          resultado.push(
            false,
            "Não foi possível acessar o servidor no momento..."
          );
      });

    return resultado;
  }
  static async trocarNomeUsuario(
    nomeUsuario: string,
    nomeUsuarioRepetir: string
  ) {
    const resultado: any = [];

    await AxiosInstance.post("/trocarNomeUsuario", {
      nomeUsuario: nomeUsuario,
      nomeUsuarioRepetir: nomeUsuarioRepetir,
    })
      .then((response) => {
        resultado.push("true");
      })
      .catch((error) => {
        if (error.response !== undefined)
          resultado.push("false", error.response.data.errors[0]);
        else
          resultado.push(
            false,
            "Não foi possível acessar o servidor no momento..."
          );
      });

    return resultado;
  }
}
