<?php

declare(strict_types=1);

class UserModel extends Model
{

    private $data;

    function __construct()
    {
        $json = file_get_contents(DATA_JSON);  //he hecho una constante llamada DATA_JSON para facilitar trabajo, en ruta al arhivo json donde guardo los usuarios, utilizado una funcion para obtener su contenido guardandolo en la variable $json.
        $this->data =  json_decode($json, true); //  aqui lo que se hace es que el contenido cogido en JSON lo pasamos a un array asociativo para poder tratar los datos.
    }
    function verifyByEmail(string $email): bool
    {
        $users =  $this->getAllUsers();
        foreach ($users as $user) {
            if (isset($user['email']) && $user['email'] === $email) {
                return true;
            }
        }
        return false;
    }
    function createUsers(string $email, string $password, string $name)
    {
        if ($this->verifyByEmail($email)) {
            return false;
        }
            $newUser =  [
                'id' => uniqid(),
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ];
            $this->data['users'][] = $newUser;
            $this->saveUsers();
        return true;
    }
    function saveUsers() :bool
    {
        $json = json_encode($this->data, JSON_PRETTY_PRINT);
        return file_put_contents(DATA_JSON, $json) !== false;
    }
    function getAllUsers(): array
    {
        return $this->data['users'] ?? [];   //devolvemos todos los usuarios y si no hay un array vacio.
    }
    function getUsersById(string $id) : ?array
    {
        foreach ($this->data['users'] as $user) {
            if ($user['id'] === $id) {
                return $user;
            }
        }
        return null;
    }
    function updateUsers(string $email, string $newName, string $newPassword) : bool
    {
        foreach ($this->data['users'] as &$user) {
            if ($user['email'] === $email) {
                $user['name'] = $newName;
                $user['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
                return $this->saveUsers();
            }
        }
        return false;
    }
    function deleteUsers($id)
    {
        foreach ($this->data['users'] as $index => $user) {
            if ($user ['id'] === $id) {
                unset ($this->data['users'][$index]);
                $this->data['users'] = array_values($this->data['users']);
                return $this->saveUsers();
            }
            
        }
        return false;
    }

}
