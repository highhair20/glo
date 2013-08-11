<?php


class Glo_Controller_Action_Api_Auth extends Glo_Controller_Action_Api
{
    
    
    /**
     * signupAction
     *
     * End Point:
     * <pre style="border: 1px solid #3D578C; background: #E2E8F2">
     * /auth/signup
     * </pre>
     *
     * Sample Request:
     * <pre style="border: 1px solid #3D578C; background: #E2E8F2">
     * {
     *     "user_uuid": "e77a48ed-ff5a-4c12-9a59-5c48379d3160",
     *     "email": "johndoe@gmail.com",
     *     "password": "foomanchu",
     *     "first_name": "John",
     *     "last_name": "Doe",
     *     "phone": "3454346534",
     *     "timezone": "America/Los_Angeles",
     *     "opt_in": "1",
     *     "zip_code": "90802",
     *     "type": "chef",      (options: farm, chef)
     * }
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
            
            // create user
            $map = new App_Model_Map_User();
            $this->view->user_uuid = $map->save($createData);
            
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
     * loginAction
     * 
     * End Point:
     * <pre style="border: 1px solid #3D578C; background: #E2E8F2">
     * /auth/login
     * </pre>
     *
     * Sample Request:
     * <pre style="border: 1px solid #3D578C; background: #E2E8F2">
        {
            "email": "johndoe@gmail.com",
            "password": "foomanchu",
        }
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
            $auth = Glo_Auth::getInstance();
            $response = $auth->authenticate($data['email'], $data['password']);
            $identity = $response->getIdentity();
            $this->view->session_uuid = Zend_Session::getId();

            $map = new App_Model_Map_User();
            $user = $map->findByEmail($identity);
            $this->view->user_uuid = $user->user_uuid;

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
     * End Point:
     * <pre style="border: 1px solid #3D578C; background: #E2E8F2">
     * /auth/logout
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
            
            $this->_helper->json($this->view);
        }
        else
        {
            throw new Glo_Exception_BadData(array_shift(array_shift($form->getMessages())));
            
        }

    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    //////////////////////
    
     
    /**
     * changePasswordAction
     *
     * End Point:
     * <pre style="border: 1px solid #3D578C; background: #E2E8F2">
     * /auth/change-password
     * </pre>
     *
     * Sample Request:
     * <pre style="border: 1px solid #3D578C; background: #E2E8F2">
     * {
     *     "user_uuid": "e77a48ed-ff5a-4c12-9a59-5c48379d3160",
     *     "session_uuid": "361092b7-d0b8-406c-8409-41db2853baf2",
     *     "password_old": "oldpass",
     *     "password_new": "newpass"
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
    public function changePasswordAction()
    {
        $form = new App_Form_AuthChangePassword();
        if ($form->isValid($this->getRequestJson())) {
            App_Model_User::changePassword($form->getValues());

        } else {
            throw new Exception_BadData(array_shift(array_shift($form->getMessages())));
            
        }
        
        $this->render('common/json', NULL, true);
    }
*/


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

