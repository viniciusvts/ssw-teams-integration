<?php
if( !class_exists('ssw_tint_wp') ){
    class ssw_tint_wp {
        // propriedades
        private $client_id = '';
        private $client_secret = '';
        private $code = '';
        private $access_token = '';
        private $refresh_token = '';
        
        public function __construct(){
            $this->client_id = get_option(SSW_TEAMSI_CLIENT_ID);
	        $this->client_secret = get_option(SSW_TEAMSI_CLIENTE_SECRET);
	        $this->code = get_option(SSW_TEAMSI_CODE);
	        $this->access_token = get_option(SSW_TEAMSI_ACCESS_TOKEN);
	        $this->refresh_token = get_option(SSW_TEAMSI_REFRESH_TOKEN);
        }
        
        // set properties
        public function setClientId($value){
            if (update_option(SSW_TEAMSI_CLIENT_ID, $value)){
                $this->client_id = $value;
                return true;
            }
            return false;
        }
        public function setClientSecret($value){
            if (update_option(SSW_TEAMSI_CLIENTE_SECRET, $value)){
                $this->client_secret = $value;
                return true;
            }
            return false;
        }
        public function setCode($value){
            if (update_option(SSW_TEAMSI_CODE, $value)){
                $this->code = $value;
                $this->setAccessToken('');
                $this->setRefreshToken('');
                return true;
            }
            return false;
        }
        private function setAccessToken($value){
            if (update_option(SSW_TEAMSI_ACCESS_TOKEN, $value)){
                $this->access_token = $value;
                return true;
            }
            return false;
        }
        private function setRefreshToken($value){
            if (update_option(SSW_TEAMSI_REFRESH_TOKEN, $value)){
                $this->refresh_token = $value;
                return true;
            }
            return false;
        }
        public function clearAll(){
            $this->setClientId('');
            $this->setClientSecret('');
            $this->setCode('');
            $this->setAccessToken('');
            $this->setRefreshToken('');
        }

        // get properties
        public function getClientId(){
            return $this->client_id;
        }
        public function getClientSecret(){
            return $this->client_secret;
        }
        public function getCode(){
            return $this->code;
        }

        // has properties
        public function hasClientId(){
            if($this->client_id) return true;
            return false;
        }
        public function hasClientSecret(){
            if($this->client_secret) return true;
            return false;
        }
        public function hasCode(){
            if($this->code) return true;
            return false;
        }
        public function hasAcessToken(){
            if($this->access_token) return true;
            return false;
        }
        public function hasRefreshToken(){
            if($this->refresh_token) return true;
            return false;
        }

        // funções de autenticação no outlook
        public function getAccessAndRefreshToken(){
            $url = 'https://login.microsoftonline.com/common/oauth2/v2.0/token';
            $payload = 'grant_type=authorization_code'.
                '&code='. $this->code.
                '&redirect_uri='. 'https://www.ijba.com.br/wp-json/ssw-teamsi-integration/v1/callback/'. //SSW_TEAMSI_URLCALLBACK.
                '&client_id='. $this->client_id.
                '&client_secret='. $this->client_secret.
                '&scope='. 'offline_access Directory.ReadWrite.All';
            $resp = $this->post($url, $payload);
            if(!isset($resp->access_token) || !isset($resp->refresh_token)){ return false; }
            if(isset($resp->access_token)){ $this->setAccessToken($resp->access_token); }
            if(isset($resp->refresh_token)){ $this->setRefreshToken($resp->refresh_token); }
            return true;
        }
        public function refreshToken(){
            //se não tem refresh token, então adquiri um
            if(!$this->hasRefreshToken()){
                return $this->getAccessAndRefreshToken();
            } else{
                // se tem refresh token, atualiza o token
                $url = 'https://login.microsoftonline.com/common/oauth2/v2.0/token';
                $payload = 'grant_type=refresh_token'.
                '&refresh_token='. $this->refresh_token.
                '&client_id='. $this->client_id.
                '&client_secret='. $this->client_secret.
                '&scope='. 'offline_access Directory.ReadWrite.All';
                $resp = $this->post($url, $payload);
                if(!$resp->access_token || !$resp->refresh_token){ return false; }
                if($resp->access_token){ $this->setAccessToken($resp->access_token); }
                if($resp->refresh_token){ $this->setRefreshToken($resp->refresh_token); }
                return true;
            }
            return false;
        }
        
        /**
         * Cria usuário na api
         * https://docs.microsoft.com/pt-br/graph/api/user-post-users?view=graph-rest-1.0&tabs=http
         * @param bool $accountEnabled - true se a conta estiver habilitada; caso contrário, false.
         * @param string displayName - Nome de exibição no catálogo de endereços do usuário.
         * @param string onPremisesImmutableId - Só precisa ser especificado ao criar uma nova conta de usuário se você está usando um domínio federado para propriedade userPrincipalName (UPN) do usuário.
         * @param string mailNickname - O alias de email do usuário.
         * @param string userPrincipalName - Nome UPN (usuario@contoso.com).
         */
        public function createUser($accountEnabled, $displayName, $onPremisesImmutableId, $mailNickname, $userPrincipalName){
            $url = 'https://graph.microsoft.com/v1.0/users';
            //payload
            $payload = new stdClass();
            if(isset($accountEnabled)){ $payload->accountEnabled = $accountEnabled; }
            if(isset($displayName)){ $payload->displayName = $displayName; }
            if(isset($mailNickname)){ $payload->mailNickname = $mailNickname; }
            if(isset($userPrincipalName)){ $payload->userPrincipalName = $userPrincipalName; }
            $payload->passwordProfile = new stdClass();
            $payload->passwordProfile->forceChangePasswordNextSignIn = true;
            $payload->passwordProfile->password = 'BemVindo910' ;
            $pload = json_encode($payload);
            //headers
            $headers = array(
                'Authorization' => 'Bearer '. $this->access_token
            );
            $resp = $this->post($url, $pload, $headers);
            if(isset($resp->error)){ 
                // se erro de autenticação
                // atualizo o header e tento novamente
                if($this->refreshToken()){
                    //headers
                    $headers = array(
                        'Authorization' => 'Bearer '. $this->access_token
                    );
                    //envia
                    $resp = $this->post($url, $pload, $headers);
                }
            }
            return $resp;
        }
        
        /**
         * Adiciona o/os usuário/s especificados para o grupo indicado.
         * Na doc da microsoft esse endpoint retorna vazio
         * @param array||string $userId - identificação do usuário
         * @param string groupId - identificação do grupo
         */
        public function moveUserToGroup($userId, $groupId){
            $url = 'https://graph.microsoft.com/v1.0/groups/'.$groupId;
            if(is_string($userId)){
                $userId = array($userId);
            }
            //payload
            $payload = new stdClass();
            $payload->{'members@odata.bind'} = array();
            foreach ($userId as $key => $value) {
                $payload->{'members@odata.bind'}[] = 'https://graph.microsoft.com/v1.0/directoryObjects/'.$value;
            }
            $pload = json_encode($payload);
            //headers
            $headers = array(
                'Authorization' => 'Bearer '. $this->access_token
            );
            $resp = $this->patch($url, $pload, $headers);
            if(isset($resp->error)){ 
                // se erro de autenticação
                // atualizo o header e tento novamente
                if($this->refreshToken()){
                    //headers
                    $headers = array(
                        'Authorization' => 'Bearer '. $this->access_token
                    );
                    //envia
                    $resp = $this->patch($url, $pload, $headers);
                }
            }
            return $resp;
        }

        /**
         * Recupera grupos do AD
         * @param array $args - argumentos que vão ser passados pela url
         */
        public function getGroups(){
            $url = 'https://graph.microsoft.com/v1.0/groups';
            //headers
            $headers = array(
                'Authorization' => 'Bearer '. $this->access_token
            );
            $resp = $this->get($url, $headers);
            if(isset($resp->value)){ return $resp; }
            else{ 
                // se não retornar resposta atualizo o token no servidor
                // atualizo o header e tento novamente
                if($this->refreshToken()){
                    //headers
                    $headers = array(
                        'Authorization' => 'Bearer '. $this->access_token
                    );
                    //envia
                    $resp = $this->get($url, $headers);
                    if(isset($resp->value) || isset($resp->error)){ return $resp; }
                }
            }
            return false;
        }

        //funções auxiliares
        private function post($url, $payload, $headers = []){
            $ch = curl_init($url);
            // Set the content type 
            $headersArray = array();
            $isPayloadJson = $this->isJson($payload);
            if ($isPayloadJson) $headersArray[] ='Content-Type:application/json';
            else $headersArray[] ='Content-Type:application/x-www-form-urlencoded';

            foreach ($headers as $key => $value) {
                $headersArray[] = $key.':'.$value;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headersArray);
            // payload
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            
            // Return response instead of outputting
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // Execute the POST request
            $result = curl_exec($ch);
            // Close cURL resource
            curl_close($ch);
            //return
            return json_decode($result);
        }
        /**
         * get
         */
        private function get($url, $headers = []){
            $ch = curl_init($url);
            // Set the content type to application/json
            $headersArray = array('Content-Type:application/json');
            foreach ($headers as $key => $value) {
                $headersArray[] = $key.':'.$value;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headersArray);
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            // Close cURL resource
            curl_close($ch);
            //return
            return json_decode($result);
        }
        /**
         * patch
         */
        private function patch($url, $payload, $headers = []){
            $ch = curl_init($url);
            // Set the content type 
            $headersArray = array();
            $isPayloadJson = $this->isJson($payload);
            if ($isPayloadJson) $headersArray[] ='Content-Type:application/json';
            else $headersArray[] ='Content-Type:application/x-www-form-urlencoded';

            foreach ($headers as $key => $value) {
                $headersArray[] = $key.':'.$value;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headersArray);
            // payload
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            // Return response instead of outputting
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            // Close cURL resource
            curl_close($ch);
            //return
            return json_decode($result);
        }

        function isJson($string) {
            return ((is_string($string) &&
                    (is_object(json_decode($string)) ||
                    is_array(json_decode($string))))) ? true : false;
        }
    }
}