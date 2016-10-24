<?php
/**
 * MitiLib, 2014 - 2016
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/miti-lib
 */
class Config{
    private $config = [];

    public function __construct(){
        session_start();
        $this->config()->erro()->sistema()->timezone()->charset()->banco()->idioma()->autoload();
    }

    private function config(){
        $this->config['ambiente'] = 1;
        $this->config['sistema'] = 'MitiLib';
        $this->config['versao'] = '1.1.41';
        $this->config['timezone'] = 'America/Sao_Paulo';
        $this->config['charset'] = 'UTF-8';
        $this->config['salt'] = '$1$mitiapim$';

        $this->config['banco']['charset'] = 'utf8';
        $this->config['banco'][0]['servidor'] = '';
        $this->config['banco'][0]['usuario'] = '';
        $this->config['banco'][0]['senha'] = '';
        $this->config['banco'][0]['nome'] = '';
        $this->config['banco'][1]['servidor'] = 'localhost';
        $this->config['banco'][1]['usuario'] = 'root';
        $this->config['banco'][1]['senha'] = 'root';
        $this->config['banco'][1]['nome'] = 'miti_api';

        $this->config['rest']['servidor'] = 'http://service.example.com/rest.php';
        $this->config['rest']['usuario'] = 'usuario';
        $this->config['rest']['senha'] = 'senha';

        return $this;
    }

    public function getConfig(){
        return $this->config;
    }

    private function erro(){
        error_reporting(-1);
        ini_set('display_errors', $this->config['ambiente']);
        return $this;
    }

    private function sistema(){
        if($this->config['ambiente']){$this->config['sistema'] .= '* '/*.$this->config['versao']*/;}
        return $this;
    }

    private function timezone(){
        date_default_timezone_set($this->config['timezone']);
        return $this;
    }

    private function charset(){
        if(!extension_loaded('mbstring')){throw new \RuntimeException('A classe '.__CLASS__.' depende da extensão mbstring.');}
        header('Content-Type: text/html; charset='.$this->config['charset']);
        mb_internal_encoding($this->config['charset']);
        return $this;
    }

    private function banco(){
        $this->config['banco']['servidor'] = $this->config['banco'][$this->config['ambiente']]['servidor'];
        $this->config['banco']['usuario'] = $this->config['banco'][$this->config['ambiente']]['usuario'];
        $this->config['banco']['senha'] = $this->config['banco'][$this->config['ambiente']]['senha'];
        $this->config['banco']['nome'] = $this->config['banco'][$this->config['ambiente']]['nome'];

        return $this;
    }

    private function idioma(){
        if(!isset($_SESSION['idioma'])){$_SESSION['idioma'] = '1';}

        if(isset($_GET['cfgIdioma'])){
            $_SESSION['idioma'] = $_GET['cfgIdioma'];
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit;
        }

        return $this;
    }

    private function autoload(){
        require '../vendor/autoload.php';
        return $this;
    }

    public static function trancar($sessao = 'usuario'){
        if(!isset($_SESSION[$sessao])){throw new \Exception('Você não tem permissão.');}
    }
}
