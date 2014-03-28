<?php


class Glo_Controller_Action_Api_Auth extends Glo_Controller_Action_Api
{
    
    
    /**
     * signupAction
     *
     * Request method: POST
     *
     * End Point: /auth/signup
     *
     * Parameters:
     * - name
     * - email
     * - email_confirm
     * - password
     * - gender (optional)
     * - birthday (optional)
     * - vanity_url (optional)
     *
     * Sample Request:
     * <pre style="border: 1px solid #3D578C; background: #E2E8F2">
     * /auth/signup (data is in the POST)
     * </pre>
     *
     * Sample Response:
     * <pre style="border: 1px solid #3D578C; background: #E2E8F2">
     * {
     *     "session_uuid":"361092b7-d0b8-406c-8409-41db2853baf2"
     * }
     * </pre>
     *
     * @return void
     */
    public function signupAction()
    {
        $form = new App_Form_Auth_Signup();
        $jsonData = $this->getRequestJson();
        if (!isset($jsonData['timezone']))
        {
            $jsonData['timezone'] = 'America/Los_Angeles';
        }
        if ($form->isValid($jsonData)) {
            $createData = $form->getValues();           
            
            try
            {
                // create user
                $map = new App_Model_Map_User();
                $this->view->user_uuid = $map->save($createData);
            }
            catch (Glo_Exception_DuplicateData $e)
            {
                throw new Glo_Exception_DuplicateData('This email address is already in use.');
            }
                                
            // authenticate
            $auth = Glo_Auth::getInstance();
            $response = $auth->authenticate($createData['email'], $createData['password']);
            $identity = $response->getIdentity();
            $this->view->session_uuid = Zend_Session::getId();
            $this->_helper->json($this->view); 
             
        } else {
            throw new Glo_Exception_BadData(array_shift(array_shift($form->getMessages())));
             
        }
        
    }
    
    
    /**
     * finalizeAction
     *
     * Request method: POST
     *
     * End Point: /auth/finalize
     *
     * Parameters:
     * - name
     * - email
     * - email_confirm
     * - password
     * - gender (optional)
     * - birthday (optional)
     * - vanity_url (optional)
     *
     * Sample Request:
     * <pre style="border: 1px solid #3D578C; background: #E2E8F2">
     * /auth/signup (data is in the POST)
     * </pre>
     *
     * Sample Response:
     * <pre style="border: 1px solid #3D578C; background: #E2E8F2">
     * {
     *     "session_uuid":"361092b7-d0b8-406c-8409-41db2853baf2"
     * }
     * </pre>
     *
     * @return void
     */
    public function finalizeAction()
    {
        $form = new App_Form_Auth_Finalize();
        $jsonData = $this->getRequestJson();
        if (!isset($jsonData['timezone']))
        {
            $jsonData['timezone'] = 'America/Los_Angeles';
        }
        if ($form->isValid($jsonData)) {
            $data = $form->getValues();
            // mark the user as complete
            $data['status'] = App_Model_DbTable_User::STATUS_ACTIVE;
            
            $map = new App_Model_Map_User();
            $map->save($data);
                    
            // get user
            $user = $map->fetch(array(
                'user_uuid' => $data['user_uuid']
            ));
            
            // save user help
            $map = new App_Model_Map_UserHelp();
            $helpData = array(
                'user_uuid'         => $user->user_uuid,
                'my_folders_help'   => 1
            );
            $map->updateByUser($helpData);
            
            //
            $map = new App_Model_Map_UserAction();
            $map->save(array(
                'user_uuid'     => $data['user_uuid'],
                'action'        => 'signup finalize'
            ));
            
            // send welcome email
            $endPoint = "https://mandrillapp.com/api/1.0/messages/send-template.json";
            $payload = array(
                "key"           => "A-BU53l4lMh7yMNLqf8LUA",
                "template_name" => "Registration",
                "template_content"  => array(

                ),
                "message"       => array(
                    "from_email"    => "hello@liv360.com",
                    "from_name"     => "Liv360",
                    "to"            => array(
                        array(
                            "email" => $user->email,
                        )
                    ),
                    "merge_vars"    => array(
                        array(
                            "rcpt" => $user->email,
                            "vars" => array(
                                array(
                                    "name" => "EMAIL",
                                    "content" => $user->email
                                ),
                                array(
                                    "name" => "DISPLAY_NAME",
                                    "content" => $user->name
                                )
                            )
                        )
                    )
                )
            );

            $ch = curl_init($endPoint);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            $response = curl_exec($ch);
            curl_close($ch);
            
            $this->view = $user;
            $this->_helper->json($this->view); 
            
        } else {
            throw new Glo_Exception_BadData(array_shift(array_shift($form->getMessages())));
            
        }
    }
    
    
    /**
     * loginAction
     * 
     * Request method: POST
     *
     * End Point: /auth/login
     *
     * Parameters:
     * - email
     * - password
     *
     * Sample Request:
     * <pre style="border: 1px solid #3D578C; background: #E2E8F2">
     * /auth/login (data is in the POST)
     * </pre>
     *
     * Sample Response:
     * <pre style="border: 1px solid #3D578C; background: #E2E8F2">
        {
            "user_uuid": "e77a48ed-ff5a-4c12-9a59-5c48379d3160",
            "session_uuid": "361092b7-d0b8-406c-8409-41db2853baf2"
        }
     * </pre>
     *
     * @return void
     */
    public function loginAction()
    {
        $form = new App_Form_Auth_Login();
        $jsonData = $this->getRequestJson();
        if ($form->isValid($jsonData)) {
            $data = $form->getValues();
            
            // authenticate
            try
            {
                $auth = Glo_Auth::getInstance();
                $response = $auth->authenticate($data['email'], $data['password']);
                $identity = $response->getIdentity();
                $this->view->session_uuid = Zend_Session::getId();
            }
            catch (Glo_Auth_Exception_Failed $e)
            {
                throw new Glo_Auth_Exception_Failed('Incorrect email/password combination.');
            }

            $map = new App_Model_Map_User();
            $user = $map->findByEmail($identity);
            $this->view->user_uuid = $user->user_uuid;

            //
            $map = new App_Model_Map_UserAction();
            $map->save(array(
                'user_uuid'     => $this->view->user_uuid,
                'action'        => 'login'
            ));

            $this->_helper->json($this->view); 
        }
        else
        {
            throw new Glo_Exception_BadData(array_shift(array_shift($form->getMessages())));
        }
        
    }
    
    

    /**
     * logoutAction
     * 
     * Request method: POST
     *
     * End Point: /auth/logout
     *
     * Parameters:
     * - user_uuid
     *
     * Sample Request:
     * <pre style="border: 1px solid #3D578C; background: #E2E8F2">
     * /auth/logout (data is in the POST)
     * </pre>
     *
     * Sample Response:
     * <pre style="border: 1px solid #3D578C; background: #E2E8F2">
     * {}
     * </pre>
     *
     * @return void
     */
    public function logoutAction()
    {
        $form = new App_Form_Auth_Logout();
        if ($form->isValid($this->getRequestJson())) {
            $data = $form->getValues();
            
/*
            $auth = Glo_Auth::getInstance();
            $response = $auth->clearIdentity();
            var_dump($response);
*/
            Zend_Session::destroy();
            unset($_COOKIE['app']);
            
            $this->view->success = true;
            $this->_helper->json($this->view); 
        }
        else
        {
            throw new Glo_Exception_BadData(array_shift(array_shift($form->getMessages())));
            
        }

    }
    
    
    /**
     * tokenLoginAction
     * 
     * Request method: POST
     *
     * End Point: /auth/token-login
     *
     * Parameters:
     * - vanity_url
     * - security_code
     *
     * Sample Request:
     * <pre style="border: 1px solid #3D578C; background: #E2E8F2">
     * /auth/token-login (data is in the POST)
     * </pre>
     *
     * Sample Response:
     * <pre style="border: 1px solid #3D578C; background: #E2E8F2">
        {
            "user_uuid": "e77a48ed-ff5a-4c12-9a59-5c48379d3160",
            "session_uuid": "361092b7-d0b8-406c-8409-41db2853baf2"
        }
     * </pre>
     *
     * @return void
     */
    public function tokenLoginAction()
    {
        $form = new App_Form_Auth_TokenLogin();
        $jsonData = $this->getRequestJson();
        if ($form->isValid($jsonData)) {
            $data = $form->getValues();
            
            // get the user
            $map = new App_Model_Map_User();
            $user = $map->fetchByVanityUrl($data['vanity_url']);

            // validate the security code
            if ($data['security_code'] == App_Model_DbTable_User::getSecurityToken($user->user_uuid))
            {
                // authenticate
                $auth = Glo_Auth::getInstance();
                $auth->forceAuthenticate($user->user_uuid);
                $this->view->user_uuid = $user->user_uuid;
                $this->view->session_uuid = Zend_Session::getId();
    
                //
                $map = new App_Model_Map_UserAction();
                $map->save(array(
                    'user_uuid'     => $this->view->user_uuid,
                    'action'        => 'token login'
                ));
    
                $this->_helper->json($this->view); 
            }
            else
            {
                throw new Glo_Auth_Exception_Failed('Incorrect security token provided.');
            }

        }
        else
        {
            throw new Glo_Exception_BadData(array_shift(array_shift($form->getMessages())));
        }
        
    }
        
    
    /**
     * changePasswordAction
     *
     * Request method: POST
     *
     * End Point: /auth/change-password
     *
     * Parameters:
     * - user_uuid "e77a48ed-ff5a-4c12-9a59-5c48379d3160"
     * - session_uuid "361092b7-d0b8-406c-8409-41db2853baf2"
     * - password_old "oldpass"
     * - password_new "newpass"
     * - password_confirm "newpass"
     *
     * Sample Request:
     * <pre style="border: 1px solid #3D578C; background: #E2E8F2">
     * /auth/change-password (data is in the POST)
     * </pre>
     *
     * Sample Response:
     * <pre style="border: 1px solid #3D578C; background: #E2E8F2">
     * {}
     * </pre>
     *
     * @return void
     */
    public function changePasswordAction()
    {
        $form = new App_Form_Auth_ChangePassword();
        if ($form->isValid($this->getRequestJson())) {
            $data = $form->getValues();
            $data['password'] = $data['password_new'];
            $map = new App_Model_Map_User();
            $user = $map->save($data);

        } else {
            throw new Glo_Exception_BadData(array_shift(array_shift($form->getMessages())));
            
        }
        
        $this->_helper->json($this->view); 
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    //////////////////////
    
     



    /**
     * deleteAction
     *
     * Marks a user as deleted (status 2).
     *
     * End Point:
     * <pre style="border: 1px solid #3D578C; background: #E2E8F2">
     * /auth/delete
     * </pre>
     *
     * Sample Request:
     * <pre style="border: 1px solid #3D578C; background: #E2E8F2">
     * {
     *     "user_uuid":"e77a48ed-ff5a-4c12-9a59-5c48379d3160",
     *     "session_uuid":"361092b7-d0b8-406c-8409-41db2853baf2"
     * }
     * </pre>
     *
     * Sample Response:
     * <pre style="border: 1px solid #3D578C; background: #E2E8F2">
     * {}
     * </pre>
     *
     * @return void
     */
/*
    public function deleteAction()
    {
        $form = new App_Form_AuthDelete();
        if ($form->isValid($this->getRequestJson())) {
            $data = $form->getValues();
            App_Model_User::markAsDeleted($data['user_uuid']);
            
        }
        else
        {
            throw new Exception_BadData(array_shift(array_shift($form->getMessages())));
            
        }

        $this->render('common/json', NULL, true);
    }
*/


    
     
    /**
     * resetPasswordAction
     *
     * End Point:
     * <pre style="border: 1px solid #3D578C; background: #E2E8F2">
     * /auth/reset-password
     * </pre>
     *
     * Accepts an email address.  Generates a new password for the user and
     * emails the new password to the user.
     *
     * Sample Request:
     * <pre style="border: 1px solid #3D578C; background: #E2E8F2">
     * {
     *     "email":"johndoe@gmail.com"
     * }
     * </pre>
     *
     * Sample Response:
     * <pre style="border: 1px solid #3D578C; background: #E2E8F2">
     * {}
     * </pre>
     *
     * @return void
     */
/*
    public function resetPasswordAction()
    {
        $form = new App_Form_AuthResetPassword();
        if ($form->isValid($this->getRequestJson())) {
            $data = $form->getValues();
            // get the user just to make sure it exists
            $user = App_Model_User::fetchByEmail($data['email']);
            // send the email
            Email_ResetPassword::send($user);
        }
        else
        {
            throw new Exception_BadData(array_shift(array_shift($form->getMessages())));
            
        }

        $this->render('common/json', NULL, true);
    }
*/
    
    
    /**
     * finalizeResetPasswordAction
     *
     * @return void
     */
/*
    public function finalizeResetPasswordAction()
    {
        $form = new App_Form_AuthFinalizeResetPassword();
        if ($form->isValid($this->getRequestJson())) {
            $data = $form->getValues();
            $tokenData = Token_Manager::fetch($data['token_uuid']);
            // validate the token and 
            // validate that this is the same user that requested the token
            if ($tokenData && strtolower($data['email']) == strtolower($tokenData['email']))
            {
                // reset the password
                App_Model_User::resetPassword($data['email'], $data['password']);
            }
            else
            {
                throw new Exception_InvalidRequest("We were unable to process your request.");
            }
        }
        else
        {
            throw new Exception_BadData(array_shift(array_shift($form->getMessages())));
            
        }

        $this->render('common/json', NULL, true);
    }
*/
    
}

