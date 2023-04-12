<h1>Projeto VueJs + Laravel</h1>
<p>Este repositório contém um projeto web dividido em duas partes: Backend Laravel e Front-End VueJS. O objetivo do
    projeto é permitir que os usuários possam criar uma conta, trocar seu nome e senha, e acessar um CRUD (Create, Read,
    Update, Delete) de dados.</p>
<h2>Tecnologias utilizadas</h2>
<ul>
    <li>Laravel 10</li>
    <li>VueJS 3</li>
    <li>Laravel Sanctum</li>
    <li>Axios</li>
    <li>TypeScript</li>
    <li>CSS</li>
    <li>FontAwesome</li>
</ul>
<h2>Configuração do ambiente</h2>
<p>Para executar o projeto localmente, siga os seguintes passos:</p>
<ol>
    <li>Clone o repositório: <code>git clone https://github.com/guzztavo2/crudLaravel-Vue.git</code></li>
    <li>Instale as dependências do Laravel: <code>composer install</code></li>
    <li>Copie o arquivo <code>.env.example</code> para <code>.env</code> e atualize as variáveis de ambiente, incluindo
        as informações do banco de dados.</li>
    <li>
        Ainda dentro do Laravel, no arquivo <code>config/cors.php</code> modifique as informações de URL para que
        funcione ao executar o Front-End.
    </li>
    <li>Execute as migrações do banco de dados: <code>php artisan migrate</code></li>
    <li>Instale as dependências do VueJS: <code>npm install</code></li>
    <li>Inicie o servidor de desenvolvimento: <code>php artisan serve</code> e <code>npm run dev</code></li>
</ol>
<h2>Uso do Laravel Sanctum</h2>
<p>Para utilizar o Laravel Sanctum e validar as requisições do CRUD, é necessário primeiro fazer login na aplicação.
    Para isso, envie uma requisição POST para a rota <code>/api/login</code> com as seguintes informações:</p>
<p>O token de autenticação será retornado no header da resposta.</p>
<p>Para acessar o CRUD, envie uma requisição GET para a rota <code>/api/crud</code> com o token de autenticação no
    header <code>Authorization</code>. Para criar, atualizar ou deletar dados, utilize as rotas correspondentes com os
    métodos POST, PUT e DELETE, respectivamente.</p>

<h2>No Laravel temos os seguintes roteamento</h2>
<table>
    <thead>
        <tr>
            <th>Endpoint</th>
            <th>Descrição</th>
            <th>URL</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>POST /login</td>
            <td>Realiza login do usuário na aplicação</td>
            <td>/login</td>
        </tr>
        <tr>
            <td>POST /registro</td>
            <td>Registra um novo usuário na aplicação</td>
            <td>/registro</td>
        </tr>
        <tr>
            <td>POST /verificarRegistroNomeUsuario</td>
            <td>Verifica se o nome de usuário já está sendo utilizado</td>
            <td>/verificarRegistroNomeUsuario</td>
        </tr>
        <tr>
            <td>GET /user</td>
            <td>Retorna as informações do usuário autenticado</td>
            <td>/user</td>
        </tr>
        <tr>
            <td>POST /logout</td>
            <td>Desloga o usuário da aplicação</td>
            <td>/logout</td>
        </tr>
        <tr>
            <td>POST /verificarSenha</td>
            <td>Verifica se a senha informada pelo usuário é válida</td>
            <td>/verificarSenha</td>
        </tr>
        <tr>
            <td>POST /trocarSenha</td>
            <td>Troca a senha do usuário autenticado</td>
            <td>/trocarSenha</td>
        </tr>
        <tr>
            <td>POST /trocarNomeUsuario</td>
            <td>Troca o nome de usuário do usuário autenticado</td>
            <td>/trocarNomeUsuario</td>
        </tr>
        <tr>
            <td>GET /informacao</td>
            <td>Retorna uma lista com todas as informações cadastradas</td>
            <td>/informacao</td>
        </tr>
        <tr>
            <td>GET /informacao/{id}</td>
            <td>Retorna a informação com o ID especificado</td>
            <td>/informacao/{id}</td>
        </tr>
        <tr>
            <td>DELETE /informacao/{id}</td>
            <td>Deleta a informação com o ID especificado</td>
            <td>/informacao/{id}</td>
        </tr>
        <tr>
            <td>DELETE /informacao</td>
            <td>Deleta todas as informações cadastradas</td>
            <td>/informacao</td>
        </tr>
        <tr>
            <td>POST /informacao</td>
            <td>Adiciona uma nova informação na aplicação</td>
            <td>/informacao</td>
        </tr>
        <tr>
            <td>PUT /editarInformacao/{id}</td>
            <td>Edita a informação com o ID especificado</td>
            <td>/editarInformacao/{id}</td>
        </tr>
    </tbody>
</table>

<h2>Já no VueJS temos o mesmo roteamento:</h2>
<p>Embora esteja usando rotas no VueJS, optei por não instalar o roteamento padrão do VueJS e criei minha própria
    implementação de roteamento utilizando eventos personalizados, como parte de meu processo de estudo e teste.</p>

<h2>Design responsivo para todas as telas</h2>
<p>Eu criei todo o design do site e utilizei o FontAwesome para torná-lo mais atraente, bem como o Colors.Co para harmonizar as cores. Além disso, utilizei CSS para garantir que o site seja totalmente responsivo e funcione em todas as telas.</p>
