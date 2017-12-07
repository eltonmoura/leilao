<?php
namespace src\br\com\caelum\leilao\dao;

use \PDO;
use \DateTime;
use \DateInterval;
use src\br\com\caelum\leilao\dominio\Leilao;
use src\br\com\caelum\leilao\dominio\Lance;
use src\br\com\caelum\leilao\dominio\Usuario;

class LeilaoDao
{
    private $con;

    public function __construct(PDO $con)
    {
        $this->con = $con;
    }

    public function salvar(Leilao $leilao)
    {
        $nome = $leilao->getDescricao();
        $valorInicial = $leilao->getValorInicial();

        $dono = $leilao->getDono();
        if (! empty($dono)) {
            $usuarioDao = new UsuarioDao($this->con);
            $usuarioDao->salvar($dono);
        }

        $donoId = $dono->getId();

        $dataAbertura = $leilao->getData()->format('Y-m-d H:i:s');
        $usado = $leilao->getUsado();
        $encerrado = $leilao->isEncerrado();

        $stmt = $this->con->prepare('
            INSERT INTO Leilao(nome, valorInicial, dono, dataAbertura, usado, encerrado)
            VALUES(:nome, :valorInicial, :dono, :dataAbertura, :usado, :encerrado)
        ');

        $stmt->bindParam('nome', $nome);
        $stmt->bindParam('valorInicial', $valorInicial);
        $stmt->bindParam('dono', $donoId);
        $stmt->bindParam('dataAbertura', $dataAbertura);
        $stmt->bindParam('usado', $usado, PDO::PARAM_BOOL);
        $stmt->bindParam('encerrado', $encerrado, PDO::PARAM_BOOL);

        if (! $stmt->execute()) {
            throw new \Exception('Erro ao salvar Leilao (' . $stmt->errorInfo()[2] . ')');
        }

        $leilao->setId($this->con->lastInsertId());

        foreach ($leilao->getLances() as $lance) {
            $this->salvarLance($lance);
        }
    }

    private function salvarLance(Lance $lance)
    {
        $usuario = $lance->getUsuario();
        if (! empty($usuario)) {
            $usuarioDao = new UsuarioDao($this->con);
            $usuarioDao->salvar($usuario);
        }

        $data = ($lance->getData() !== null) ? $lance->getData()->format('Y-m-d') : null;
        $valor = $lance->getValor();
        $leilaoId = ($lance->getLeilao() !== null) ? $lance->getLeilao()->getId() : null;

        $stmt = $this->con->prepare('
            INSERT INTO Lance(usuario,data,valor,leilao)
            VALUES(:usuario,:data,:valor,:leilao)
        ');

        $stmt->bindParam('usuario', $usuario->getId());
        $stmt->bindParam('data', $data);
        $stmt->bindParam('valor', $valor);
        $stmt->bindParam('leilao', $leilaoId);

        $stmt->execute();

        if (! $stmt->execute()) {
            throw new \Exception('Erro ao salvar Lance (' . json_encode($stmt->errorInfo()) . ')');
        }
    }

    public function porId(int $id)
    {
        $stmt = $this->con->prepare('SELECT * FROM Leilao WHERE id = :id');
        $stmt->bindParam('id', $id);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Usuario::class);
        $leilao = $stmt->fetch();

        return $leilao;
    }

    public function novos() : array
    {
        $usado = false;

        $stmt = $this->con->prepare('SELECT * FROM Leilao WHERE usado = :usado');
        $stmt->bindParam('usado', $usado);
        $stmt->execute();

        $novos = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Leilao::class);

        return $novos;
    }

    public function antigos() : array
    {
        $seteDiasAtras = new DateTime();
        $seteDiasAtras->sub(new DateInterval('P7D'));
        $seteDiasAtras = $seteDiasAtras->format('Y-m-d');

        $stmt = $this->con->prepare('SELECT * fROm Leilao WHERE dataAbertura <= :dataAbertura');
        $stmt->bindParam('dataAbertura', $seteDiasAtras);
        $stmt->execute();

        $antigos  = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Leilao::class);

        return $antigos;
    }

    public function porPeriodo(DateTime $inicio, DateTime $fim) : array
    {
        $inicio = $inicio->format('Y-m-d H:i:s');
        $fim = $fim->format('Y-m-d H:i:s');

        $stmt = $this->con->prepare('
            SELECT * FROM Leilao
            WHERE dataAbertura
            BETWEEN :inicio AND :fim 
            AND encerrado = false
        ');

        $stmt->bindParam('inicio', $inicio);
        $stmt->bindParam('fim', $fim);

        $stmt->execute();

        $porPeriodo = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Leilao::class);

        return $porPeriodo;
    }

    public function disputadosEntre(float $inicio, float $fim): array
    {
        $stmt = $this->con->prepare('
            SELECT le.*,COUNT(la.id) as lances
            FROM Leilao as le
                JOIN Lance as la ON le.id = la.leilao
            WHERE
                le.valorInicial BETWEEN :inicio AND :fim
                AND le.encerrado = false
            GROUP BY le.id
            HAVING lances >= 3
        ');

        $stmt->bindParam('inicio', $inicio);
        $stmt->bindParam('fim', $fim);
        $stmt->execute();

        $disputados = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Leilao::class);

        return $disputados;
    }

    public function total()
    {
        $encerrado = false;

        $stmt = $this->con->prepare('SELECT COUNT(*) FROM Leilao WHERE encerrado = :encerrado');
        $stmt->bindParam('encerrado', $encerrado);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function atualiza(Leilao $leilao)
    {
        $nome = $leilao->getNome();
        $valorInicial = $leilao->getValorInicial();
        $donoId = $leilao->getDono()->getId();
        $dataAbertura = $leilao->getDataAbertura()->format('Y-m-d H:i:s');
        $usado = $leilao->getUsado();
        $encerrado = $leilao->isEncerrado();
        $id = $leilao->getId();

        $stmt = $this->con->prepare('
            UPDATE Leilao
            SET nome = :nome,valorInicial = :valorInicial,dono = :dono,usado = :usado,encerrado = :encerrado
            where id = :id
        ');

        $stmt->bindParam('nome', $nome);
        $stmt->bindParam('valorInicial', $valorInicial);
        $stmt->bindParam('dono', $donoId);
        $stmt->bindParam('dataAbertura', $dataAbertura);
        $stmt->bindParam('usado', $usado);
        $stmt->bindParam('encerrado', $encerrado);
        $stmt->bindParam('id', $id);

        $stmt->execute();

        if (! $stmt->execute()) {
            throw new \Exception('Erro ao atualizar Leilao (' . $stmt->errorInfo()[2] . ')');
        }
    }

    public function deletar(Leilao $leilao)
    {
        $id = $leilao->getId();

        $stmt = $this->con->prepare('DELETE FROM Leilao WHERE id = :id');
        $stmt->bindParam('id', $id);

        $stmt->execute();

        if (! $stmt->execute()) {
            throw new \Exception('Erro ao remover Leilao (' . $stmt->errorInfo()[2] . ')');
        }
    }

    public function deletaEncerrados()
    {
        $stmt = $this->con->prepare('DELETE FROM Leilao WHERE encerrado = true');
        $stmt->execute();

        if (! $stmt->execute()) {
            throw new \Exception('Erro ao remover Leilao (' . $stmt->errorInfo()[2] . ')');
        }
    }

    public function listaLeiloesDoUsuario(Usuario $usuario)
    {
        $usuarioId = $usuario->getId();

        $stmt = $this->con->prepare('
            SELECT le.*
            FROM Leilao AS le 
                JOIN Lance la ON la.leilao = le.id 
            JOIN Usuario u ON la.usuario = :usuario
        ');

        $stmt->bindParam('usuario', $usuarioId);
        $stmt->execute();

        $doUsuario = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Leilao::class);

        return $doUsuario;
    }

    public function getValorInicialMedioDoUsuario(Usuario $usuario)
    {
        $usuarioId = $usuario->getId();
        $stmt = $this->con->prepare('
            SELECT AVG(le.valorInicial)
            FROM Leilao as le 
                JOIN Lance la ON la.leilao = le.id 
                JOIN Usuario u ON la.usuario = :usuario
            ');

        $stmt->bindParam('usuario', $usuarioId);
        $stmt->execute();

        $valor = $stmt->fetchColumn();

        return $valor;
    }
}
