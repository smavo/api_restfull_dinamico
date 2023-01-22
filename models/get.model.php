<?php

require_once "connection.php";

class GetModel
{

    /*===== Peticiones GET sin filtro =====*/
    static public function getData($table, $select, $orderBy, $orderMode, $startAt, $endAt){

        /*===== Peticiones GET - Sin Ordenar Datos - Sin Limitar Datos =====*/
        $sql = "SELECT $select FROM $table ";

        /*===== Peticiones GET + Ordenar Datos - Sin Limitar Datos =====*/
        if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null) {
            $sql = "SELECT $select FROM $table ORDER BY $orderBy $orderMode";
        }

        /*===== Peticiones GET + Ordenar Datos + Limitar de datos =====*/
        if ($orderBy != null && $orderMode != null && $startAt != null && $endAt != null) {
            $sql = "SELECT $select FROM $table ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
        }

        /*===== Peticiones GET - Sin Ordenar Datos + Limitar Datos =====*/
        if ($orderBy == null && $orderMode == null && $startAt != null && $endAt != null) {
            $sql = "SELECT $select FROM $table LIMIT $startAt, $endAt";
        }

        $stmt = Connection::connect()->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS); /* para nos mostrar los indices agregar: PDO::FETCH_CLASS */
    }

    /*===== Peticiones GET con filtro =====*/
    static public function getDataFilter($table, $select, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt){

        $linkToArray = explode(",", $linkTo);
        $equalToArray = explode(",", $equalTo);
        $linkToText = "";

        if (count($linkToArray) > 1) {

            foreach ($linkToArray as $key => $value) {
                if ($key > 0) {
                    $linkToText .= "AND " . $value . " = :" . $value . " ";
                }
            }
        }

         /*===== Peticiones GET + Filtro - Sin Ordenar Datos - Sin Limitar Datos =====*/
        $sql = "SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToText ";

        /*===== Peticiones GET + Filtro + Ordenar Datos - Sin Limitar Datos =====*/
        if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null) {
            $sql = "SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode";
        }

        /*===== Peticiones GET + Filtro + Ordenar Datos + Limitar de datos =====*/
        if ($orderBy != null && $orderMode != null && $startAt != null && $endAt != null) {
            $sql = "SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
        }

        /*===== Peticiones GET + Filtro - Sin Ordenar Datos + Limitar Datos =====*/
        if ($orderBy != null && $orderMode != null && $startAt != null && $endAt != null) {
            $sql = "SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToText LIMIT $startAt, $endAt";
        }

        $stmt = Connection::connect()->prepare($sql);

        foreach ($linkToArray as $key => $value) {
            $stmt->bindParam(":" . $value, $equalToArray[$key], PDO::PARAM_STR);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    /*===== Peticiones GET sin filtro entre tablas relacionadas =====*/
    static public function getRelData($rel, $type, $select, $orderBy,$orderMode,$startAt,$endAt){

		$relArray = explode(",", $rel);
		$typeArray = explode(",", $type);
		$innerJoinText = "";


        if(count($relArray)>1){

			foreach ($relArray as $key => $value) {

				if($key > 0){
					$innerJoinText .= "INNER JOIN ".$value." ON ".$relArray[0].".id_".$typeArray[$key]."_".$typeArray[0] ." = ".$value.".id_".$typeArray[$key]." ";
				}

			}

            /*===== Peticiones GET - Sin Ordenar Datos - Sin Limitar Datos =====*/
            $sql = "SELECT $select FROM $relArray[0] $innerJoinText ";

            /*===== Peticiones GET + Ordenar Datos - Sin Limitar Datos =====*/
			if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
				$sql = "SELECT $select FROM $relArray[0] $innerJoinText ORDER BY $orderBy $orderMode";
			}

            /*===== Peticiones GET + Ordenar Datos + Limitar de datos =====*/
			if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
				$sql = "SELECT $select FROM $relArray[0] $innerJoinText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
			}

            /*===== Peticiones GET - Sin Ordenar Datos + Limitar Datos =====*/
			if($orderBy == null && $orderMode == null && $startAt != null && $endAt != null){
				$sql = "SELECT $select FROM $relArray[0] $innerJoinText LIMIT $startAt, $endAt";
			}


            $stmt = Connection::connect()->prepare($sql);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_CLASS);

		} else{

			return null;
		}
    
    }

}
