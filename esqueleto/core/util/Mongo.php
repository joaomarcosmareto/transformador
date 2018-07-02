<?php

namespace core\util;

// use PDO;
use Exception;
use MongoDB\Client;
use MongoDB\BSON\ObjectID;
use core\AppConfig;
use core\util\Logger;
use core\util\MongoException;
use core\dominio\Dominio;

class Mongo {

    public static $con;

    private function __construct() {}

    public static function getInstance(array $settings)
    {
        if(self::$con == null)
        {
            $dsn = "mongodb://";
            if($settings['auth'])
            {
                $dsn .= $settings['user'] . ":" . $settings['pass'] . "@".$settings['host'].":27017";
            }
            else
            {
                $dsn .= $settings['host'].":27017";
            }
            self::$con = new Client($dsn, [], ['typeMap' => ['root' => 'array', 'document' => 'array']]);
            self::$con = self::$con->selectDatabase($settings['base']);
        }
    }

    #region PRIVATE FUNCTIONS

        private static function onBeforeExecute(&$query, $curTime){
            $query = [];
            if(AppConfig::$LogarTempoExecucaoQuery)
            {
                $query["init"] = $curTime;
            }
        }
        private static function onAfterExecute(&$query, $curTime, $operation, $col, $array_criteria, $array_doc, $array_options){
            if(AppConfig::$LogarQuery)
            {
                if(AppConfig::$LogarTempoExecucaoQuery)
                    $query["stop"] = $curTime;

                $query["operation"] = $operation;
                $query["collection"] = $col;

                if($array_criteria !== null){
                    $array_criteria = UtilFunctions::ajustaArrayMongoPraJson($array_criteria);
                    $array_criteria = json_encode($array_criteria, JSON_UNESCAPED_UNICODE);
                }
                $query["criteria"] = $array_criteria;

                if($array_doc !== null){
                    $array_doc = UtilFunctions::ajustaArrayMongoPraJson($array_doc);
                    $array_doc = json_encode($array_doc, JSON_UNESCAPED_UNICODE);
                }
                $query["doc"] = $array_doc;
                $query["options"] = json_encode($array_options, JSON_UNESCAPED_UNICODE);

                Logger::addQuery($query);
            }
        }
        private static function onException($e, $query){
            $message = ($e instanceof MongoException)
                ? "#".$e->getMessage()
                : "#".json_encode($query, JSON_UNESCAPED_UNICODE)."#".$e->getMessage()."#";
            Logger::info($message);
        }
        private static function update($collection, $criteria, $doc, $options)
        {
            try{
                self::onBeforeExecute($query, UtilFunctions::getCurrentTimestamp());

                if(!isset($doc['$set']) && !isset($doc['$push']) && !isset($doc['$inc']))
                {
                    $return = $collection->replaceOne($criteria, $doc, $options);
                }
                else
                {
                    $return = $collection->updateMany($criteria, $doc, $options);
                }

                self::onAfterExecute(
                    $query,
                    UtilFunctions::getCurrentTimestamp(),
                    "update",
                    $collection->getCollectionName(),
                    $criteria,
                    $doc,
                    $options
                );

                if($return->isAcknowledged())
                {
                    return true;
                }
                throw new MongoException($query, $return);
            }
            catch(Exception $e){
                self::onException($e, $query);
                throw $e;
            }
        }
        private static function batchInsert($collection, $arrays_doc)
        {
            try{
                self::onBeforeExecute($query, UtilFunctions::getCurrentTimestamp());

                $return = $collection->insertMany($arrays_doc);

                self::onAfterExecute(
                    $query,
                    UtilFunctions::getCurrentTimestamp(),
                    "batchInsert",
                    $collection->getCollectionName(),
                    null,
                    $arrays_doc,
                    null
                );

                if($return->isAcknowledged())
                {
                    return true;
                }
                throw new MongoException($query, $return);
            }
            catch(Exception $e){
                self::onException($e, $query);
            }
            return false;
        }
        private static function remove($collection, $criteria, $options)
        {
            try{
                self::onBeforeExecute($query, UtilFunctions::getCurrentTimestamp());

                $return = $collection->deleteMany($criteria, $options);

                self::onAfterExecute(
                    $query,
                    UtilFunctions::getCurrentTimestamp(),
                    "remove",
                    $collection->getCollectionName(),
                    $criteria,
                    null,
                    $options
                );

                if($return->isAcknowledged())
                {
                    return;
                }
                throw new MongoException($query, $return);
            }
            catch(Exception $e){
                self::onException($e, $query);
            }
        }
        private static function findOne($collection, $criteria, $options = [])
        {
            $options = $options ?? [];
            try{
                self::onBeforeExecute($query, UtilFunctions::getCurrentTimestamp());

                $return = $collection->findOne($criteria, $options);

                self::onAfterExecute(
                    $query,
                    UtilFunctions::getCurrentTimestamp(),
                    "findOne",
                    $collection->getCollectionName(),
                    $criteria,
                    null,
                    $options
                );

                return $return;
            }
            catch(Exception $e){
                self::onException($e, $query);
            }
            return null;
        }
        private static function find($collection, $criteria, $options = [])
        {
            $options = $options ?? [];
            try{
                self::onBeforeExecute($query, UtilFunctions::getCurrentTimestamp());

                $return = $collection->find($criteria, $options);

                self::onAfterExecute(
                    $query,
                    UtilFunctions::getCurrentTimestamp(),
                    "find",
                    $collection->getCollectionName(),
                    $criteria,
                    null,
                    $options
                );

                return $return;
            }
            catch(Exception $e){
                self::onException($e, $query);
            }
            return null;
        }
        private static function count($collection, $criteria, $options)
        {
            try{

                self::onBeforeExecute($query, UtilFunctions::getCurrentTimestamp());

                $return = $collection->count($criteria, $options);

                self::onAfterExecute(
                    $query,
                    UtilFunctions::getCurrentTimestamp(),
                    "count",
                    $collection->getCollectionName(),
                    $criteria,
                    null,
                    $options
                );

                return $return;
            }
            catch(Exception $e){
                self::onException($e, $query);
            }
            return 0;
        }

    #endregion

    #region PUBLIC API

        public static function salvar(string $collectionName, array $dominio, array $criteria = null, array $options = null)
        {
            if($criteria == null)
                $criteria = array('_id' => $dominio['_id']);

            if($options == null)
                $options = array("upsert" => true, "multiple" => false);

            return Mongo::update(self::$con->{$collectionName}, $criteria, $dominio, $options);
        }//metodo SALVAR

        public static function atualizar(string $collectionName, array $criteria, array $set, array $options = array())
        {
            return Mongo::update(self::$con->{$collectionName}, $criteria, $set, $options);
        }

        public static function inserirEmLote(string $collectionName, array $array_dominio)
        {
            if(count($array_dominio) == 0) return true;

            $array_docs = [];
            foreach ($array_dominio as $dominio) {
                $array_docs[] = $dominio->doc;
            }

            return Mongo::batchInsert(self::$con->{$collectionName}, $array_docs);
        }//metodo batchInsert

        public static function deletar(string $collectionName, ObjectID $_id)
        {
            return Mongo::remove(self::$con->{$collectionName}, ['_id' => $_id], ['justOne' => true]);
        }//metodo DELETAR

        public static function deletarPorCriterio(string $collectionName, array $criteria)
        {
            return Mongo::remove(self::$con->{$collectionName}, $criteria, array());
        }//metodo deletarPorCriterio

        public static function retornaPorId(string $collectionName, ObjectID $_id, array $options = array())
        {
            return Mongo::findOne(self::$con->{$collectionName}, ['_id' => $_id], $options);
        }//metodo RETORNAPORID

        public static function retornaUm(
            string $collectionName,
            array $campos = null, array $filtros = null, array $comparadores = null, array $append = null)
        {
            $q = array();
            $count = count($campos);
            for($i = 0; $i < $count; $i++)
            {
                if(sizeof($comparadores))
                {
                    if($comparadores[$i] == "=")
                        $q[$campos[$i]] = $filtros[$i];
                    else{
                        if($comparadores[$i] == "<")
                            $q[$campos[$i]] = array('$lt' => $filtros[$i]);
                        else if($comparadores[$i] == ">")
                            $q[$campos[$i]] = array('$gt' => $filtros[$i]);
                        else if($comparadores[$i] == "<=")
                            $q[$campos[$i]] = array('$lte' => $filtros[$i]);
                        else if($comparadores[$i] == ">=")
                            $q[$campos[$i]] = array('$gte' => $filtros[$i]);
                        else if($comparadores[$i] == "like"){
                            $like_var = str_replace('%', '', $filtros[$i]);
                            $q[$campos[$i]] = new MongoRegex("/$like_var/i");
                        }
                        else if($comparadores[$i] == "= in"){
                            $q[$campos[$i]] = array('$in' => $filtros[$i]);
                        }
                        else if($comparadores[$i] == "!="){
                            $q[$campos[$i]] = array('$ne' => $filtros[$i]);
                        }
                    }
                }
                else
                    $q[$campos[$i]] = $filtros[$i];
            }
            return Mongo::findOne(self::$con->{$collectionName}, $q, $append);
        }//metodo RETORNAUM

        public static function retornaTodos(
            string $collectionName,
            array $campos = null, array $filtros = null, array $comparadores = null, array $append = null)
        {
            $r = array();

            $q = array();
            $count = count($campos);
            for($i = 0; $i < $count; $i++)
            {
                if(sizeof($comparadores))
                {
                    if($comparadores[$i] == "=")
                        $q[$campos[$i]] = $filtros[$i];
                    else{
                        if($comparadores[$i] == "<")
                            $q[$campos[$i]] = array('$lt' => $filtros[$i]);
                        else if($comparadores[$i] == ">")
                            $q[$campos[$i]] = array('$gt' => $filtros[$i]);
                        else if($comparadores[$i] == "<=")
                            $q[$campos[$i]] = array('$lte' => $filtros[$i]);
                        else if($comparadores[$i] == ">=")
                            $q[$campos[$i]] = array('$gte' => $filtros[$i]);
                        else if($comparadores[$i] == "like"){
                            $like_var = str_replace('%', '', $filtros[$i]);
                            // $q[$campos[$i]] = new MongoRegex("/$like_var/i");
                            $q[$campos[$i]] = array('$regex' => "$like_var", '$options'=>"i");
                        }
                        else if($comparadores[$i] == "= in"){
                            $q[$campos[$i]] = array('$in' => $filtros[$i]);
                        }
                        else if($comparadores[$i] == "!="){
                            $q[$campos[$i]] = array('$ne' => $filtros[$i]);
                        }
                    }
                }
                else
                    $q[$campos[$i]] = $filtros[$i];
            }

            $cursor = Mongo::find(self::$con->{$collectionName}, $q, $append);

            $it = new \IteratorIterator($cursor);
            $it->rewind(); // o rewind no cursor é obrigatório

            while($doc = $it->current()) {
                $r[] = $doc;
                $it->next();
            }

            return (empty($r)) ? array() : $r;
        }//metodo RETORNATODOS

        public static function retornaTodosAggregate(
            string $collectionName, string $className,
            array $campos = null, array $filtros = null, array $comparadores = null, array $append = null, array $pipeline)
        {
            $q = array();
            $count = count($campos);
            for($i = 0; $i < $count; $i++)
            {
                if(sizeof($comparadores))
                {
                    if($comparadores[$i] == "=")
                        $q[$campos[$i]] = $filtros[$i];
                    else{
                        if($comparadores[$i] == "<")
                            $q[$campos[$i]] = array('$lt' => $filtros[$i]);
                        else if($comparadores[$i] == ">")
                            $q[$campos[$i]] = array('$gt' => $filtros[$i]);
                        else if($comparadores[$i] == "<=")
                            $q[$campos[$i]] = array('$lte' => $filtros[$i]);
                        else if($comparadores[$i] == ">=")
                            $q[$campos[$i]] = array('$gte' => $filtros[$i]);
                        else if($comparadores[$i] == "like"){
                            if(substr_count($filtros[$i], '%') === 2){
                                $like_var = str_replace('%', '', $filtros[$i]);
                                $q[$campos[$i]] = array('$regex' => "$like_var", '$options'=>"i");
                            } elseif (substr_count($filtros[$i], '%') === 0) {
                                $like_var = str_replace('%', '', $filtros[$i]);
                                $q[$campos[$i]] = array('$regex' => "^$like_var$", '$options'=>"im");
                            } elseif (strpos($filtros[$i], "%") === 0) {
                                $like_var = str_replace('%', '', $filtros[$i]);
                                $q[$campos[$i]] = array('$regex' => "$like_var$", '$options'=>"im");
                            } else {
                                $like_var = str_replace('%', '', $filtros[$i]);
                                $q[$campos[$i]] = array('$regex' => "^$like_var", '$options'=>"im");
                            }
                        }
                        else if($comparadores[$i] == "= in"){
                            $q[$campos[$i]] = array('$in' => $filtros[$i]);
                        }
                        else if($comparadores[$i] == "!="){
                            $q[$campos[$i]] = array('$ne' => $filtros[$i]);
                        }
                    }
                }
                else
                    $q[$campos[$i]] = $filtros[$i];
            }

            $match = array(
                '$match' => $q
            );

            array_unshift($pipeline, $match);


            $result = self::$con->{$collectionName}->aggregate($pipeline);

            $r = array();

            $it = new \IteratorIterator($result);
            $it->rewind(); // o rewind no cursor é obrigatório

            $className = "core\dominio\\$className";

            while($doc = $it->current()) {
                $dominio = new $className;
                $dominio->doc = $doc;
                $r[] = $dominio;
                $it->next();
            }

            return $r;
        }//metodo RETORNATODOSAGGREGATE

        public static function retornaContagem(
            string $collectionName,
            array $campos = null, array $filtros = null, array $comparadores = null, array $append = null)
        {
            $q = array();
            $count = count($campos);
            for($i = 0; $i < $count; $i++)
            {
                if(sizeof($comparadores))
                {
                    if($comparadores[$i] == "=")
                        $q[$campos[$i]] = $filtros[$i];
                    else{
                        if($comparadores[$i] == "<")
                            $q[$campos[$i]] = array('$lt' => $filtros[$i]);
                        else if($comparadores[$i] == ">")
                            $q[$campos[$i]] = array('$gt' => $filtros[$i]);
                        else if($comparadores[$i] == "<=")
                            $q[$campos[$i]] = array('$lte' => $filtros[$i]);
                        else if($comparadores[$i] == ">=")
                            $q[$campos[$i]] = array('$gte' => $filtros[$i]);
                        else if($comparadores[$i] == "like"){
                            $like_var = str_replace('%', '', $filtros[$i]);
                            // $q[$campos[$i]] = new MongoRegex("/$like_var/i");
                            $q[$campos[$i]] = array('$regex' => "$like_var", '$options'=>"i");
                        }
                        else if($comparadores[$i] == "= in"){
                            $q[$campos[$i]] = array('$in' => $filtros[$i]);
                        }
                        else if($comparadores[$i] == "!="){
                            $q[$campos[$i]] = array('$ne' => $filtros[$i]);
                        }
                    }
                }
                else
                    $q[$campos[$i]] = $filtros[$i];
            }

            $ap = array();
            if(isset($append["limit"]))
                $ap["limit"] = $append["limit"];

            if(isset($append["skip"]))
                $ap["limit"] = $append["skip"];

            return Mongo::count(self::$con->{$collectionName}, $q, $ap);
        }//metodo RETORNACONTAGEM

        public static function retornaTodosCustom(string $collectionName, array $customQuery)
        {
            $r = [];
            $cursor = Mongo::find(self::$con->{$collectionName}, $customQuery, null);
            $it = new \IteratorIterator($cursor);
            $it->rewind(); // o rewind no cursor é obrigatório

            while($doc = $it->current()) {
                $r[] = $doc;
                $it->next();
            }

            return (empty($r)) ? [] : $r;
        }//metodo RETORNATODOSCUSTOM

    #endregion

}//classe