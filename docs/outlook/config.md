# SSW WP Teams Integration - Outlook

## Passos para conexão:

- Crie o aplicativo na Azure admin center: 
[Doc Microsoft app registration](https://docs.microsoft.com/pt-br/azure/active-directory/develop/active-directory-v2-protocols#app-registration).

- adicione as informações na tela de configuração:
![Tela configuração](../assets/config-screen-outlook.jpg)

- Defina no aplicativo criado na Azure a url de callback:
![Tela autorização](../assets/define-callback-url.jpg)

- Clique em iniciar integração.

- Integração OK.
![Tela autorização ok](../assets/integracao-ok.jpg)

- Além da classe para uso, como solicitado para um cliente há um endpoint para criação e adição de usuários em um grupo.

- Defina o grupo em opções.
![Tela de opções](../assets/define-group.jpg)

- A partir daí podemos fazer as requisições pelos métodos enquanto fazemos as requisições de token de acesso automaticamente e estamos prontos para usar a classe <code>ssw_tint_wp</code>

## Métodos
[Documentação dos médotos](methods.md)
