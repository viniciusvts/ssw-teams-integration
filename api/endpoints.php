<?php
/**
 * url de callback
 * @author Vinicius de Santana
 */
function ssw_tint_callbackcode () {
  $SSWTI = new ssw_tint_wp();
  $url = SSW_TEAMSI_URLHOME;
  if($SSWTI->setCode($_GET['code'])){
    wp_redirect($url);
    exit;
  }else{
    wp_redirect($url);
    exit;
  }
}

/**
 * Endpoint para a webhook do rd
 * @author Vinicius de Santana
 */
function ssw_tint_rdwebhook (WP_REST_Request $request){
  $SSWTI = new ssw_tint_wp();
  $usersCreated = array();
  $usersNotCreated = array();
  $leads = $request->get_param('leads');
  foreach ($leads as $lead) {
    // create user
    $nameLowerCase = strtolower($lead['name']);
    $nameWithoutSpaces = str_replace(' ', '', $nameLowerCase);
    $userCreated = $SSWTI->createUser(true,
        $lead['name'], 
        null,
        $nameWithoutSpaces,
        $nameWithoutSpaces.'@ijbaead.onmicrosoft.com');
    // guarda o id
    if($userCreated->id) $usersCreated[] = $userCreated->id;
    else $usersNotCreated[] = $userCreated['email'];
  }
  $groupIdSelected = get_option(SSW_TEAMSI_GROUP);
  $groupResponse = $SSWTI->moveUserToGroup($usersCreated, $groupIdSelected);
  $data = array(
    'userIdsCreated' => $usersCreated,
    'groupId' => $groupIdSelected,
    'userIdsNotCreated' => $usersNotCreated,
  );

  $resp = new WP_REST_Response( $data );
  // Add a custom status code: $response->set_status( 201 );
  // Add a custom header: $response->header( 'Location', 'http://example.com/' );
  
  return $resp;
}
/**
 * Função registra os endpoints
 * @author Vinicius de Santana
 */
function SSW_TEAMSI_registerapi(){
    $sswuriapi = 'ssw-teamsi-integration/v1';
    // url de callback
    register_rest_route($sswuriapi,
      '/callback',
      array(
        'methods' => 'GET',
        'callback' => 'ssw_tint_callbackcode',
        'description' => 'recebe o code da integração e salva no banco',
      )
    );
    // endpoint para a webhook do rd
    register_rest_route($sswuriapi,
      '/rdwebhook',
      array(
        'methods' => 'POST',
        'callback' => 'ssw_tint_rdwebhook',
      )
    );
}
  
add_action('rest_api_init', 'SSW_TEAMSI_registerapi');
  