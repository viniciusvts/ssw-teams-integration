# Classe ssw_tint_wp

## Instancie
A instância já carrega os códigos de autenticação


    $SSWTI = new ssw_tint_wp();

## Você pode adicionar os códigos pela instância da classe
O client_secret

    $SSWTI->setClientSecret('value');

O client_id

    $SSWTI->setClientId('value');

O code

    $SSWTI->setCode('value');

## Pegar as propriedades
O client_secret

    $SSWTI->getClientSecret();

O client_id

    $SSWTI->getClientId();

O code

    $SSWTI->getCode();

## Verificar se as propriedades existem
O client_secret

    $SSWTI->hasClientSecret();

O client_id

    $SSWTI->hasClientId();

O code

    $SSWTI->hasCode();

O access_token

    $SSWTI->hasAcessToken();

O refresh_token

    $SSWTI->hasRefreshToken();

## Criar usuário
Cria usuário na api

    $SSWTI->createUser($accountEnabled, $displayName, $onPremisesImmutableId, $mailNickname, $userPrincipalName);


## Coloca usuário no grupo
Move o/s usuario/s para um grupo especificado

    $SSWTI->moveUserToGroup($userId, $groupId);

## Recupera grupos
Recupera as informações dos grupos do teams

    $SSWTI->getGroups();

Enjoy!
