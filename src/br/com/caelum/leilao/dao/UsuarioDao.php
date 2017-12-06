<?php
namespace src\br\com\caelum\leilao\dao;

use PDO;
use src\br\com\caelum\leilao\dominio\Usuario;

class UsuarioDao
{
    private $con;

    public function __construct(PDO $con)
    {
        $this->con = $con;
    }

    public function porId(int $id) : Usuario
    {
        $stmt = $this->con->prepare('SELECT * FROM Usuario WHERE id = :id');
        $stmt->bindParam('id', $id);

        $funcionou = $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Usuario::class);
        $usuario = $stmt->fetch();

        return $usuario;
    }

    public function porNomeEEmail(string $nome, string $email)
    {
        $stmt = $this->con->prepare('SELECT * FROM Usuario WHERE nome = :nome AND email = :email');
        $stmt->bindParam('nome', $nome);
        $stmt->bindParam('email', $email);

        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, Usuario::class);
        $usuario = $stmt->fetch();

        return $usuario;
    }

    public function salvar(Usuario $usuario)
    {
        $nome = $usuario->getNome();
        $email = $usuario->getEmail();

        $stmt = $this->con->prepare('INSERT INTO Usuario (nome,email) VALUES(:nome,:email)');
        $stmt->bindParam('nome', $nome);
        $stmt->bindParam('email', $email);

        $stmt->execute();

        $usuario->setId($this->con->lastInsertId());
    }

    public function atualizar(Usuario $usuario)
    {
        $id = $usuario->getId();
        $email = $usuario->getEmail();
        $nome = $usuario->getNome();

        $stmt = $this->con->prepare("update Usuario set nome = :nome,email = :email where id = :id");
        $stmt->bindParam("nome", $nome);
        $stmt->bindParam("email", $email);
        $stmt->bindParam("id", $id);

        $stmt->execute();
    }

    public function deletar(Usuario $usuario)
    {
        $id = $usuario->getId();
        $stmt = $this->con->prepare("delete from Usuario where id = :id");
        $stmt->bindParam("id", $id);

        $stmt->execute();
    }
}
