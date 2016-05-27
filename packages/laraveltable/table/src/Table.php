<?php

namespace Laraveltable\Table;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Schema;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class Table extends Model
{
    private $currentTable;
    private $query; // query
    private $byPages = 10; // limit
    private $page; // offset
    private $orderBy;
    private $joins = [];
    private $direction;
    private $datas;
    private $columnSorted;
    private $columnDisplayed;
    private $nbResult;
    private $nbPage;
    private $search;
    private $linkShowed = 5;
    private $callbackData = [];
    private $callbackSearch = [];
    private $wheres;

    /*
     * Initialise le nom de la table et le nombre de resultats
     */
    public function __construct($currentTable)
    {
        parent::__construct();
        $this->currentTable = $currentTable;
        $this->columnDisplayed = $this->columnSorted = Schema::getColumnListing($currentTable);
        $this->columnDisplayed = array_combine($this->columnDisplayed, $this->columnDisplayed);
    }

    /* initialise le nombre de page */
    public function initPage()
    {
        !empty($_GET['page']) ? $page = $_GET['page'] : $page = null;
        (string)(int)$page == $page && $page > 0 ? $this->page = (int)$page : $this->page = 1;
    }

    public function initOrderBy()
    {
        !empty($_GET['orderby']) ? $orderBy = $_GET['orderby'] : $orderBy = null;
        if (in_array($orderBy, $this->columnSorted)) {
            $this->orderBy = $orderBy;
            $this->initdirection();
            $this->query = $this->query->orderBy($this->orderBy, $this->direction);
        }

    }

    public function initdirection()
    {
        !empty($_GET['direction']) ? $direction = $_GET['direction'] : $direction = "asc";
        $direction == "asc" ? $this->direction = 'asc' : $this->direction = 'desc';
    }

    public function initJoin()
    {
        foreach ($this->joins as $join) {
            $table = $join[0];
            $relation1 = $join[1];
            $symbol = $join[2];
            $relation2 = $join[3];
            $this->query = $this->query->leftJoin($table, $relation1, $symbol, $relation2);
        }
    }

    public function initWhere()
    {
        if (!empty($this->wheres)) {
            foreach ($this->wheres as $where) {
                $this->query = $this->query->where($where[0], $where[1], $where[2]);
            }
        }

    }

    public function initSearch()
    {

        // get search field
        $this->search = !empty(Input::get('search')) ? Input::get('search') : null;
        //prepare query
        if (!empty($this->search)) {
            $this->query = $this->query->where(function ($query) {
                foreach ($this->columnDisplayed as $k => $v) {

                    $query->orWhere($k, 'like', '%' . /*$this->search*/
                        $this->callbackSearch($k, $this->search) . '%');
                }
            });
        }
    }

    public function initNbResults()
    {
        $this->nbResult = $this->query->count();
    }

    public function initNbPages()
    {
        $this->nbPage = ceil($this->nbResult / $this->byPages);
    }

    public function initLimit()
    {
        $this->query = $this->query->limit($this->byPages);
    }

    public function initOffset()
    {
        $this->query = $this->query->offset($this->byPages * ($this->page - 1));
    }

    private function executeQuery()
    {

        $this->query = DB::table($this->currentTable);
        $this->initPage();
        $this->initOrderBy();
        $this->initSearch();
        $this->initWhere();
        $this->initJoin();
        $this->initNbResults(); // ! important to do this before limit && offset
        $this->initNbPages(); // ! important to do this before limit && offset
        $this->initLimit();
        $this->initOffset();

        $this->datas = $this->query->get();
    }

    public function prepareView()
    {
        $this->executeQuery();
    }

    public function getHtmlTable()
    {
        $html = '<table class="table table-responsive table-striped">';
        $html .= '<thead>';
        $html .= '<tr>';
        // header
        foreach ($this->columnDisplayed as $k => $v) {
            $orderByIcon = !empty($this->orderBy) && $this->orderBy == $k?
                $this->direction=='asc' ? '<span class="glyphicon glyphicon-arrow-up"></span>': '<span class="glyphicon glyphicon-arrow-down"></span>' :''; // class des fleches de direction
            $sortableClass = in_array($k, $this->columnSorted) ? 'sortable' : ''; // applique la class sortable en fonction de $this->columnSorted
            $html .= '<th class="' . $sortableClass .'" data-column="' . $k .  '">' . $v . $orderByIcon .'</th>';
        }
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        //datas
        foreach ($this->datas as $data) {
            is_object($data) ? $data = get_object_vars($data) : $data = $data;
            $html .= '<tr>';
            foreach ($this->columnDisplayed as $k => $v) {
                $html .= '<th>' . /*$data[$k]*/
                    $this->callbackData($k, $data[$k]) . '</th>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';

        return $html;
    }

    public function getHtmlPagination()
    {
        if ($this->nbPage < 2) {
            return '';
        }
        $goToFirstClass = $previousClass = $this->page == 1 ? "disabled" : "";
        $goToLastClass = $nextClass = $this->nbPage == $this->page ? "disabled" : "";

        $html = '<ul class="pagination">';
        $html .= '<li class="go-to-first ' . $goToFirstClass . '"><a class ="a-pagination" data-page="1"><<</a></li>';
        $html .= '<li class="previous ' . $previousClass . '"><a class ="a-pagination" data-page="' . ($this->page - 1) . '"><</a></li>';

        for ($i = $this->page - $this->linkShowed; $i <= $this->page + $this->linkShowed; $i++) {
            if ($i > 0 && $i <= $this->nbPage) {
                $active = $i == $this->page ? "active" : "";
                $html .= '<li class="' . $active . '"><a class ="a-pagination" data-page="' . $i . '">' . $i . '</a></li>';
            }
        }
        $html .= '<li class="next ' . $nextClass . '"><a class ="a-pagination" data-page="' . ($this->page + 1) . '">></a></li>';
        $html .= '<li class="go-to-last ' . $goToLastClass . '"><a class ="a-pagination" data-page="' . $this->nbPage . '">>></a></li>';

        $html .= '</ul>';

        return $html;
    }

    public function getHtmlSearch($buttonName)
    {
        $html = '<input type="text" class="search" id="search-input">';

        $html .= '<button type="button" class="btn btn-primary" id="search-button">'.$buttonName.'</button>';

        return $html;
    }

    public function getHtmlSearchReset($buttonName)
    {

        $html = '<button type="button" class="btn btn-primary" id="reset-search-button">'.$buttonName.'</button>';

        return $html;
    }

    /**
     * @param $columnName
     * @param $callback
     *
     * On créé un callback avec une fonction stockée dans un array
     */
    public function addCallBackData($columnName, $callback)
    {
        $this->callbackData[$columnName] = $callback;

    }

    public function addCallBackSearch($columnName, $callback)
    {
        $this->callbackSearch[$columnName] = $callback;

    }

    /**
     * @param $columnName
     * @param $data
     * @return mixed
     *
     * Si il y a un callback on appel la fonction
     * Sinon on retourne les données de base
     * il est appelé dans la génération du html
     */
    public function callbackData($columnName, $data)
    {
        if (!empty($this->callbackData[$columnName])) {
            return $this->callbackData[$columnName]($data);
        } else {
            return $data;
        }
    }

    public function callbackSearch($columnName, $data)
    {
        if (!empty($this->callbackSearch[$columnName])) {
            return $this->callbackSearch[$columnName]($data);
        } else {
            return $data;
        }
    }


    // -------------- GETTER && SETTER


    public function getQuery()
    {
        return $this->query;
    }

    public function setQuery($query)
    {
        $this->query = $query;
    }

    public function getByPages()
    {
        return $this->byPages;
    }

    public function setByPages($byPages)
    {
        $this->byPages = $byPages;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function setPage($page)
    {
        $this->page = $page;
    }

    public function getOrderBy()
    {
        return $this->orderBy;
    }

    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
    }

    public function getDirection()
    {
        return $this->direction;
    }

    public function setDirection($direction)
    {
        $this->direction = $direction;
    }

    public function getDatas()
    {
        return $this->datas;
    }

    public function setColumnDisplayed(array $columnDisplayed)
    {
        $this->columnDisplayed = $columnDisplayed;
    }

    public function setColumnSorted(array $columnSorted)
    {
        $this->columnSorted = $columnSorted;
    }

    public function addJoin($join)
    {
        $this->joins[] = $join;
    }

    public function addWhere($where)
    {
        $this->wheres [] = $where;
    }

    public function getNbResult()
    {
        return $this->nbResult;
    }
}
