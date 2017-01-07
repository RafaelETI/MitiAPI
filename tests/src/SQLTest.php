<?php
use PHPUnit\Framework\TestCase;

class SQLTest extends TestCase
{
    private static $config;
    private static $sqlCategoria;
    private static $sqlMemoria;
    private static $sqlStatus;

    public static function setUpBeforeClass()
    {
        global $config;
        self::$config = $config;

        self::$sqlCategoria = new \Miti\SQL($config, 'categoria', 'c');
        self::$sqlMemoria = new \Miti\SQL($config, 'memoria', 'm');
        self::$sqlStatus = new \Miti\SQL($config, 'status', 's');
    }

    public function testBanco()
    {
        $banco = (new \Miti\SQL(self::$config, 'categoria', 'c'))->setBanco(new \Miti\Banco(self::$config))->getBanco();
        $this->assertSame(true, $banco instanceof \Miti\Banco);
    }

    public function testGetTipos()
    {
        $tipos = ['id' => 'float', 'nome' => 'string', 'status' => 'string'];
        $this->assertSame($tipos, self::$sqlCategoria->getTipos());
    }

    public function testGetAnulaveis()
    {
        $anulaveis = ['id' => false, 'nome' => false, 'status' => true];
        $this->assertSame($anulaveis, self::$sqlCategoria->getAnulaveis());
    }

    public function testGetTamanhos()
    {
        $tamanhos = ['id' => 3, 'nome' => 30, 'status' => 1];
        $this->assertSame($tamanhos, self::$sqlCategoria->getTamanhos());
    }

    public function testGetPk()
    {
        $this->assertSame('id', self::$sqlCategoria->getPk());
    }

    public function testValidarVazio()
    {
        $this->setExpectedException('UnexpectedValueException', "Valor vazio para o campo 'id'");
        self::$sqlCategoria->criar(['id' => '']);
    }

    public function testValidarExcessoDeCaracteres()
    {
        $mensagem = "Limite de caractéres excedido para o campo 'id'";
        $this->setExpectedException('UnexpectedValueException', $mensagem);

        self::$sqlCategoria->criar(['id' => 1000]);
    }

    public function testCriar()
    {
        self::$sqlCategoria->criar(['id' => 4, 'nome' => 'Teste', 'status' => 'c']);
        self::$sqlCategoria->criar(['id' => 5, 'nome' => 'Teste 2', 'status' => '']);
    }

    public function testAtualizar()
    {
        self::$sqlCategoria->filtrar('c', 'id', '=', '5')->atualizar(['status' => 'c']);
    }

    public function testDeletar()
    {
        self::$sqlCategoria->zerar();

        self::$sqlCategoria->filtrar('c', 'status', '=', 'c')->deletar();

        $quantidade = self::$sqlCategoria->selecionar('c', 'id')->ler()->quantificar();
        $this->assertSame(0, $quantidade);
    }

    public function testJuntar()
    {
        $m = self::$sqlMemoria
            ->selecionar('m', 'id')
            ->selecionar('s', 'descricao', 'des')
            ->juntar('categoria', 'c', 'm', 'categoria', 'c', 'id')
            ->juntar('status', 's', 'c', 'status', 's', 'id')
            ->filtrar('s', 'id', '=', 'a')
            ->ler()
            ->vetorizar()
        ;

        $this->assertSame(['id' => '1', 'des' => 'Ativo'], $m);
    }

    public function testJuntarEsquerda()
    {
        self::$sqlCategoria->zerar();

        $c = self::$sqlCategoria
            ->selecionar('c', 'nome')
            ->juntarEsquerda('status', 's', 'c', 'status', 's', 'id')
            ->filtrar('c', 'id', '=', '3')
            ->ler()
            ->vetorizar()
        ;

        $this->assertSame(['nome' => 'Pintura'], $c);
    }

    public function testJuntarDireita()
    {
        self::$sqlStatus->zerar();

        $s = self::$sqlStatus
            ->selecionar('c', 'nome', 'c_nome')
            ->juntarDireita('categoria', 'c', 's', 'id', 'c', 'status')
            ->filtrar('c', 'id', '=', '3')
            ->ler()
            ->vetorizar()
        ;

        $this->assertSame(['c_nome' => 'Pintura'], $s);
    }

    public function testEFiltrar()
    {
        self::$sqlMemoria->zerar();

        $m = self::$sqlMemoria
            ->selecionar('m', 'id')
            ->filtrar('m', 'categoria', '=', '1')
            ->eFiltrar('m', 'descricao', '=', 'Peaceful Warrior')
            ->ler()
            ->vetorizar()
        ;

        $this->assertSame(['id' => '1'], $m);
    }

    public function testOuFiltrar()
    {
        self::$sqlMemoria->zerar();

        $m = self::$sqlMemoria
            ->selecionar('m', 'id')
            ->filtrar('m', 'id', '=', '1')
            ->ouFiltrar('m', 'id', '=', '2')
            ->ler()
            ->quantificar()
        ;

        $this->assertSame(2, $m);
    }

    public function testTratarLeitura()
    {
        self::$sqlCategoria->zerar();
        $c = self::$sqlCategoria->selecionar('c', 'id')->filtrar('c', 'nome', 'like', 'ilm')->ler()->quantificar();
        $this->assertSame(1, $c);
    }

    public function testOrdenar()
    {
        self::$sqlMemoria->zerar();

        $m = self::$sqlMemoria
            ->selecionar('m', 'descricao')
            ->ordenar('m', 'categoria', 'asc')
            ->ordenar('m', 'descricao', 'desc')
            ->ler()
            ->vetorizar()
        ;

        $this->assertSame(['descricao' => 'The Village'], $m);
    }

    public function testOrdenarAleatoriamente()
    {
        self::$sqlMemoria->zerar();
        self::$sqlMemoria->selecionar('m', 'id')->ordenarAleatoriamente();

        $resultado = false;
        $controle = self::$sqlMemoria->ler()->vetorizar();

        for ($i = 1; $i <= 10; $i++) {
            $m = self::$sqlMemoria->ler()->vetorizar();

            if ($m['id'] != $controle['id']) {
                $resultado = true;
                break;
            }
        }

        $this->assertTrue($resultado);
    }

    public function testAgrupar()
    {
        self::$sqlMemoria->zerar();

        $m = self::$sqlMemoria
            ->selecionar('s', 'prioridade')
            ->juntar('categoria', 'c', 'c', 'id', 'm', 'categoria')
            ->juntar('status', 's', 's', 'id', 'c', 'status')
            ->agrupar('s', 'prioridade')
            ->ler()
            ->quantificar();

        $this->assertSame(1, $m);
    }

    public function testLimitarZero()
    {
        self::$sqlMemoria->zerar();
        $quantidade = self::$sqlMemoria->selecionar('m', 'id')->limitar(0)->ler()->quantificar();
        $this->assertSame(3, $quantidade);
    }

    public function testLimitar()
    {
        self::$sqlMemoria->zerar();
        $quantidade = self::$sqlMemoria->selecionar('m', 'id')->limitar(1, 2)->ler()->quantificar();
        $this->assertSame(1, $quantidade);
    }

    public function testZerar()
    {
        self::$sqlMemoria->zerar()->limitar(1)->zerar();
        $quantidade = self::$sqlMemoria->selecionar('m', 'id')->ler()->quantificar();
        $this->assertSame(3, $quantidade);
    }

    public static function tearDownAfterClass()
    {
        self::$sqlCategoria->getBanco()->rebobinar();
        self::$sqlMemoria->getBanco()->rebobinar();
        self::$sqlStatus->getBanco()->rebobinar();
    }
}
