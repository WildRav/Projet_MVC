<?php

class User extends BaseSql
{

    protected $id;
    protected $email;
    protected $lastname;
    protected $firstname;
    protected $pwd;
    protected $status;
    protected $permission;

    public function __construct(
        $id = -1,
        $email = null,
        $lastname = null,
        $firstname = null,
        $pwd = null,
        $status = 0,
        $permission = 0
    ) {
        parent::__construct();
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setEmail($email)
    {
        $email = trim($email);

        $this->email = $email;
    }

    public function setLastname($lastname)
    {
        $lastname = trim($lastname);

        $this->lastname = $lastname;
    }

    public function setFirstname($firstname)
    {
        $firstname = trim($firstname);

        $this->firstname = $firstname;
    }

    public function setPwd($pwd)
    {
        $pwd = password_hash($pwd, PASSWORD_DEFAULT);

        $this->pwd = $pwd;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setPermission($permission)
    {
        $this->permission = $permission;
    }

    public function getForm()
    {
        return [
            'struct' => [
                'method' => 'post',
                'action' => 'user/add',
                'class' => 'form-group',
                'submit' => 'Texte'
            ],
            'data' => [
                'email' => [
                    'type' => 'email',
                    'placeholder' => 'test@email.com',
                    'label' => 'Votre email',
                    'required' => 1
                ],
                'firstname' => [
                    'type' => 'text',
                    'placeholder' => 'Prenom',
                    'label' => 'Votre prénom',
                    'required' => 1
                ],
                'pwd' => [
                    'type' => 'password',
                    'placeholder' => '********',
                    'label' => 'Password',
                    'required' => 1
                ]
            ]
        ];
    }

}
