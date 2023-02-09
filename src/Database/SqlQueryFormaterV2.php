<?php

/**
 * Fichier de classe.
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  "CVS: cvs_id"
 * @link     Link
 */

namespace App\Database;

/**
 * Gère les requêtes SQL.
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @link     Link
 */
class SqlQueryFormaterV2
{
    private $data;
    private $selectedLength;
    private $selected;

    private $table;

    private $clauses;
    private $where;
    private $whereLength;

    private $order;

    private $insertInto;
    private $cols;
    private $colsLength;
    private $colonnes;

    private $vals;
    private $valuesLength;
    private $values;

    private $update;

    private $set;
    private $setedLength;
    private $seted;

    private $itemsNumber;
    private $firstItemIndex;

    private $query;

    /**
     * Permet de spécifier les données à récupérer de la base de données.
     * 
     * @param string $data le nom de la colonne dans laquelle on récupère les données.
     * 
     * @return self Retourne la même instance.
     */
    public function select(string $data)
    {
        $this->data .= $data . ", ";
        $this->selectedLength = strlen($this->data);
        $this->selected = substr($this->data, 0, $this->selectedLength - 2);
        $this->query = "SELECT $this->selected";
        return $this;
    }
        
    /**
     * Permet d'insérer des données dans une table.
     * 
     * @param string $table La table dans laquelle on insère les données.
     * 
     * @return self Retourne la même instance.
     */
    public function insertInto(string $table)
    {
        $this->insertInto = $table;
        $this->query = "INSERT INTO $this->insertInto";
        return $this;
    }
    
    /**
     * Permet de mettre à jour les données d'une base d'une table.
     * 
     * @param string $table La table à mettre à jour.
     * 
     * @return self Retourne la même instance.
     */
    public function update(string $table)
    {
        $this->update = $table;
        $this->query = "UPDATE $this->update";
        return $this;
    }
    
    /**
     * Permet de spécifier la table de laquelle on récupère les données.
     * 
     * @param string $table Le nom de la table.
     * 
     * @return self Retourne la même instance.
     */
    public function from(string $table)
    {
        $this->table = $table;
        $this->query .= " FROM $this->table";
        return $this;
    }

    /**
     * Permet de spécifier la colonne à mettre à jour
     * 
     * @param string $col Le nom de la colonne à mettre à jour.
     * 
     * @return self Retourne la même instance.
     */
    public function set(string $col)
    {
        $this->set .= $col . " AND ";
        $this->setedLength = strlen($this->set);
        $this->seted = substr($this->set, 0, $this->setedLength - 5);
        $this->query .= " SET $this->seted";
        return $this;
    }
    
    /**
     * Permet de spécifier le colonne dans laquelle on insère la donnée.
     * 
     * @param string $col La colonne dans laquelle on insère la donnée.
     * 
     * @return self Retourne la même instance.
     */
    public function cols(string $col)
    {
        if ($this->insertInto) {
            $this->cols .= $col . ", ";
            $this->colsLength = strlen($this->cols);
            $this->colonnes = substr($this->cols, 0, $this->colsLength - 2);
            $this->query .= "($this->colonnes)";
        }
        return $this;
    }
    
    /**
     * Permet de donner la valeur à insérer dans la colonne.
     * 
     * @param string $value La valeur à insérer dans la colonne.
     * 
     * @return self Retourne la même instance.
     */
    public function values(string $value)
    {
        if ($this->set) {
            $this->vals .= "'$value', ";
            $this->valuesLength = strlen($this->vals);
            $this->values = substr($this->vals, 0, $this->valuesLength - 2);
            $this->query .= " VALUES ($this->values)";
        }
        return $this;
    }

    /**
     * Permet de spécifier des clauses WHERE dans la requête.
     * 
     * @param string $where La clause.
     * 
     * @return self Retourne la même instance.
     */
    public function where(string $where)
    {
        if ($this->selected || $this->update) {
            $this->clauses .= $where . " AND ";
            $this->whereLength = strlen($this->clauses);
            $this->where = substr($this->clauses, 0, $this->whereLength - 5);
            $this->query .= " WHERE $this->where";
        }
        return $this;
    }

    /**
     * Permet de passer une clause between à la requête.
     * 
     * @param int $begin
     * @param int $end
     * 
     * @return self
     */
    public function between($col, int $begin, int $end)
    {
        if ($this->where) {
            if ($end < $begin) {
                $this->query .= " AND $col BETWEEN $end AND $begin";
            } else {
               $this->query .= " AND $col BETWEEN $begin AND $end";
            }
        }
        return $this;
    }
    
    /**
     * Permet de spécifier un ordre de retour des données.
     * 
     * @param string $col La colonne sur laquelle ordonner les retours.
     * 
     * @return self Retourne la même instance.
     */
    public function orderBy(string $col)
    {
        if ($this->where) {
            $this->order = $col;
            $this->query .= " ORDER BY $this->order";
        }
        return $this;
    }

    /**
     * Méthode non terminée.
     * 
     * @param string $table 
     * 
     * @return self Retourne la même instance.
     */
    public function alter(string $table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Retourne une requête pour compter les entrées d'une table.
     * 
     * @param string $toCount La colonne sur laquelle on veut compter les entrées.
     * 
     * @return self
     */
    public function count(string $toCount)
    {
        $this->select("count($toCount)");
    }

    /**
     * @param int $itemsNumber    Le nombre d'élément qui seront retournés.
     * @param int $firstItemIndex L'index de l'occurence à partir de laquelle les
     *                            éléments seront retournés tout en sachant que le premier
     *                            item de la table a pour index 0.
     */
    public function limit(int $itemsNumber, int $firstItemIndex = null)
    {
        if ($this->selected && $this->table) {
            $this->itemsNumber = $itemsNumber;

            if ($this->itemsNumber) {
                $this->firstItemIndex = $firstItemIndex;
            }

            if (null == $this->firstItemIndex && $this->itemsNumber) {
                $this->query .= " LIMIT $this->itemsNumber";
            } elseif (($this->firstItemIndex || $this->firstItemIndex == 0) && $this->itemsNumber) {
                $this->query .= " LIMIT $this->firstItemIndex, $this->itemsNumber";
            }
        }

        return $this;
    }

    /**
     * Retourne la requête finale sous forme de chaîne de caractère.
     * 
     * @return string
     */
    public function returnQueryString()
    {
        return $this->query;
    }
    
}